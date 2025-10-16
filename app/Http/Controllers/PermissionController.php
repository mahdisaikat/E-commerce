<?php

namespace App\Http\Controllers;

use App\DataTables\PermissionDataTable;
use App\Http\Requests\StorePermissionRequest;
use App\Http\Requests\UpdatePermissionRequest;
use App\Models\Permission;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class PermissionController extends Controller {
    protected Permission $model;
    protected PermissionDataTable $dataTable;
    protected string $title;
    protected string $indexRoute;
    protected array $formFields;

    public function __construct(Permission $model, PermissionDataTable $dataTable)
    {
        $this->model = $model;
        $this->dataTable = $dataTable;
        $this->title = __('backend.permission');
        $this->indexRoute = 'permissions';
        $this->formFields = $this->initializeFormFields();
    }

    protected function initializeFormFields(): array
    {
        return [
            ['name' => 'name', 'type' => 'text', 'label' => __('backend.name')],
            ['name' => 'display_name', 'type' => 'text', 'label' => __('backend.display_name')],
            ['name' => 'module_name', 'type' => 'text', 'label' => __('backend.module_name')],
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
    public function store(StorePermissionRequest $request): JsonResponse|RedirectResponse
    {
        $data = $request->validated();

        $data['guard_name'] = 'web';

        $this->model->create($data);

        return $this->successResponse(
            $this->title . __('backend.stored_successfully'),
            route($this->indexRoute . '.index')
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Permission $permission): JsonResponse|\Illuminate\View\View
    {
        if (request()->ajax())
        {
            return response()->json($permission);
        }

        return view('backend.common.show', [
            'title' => $this->title,
            'data' => $permission,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Permission $permission)
    {
        if (request()->ajax())
        {
            return response()->json($permission);
        }

        return view('backend.common.edit', [
            'title' => $this->title,
            'data' => $permission,
        ]);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePermissionRequest $request, Permission $permission): JsonResponse|RedirectResponse
    {
        $data = $request->validated();

        $permission->update($data);

        return $this->successResponse(
            $this->title . __('backend.updated_successfully'),
            route($this->indexRoute . '.index')
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Permission $permission): JsonResponse|RedirectResponse
    {
        $permission->delete();

        return $this->successResponse(
            $this->title . __('backend.deleted_successfully'),
            route($this->indexRoute . '.index'),
            'info'
        );
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