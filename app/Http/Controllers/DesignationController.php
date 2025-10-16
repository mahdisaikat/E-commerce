<?php

namespace App\Http\Controllers;

use App\DataTables\DesignationsDataTable;
use App\Models\Designation;
use App\Http\Requests\StoreDesignationRequest;
use App\Http\Requests\UpdateDesignationRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class DesignationController extends Controller {
    protected Designation $model;
    protected DesignationsDataTable $dataTable;
    protected string $title;
    protected string $indexRoute;
    protected array $formFields;

    public function __construct(Designation $model, DesignationsDataTable $dataTable)
    {
        $this->model = $model;
        $this->dataTable = $dataTable;
        $this->title = __('backend.designation');
        $this->indexRoute = 'designations';
        $this->formFields = $this->initializeFormFields();
    }

    protected function initializeFormFields(): array
    {
        return [
            ['name' => 'title', 'type' => 'text', 'label' => __('backend.title')],
            ['name' => 'description', 'type' => 'textarea', 'label' => __('backend.description')],
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
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
    public function store(StoreDesignationRequest $request)
    {
        $this->model->create($request->validated());

        return $this->successResponse(
            $this->title . __('backend.stored_successfully'),
            route($this->indexRoute . '.index')
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Designation $designation)
    {
        if (request()->ajax())
        {
            return response()->json($designation);
        }

        return view('backend.common.show', [
            'title' => $this->title,
            'data' => $designation,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Designation $designation)
    {
        if (request()->ajax())
        {
            return response()->json($designation);
        }

        return view('backend.common.edit', [
            'title' => $this->title,
            'data' => $designation,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDesignationRequest $request, Designation $designation)
    {
        $data = $request->validated();

        $designation->update($data);

        return $this->successResponse(
            $this->title . __('backend.updated_successfully'),
            route($this->indexRoute . '.index')
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Designation $designation)
    {
        $designation->delete();

        return $this->successResponse(
            $this->title . __('backend.deleted_successfully'),
            route($this->indexRoute . '.index'),
            'info'
        );
    }

    public function status(Request $request)
    {
        $model = $this->model->find($request->id);

        if (is_null($model))
        {
            return $this->errorResponse('not found.');
        }

        $model->status = $request->status;
        $model->save();

        $type = $request->status == 1 ? 'success' : 'info';
        $message = $request->status == 1 ? $this->title . '' . __('activated_successfully') : $this->title . ' ' . __('inactivated_successfully');

        return $this->successResponse($message, $type, false);
    }

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
