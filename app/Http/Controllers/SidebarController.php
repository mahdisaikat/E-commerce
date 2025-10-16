<?php

namespace App\Http\Controllers;

use App\DataTables\SidebarsDataTable;
use App\Models\Sidebar;
use App\Http\Requests\StoreSidebarRequest;
use App\Http\Requests\UpdateSidebarRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Permission;

class SidebarController extends Controller {
    protected Sidebar $model;
    protected SidebarsDataTable $dataTable;
    protected string $title;
    protected string $indexRoute;
    protected array $formFields;

    public function __construct(Sidebar $model, SidebarsDataTable $dataTable)
    {
        $this->model = $model;
        $this->dataTable = $dataTable;
        $this->title = __('backend.dynamic_sidebar');
        $this->indexRoute = 'sidebars';
        $this->formFields = $this->initializeFormFields();
    }

    protected function initializeFormFields(): array
    {
        // Get parent menu options
        $parentOptions = Sidebar::where('status', 1)
            ->whereNull('parent_id')
            ->orderBy('label', 'asc')
            ->pluck('label', 'id')
            ->toArray();

        // Get filtered route names
        $routes = Route::getRoutes();
        $routeNames = [];
        foreach ($routes as $route)
        {
            $routeName = $route->getName();
            if (
                $routeName &&
                in_array('web', $route->middleware()) &&
                !str_starts_with($routeName, 'sanctum.') &&
                !str_starts_with($routeName, 'ignition.') &&
                !str_starts_with($routeName, 'livewire.') &&
                !str_starts_with($routeName, 'lang.') &&
                !str_starts_with($routeName, 'verification.')
            )
            {
                $routeNames[$routeName] = $routeName; // key & value same
            }
        }
        ksort($routeNames); // sort alphabetically

        // Get permissions
        $permissions = Permission::orderBy('name', 'asc')
            ->pluck('name', 'id')
            ->toArray();

        return [
            [
                'name' => 'parent_id',
                'type' => 'select',
                'label' => __('backend.parent_menu'),
                'options' => ['' => __('backend.select')] + $parentOptions
            ],
            [
                'name' => 'route',
                'type' => 'select',
                'label' => __('backend.route'),
                'options' => ['' => __('backend.select')] + $routeNames
            ],
            ['name' => 'label', 'type' => 'text', 'label' => __('backend.label')],
            ['name' => 'serial', 'type' => 'text', 'label' => __('backend.serial')],
            [
                'name' => 'permission_id',
                'type' => 'select',
                'label' => __('backend.permission'),
                'options' => ['' => __('backend.select')] + $permissions
            ],
            ['name' => 'icon', 'type' => 'text', 'label' => __('backend.icon')],
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
    public function store(StoreSidebarRequest $request)
    {
        $data = $request->validated();

        $this->model->create($data);

        return $this->successResponse(
            $this->title . __('backend.stored_successfully'),
            route($this->indexRoute . '.index')
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Sidebar $sidebar)
    {
        if (request()->ajax())
        {
            return response()->json($sidebar);
        }

        return view('backend.common.show', [
            'title' => $this->title,
            'data' => $sidebar,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sidebar $sidebar)
    {
        if (request()->ajax())
        {
            return response()->json($sidebar);
        }

        return view('backend.common.edit', [
            'title' => $this->title,
            'data' => $sidebar,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSidebarRequest $request, Sidebar $sidebar)
    {
        $data = $request->validated();

        $sidebar->update($data);

        return $this->successResponse(
            $this->title . __('backend.updated_successfully'),
            route($this->indexRoute . '.index')
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sidebar $sidebar)
    {
        $sidebar->delete();

        return $this->successResponse(
            $this->title . __('backend.deleted_successfully'),
            route($this->indexRoute . '.index'),
            'info'
        );
    }

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
