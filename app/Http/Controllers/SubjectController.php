<?php

namespace App\Http\Controllers;

use App\DataTables\SubjectsDataTable;
use App\Models\Subject;
use App\Http\Requests\StoreSubjectRequest;
use App\Http\Requests\UpdateSubjectRequest;
use App\Models\Section;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SubjectController extends Controller {
    protected Subject $model;
    protected SubjectsDataTable $dataTable;
    protected string $title;
    protected string $indexRoute;
    protected array $formFields;
    private $imageService;

    public function __construct(Subject $model, SubjectsDataTable $dataTable)
    {
        $this->model = $model;
        $this->dataTable = $dataTable;
        $this->title = __('backend.subject');
        $this->indexRoute = 'subjects';
        $this->formFields = $this->initializeFormFields();
    }

    protected function initializeFormFields(): array
    {
        return [
            ['name' => 'name', 'type' => 'text', 'label' => __('backend.name')],
            [
                'name' => 'section_id',
                'type' => 'select',
                'label' => __('backend.section'),
                'options' =>
                    ['' => __('backend.select')] +
                    Section::orderBy('name', 'asc')
                        ->pluck('name', 'id')
                        ->toArray(),
            ],
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
    public function store(StoreSubjectRequest $request)
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
    public function show(Subject $subject)
    {
        if (request()->ajax())
        {
            return response()->json($subject);
        }

        return view('backend.common.show', [
            'title' => $this->title,
            'data' => $subject,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Subject $subject)
    {
        if (request()->ajax())
        {
            return response()->json($subject);
        }

        return view('backend.common.edit', [
            'title' => $this->title,
            'data' => $subject,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSubjectRequest $request, Subject $subject)
    {
        $subject->update($request->validated());

        return $this->successResponse(
            $this->title . __('backend.updated_successfully'),
            route($this->indexRoute . '.index')
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subject $subject)
    {
        $subject->delete();

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
