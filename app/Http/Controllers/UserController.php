<?php

namespace App\Http\Controllers;

use App\DataTables\UsersDataTable;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Services\AuditService;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserController extends Controller {
    protected User $model;
    protected UsersDataTable $dataTable;
    protected string $title;
    protected string $indexRoute;
    protected array $formFields;
    private $auditService;
    private $imageService;

    public function __construct(User $model, UsersDataTable $dataTable, AuditService $auditService, ImageService $imageService)
    {
        $this->model = $model;
        $this->dataTable = $dataTable;
        $this->title = __('backend.user');
        $this->indexRoute = 'users';
        $this->formFields = $this->initializeFormFields();
        $this->auditService = $auditService;
        $this->imageService = $imageService;
    }

    protected function initializeFormFields(): array
    {
        return [
            ['name' => 'name', 'type' => 'text', 'label' => __('backend.name')],
            ['name' => 'email', 'type' => 'email', 'label' => __('backend.email')],
            ['name' => 'mobile', 'type' => 'number', 'label' => __('backend.mobile')],
            ['name' => 'username', 'type' => 'text', 'label' => __('backend.username')],
            ['name' => 'password', 'type' => 'password', 'label' => __('backend.password')],
            ['name' => 'password_confirmation', 'type' => 'password', 'label' => __('backend.password_confirmation')],
            [
                'name' => 'role',
                'type' => 'select',
                'label' => __('backend.role'),
                'options' =>
                    ['' => __('backend.select')] +
                    Role::orderBy('name', 'asc')
                        ->whereNotIn('name', ['systemadmin'])
                        ->pluck('display_name', 'name')
                        ->toArray(),
            ],
            [
                'name' => 'profile_image',
                'type' => 'file',
                'label' => __('backend.profile_image'),
                'attributes' => [
                    'accept' => 'image/*',
                    'data-coreui-toggle' => 'tooltip',
                    'data-coreui-placement' => 'top',
                    'title' => __('backend.upload_profile_image'),
                ],
            ],
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): mixed
    {
        $viewData = [
            'name' => $this->title,
            'title' => $this->title . __('backend.list'),
            'route' => $this->indexRoute,
            'formFields' => $this->formFields,
        ];

        return $this->dataTable->render('backend.common.index', $viewData);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request): JsonResponse|RedirectResponse
    {
        $data = $request->validated();

        $data['password'] = Hash::make($data['password']); // Always hash password before storing

        $this->model->create($data);

        if ($request->hasFile('profile_image'))
        {
            $this->imageService->uploadImage($request->file('profile_image'), 'profile', $this->model, 500, 500);
        }

        $this->model->syncRoles($request->role);

        return $this->successResponse(
            $this->title . __('backend.stored_successfully'),
            route($this->indexRoute . '.index')
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user): JsonResponse|\Illuminate\View\View
    {
        if (request()->ajax())
        {
            return response()->json($user);
        }

        return view('backend.common.show', [
            'title' => $this->title,
            'data' => $user,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        if (request()->ajax())
        {
            return response()->json($user);
        }

        return view('backend.common.edit', [
            'title' => $this->title,
            'data' => $user,
        ]);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user): JsonResponse|RedirectResponse
    {
        $data = $request->validated();

        if (!empty($data['password']))
        {
            $data['password'] = Hash::make($data['password']);
        } else
        {
            unset($data['password']); // Prevent overriding existing password
        }

        $user->update($data);

        if ($request->hasFile('profile_image'))
        {
            if (!empty($user->image)) {
                $this->imageService->deleteImage($user->image);
            }
            $this->imageService->uploadImage($request->file('profile_image'), 'profile', $user, 500, 500);
        }

        $user->syncRoles($request->role);

        return $this->successResponse(
            $this->title . __('backend.updated_successfully'),
            route($this->indexRoute . '.index')
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user): JsonResponse|RedirectResponse
    {
        $user->delete();

        return $this->successResponse(
            $this->title . __('backend.deleted_successfully'),
            route($this->indexRoute . '.index'),
            'info'
        );
    }

    /**
     * Update the status of the specified resource.
     */
    public function status(Request $request): JsonResponse|RedirectResponse
    {
        $user = $this->model->find($request->id);

        if (!$user)
        {
            return $this->errorResponse(
                $this->title . __('backend.not_found'),
                route($this->indexRoute . '.index')
            );
        }

        $user->update(['status' => $request->status]);

        $type = $request->status == 1 ? 'success' : 'info';
        $message = $request->status == 1
            ? $this->title . __('backend.activated_successfully')
            : $this->title . __('backend.inactivated_successfully');

        return $this->successResponse($message, route($this->indexRoute . '.index'), $type);
    }

    public function edit_permission($user_id)
    {
        $title = $this->title . " Edit Permissions";
        $permissions = [];
        $route = $this->indexRoute;
        $user = User::find($user_id);

        $permissionList = auth()->user()->hasRole('systemadmin') ? Permission::all() : auth()->user()->getAllPermissions();

        $role_permissions = $user->getAllPermissions()->pluck('id', 'id')->toArray();
        foreach ($permissionList as $key => $value)
        {
            $permissions[$value['module_name']][$value['id']] = $value['display_name'];
        }
        return view('backend.role.permission', compact('title', 'route', 'user_id', 'permissions', 'role_permissions'));
    }

    public function update_permission(Request $request)
    {
        $user = User::find($request->id);
        $permissionIds = $request->input('permission', []);
        $permissions = Permission::whereIn('id', $permissionIds)->get();

        foreach ($permissions as $permission)
        {
            $this->auditService->audit($permission->id, 'User Permission Update', [], $permission->toArray(), User::class);
        }

        $user->syncPermissions($permissions);

        return redirect()->route($this->indexRoute . '.index')->with('success', $this->title . ' ' . trans('backend.permission_update_message'));
    }

    /**
     * Helper method for successful responses.
     */
    protected function successResponse(
        string $message,
        string $route = null,
        string $type = 'success'
    ): JsonResponse|RedirectResponse {
        if (request()->ajax())
        {
            return response()->json([
                'type' => $type,
                'message' => $message,
            ], $type === 'success' ? 200 : 201);
        }

        return redirect()->to($route)->with($type, $message);
    }

    /**
     * Helper method for error responses.
     */
    protected function errorResponse(
        string $message,
        string $route = null,
        string $type = 'error'
    ): JsonResponse|RedirectResponse {
        if (request()->ajax())
        {
            return response()->json([
                'type' => $type,
                'message' => $message,
            ], 404);
        }

        return redirect()->to($route)->with($type, $message);
    }
}