<?php

namespace App\Http\Controllers;

use App\DataTables\SectionsDataTable;
use App\Models\Section;
use App\Http\Requests\StoreSectionRequest;
use App\Http\Requests\UpdateSectionRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SectionController extends Controller {
    protected Section $model;
    protected SectionsDataTable $dataTable;
    protected string $title;
    protected string $indexRoute;
    protected array $formFields;

    public function __construct(Section $model, SectionsDataTable $dataTable)
    {
        $this->model = $model;
        $this->dataTable = $dataTable;
        $this->title = __('backend.section');
        $this->indexRoute = 'sections';
        $this->formFields = $this->initializeFormFields();
    }

    protected function initializeFormFields(): array
    {
        return [
            ['name' => 'name', 'type' => 'text', 'label' => __('backend.name')],
            ['name' => 'class', 'type' => 'text', 'label' => __('backend.class')],
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
    public function store(StoreSectionRequest $request)
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
    public function show(Section $section)
    {
        if (request()->ajax())
        {
            return response()->json($section);
        }

        return view('backend.common.show', [
            'title' => $this->title,
            'data' => $section,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Section $section)
    {
        if (request()->ajax())
        {
            return response()->json($section);
        }

        return view('backend.common.edit', [
            'title' => $this->title,
            'data' => $section,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSectionRequest $request, Section $section)
    {
        $section->update($request->validated());

        return $this->successResponse(
            $this->title . __('backend.updated_successfully'),
            route($this->indexRoute . '.index')
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Section $section)
    {
        $section->delete();

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
