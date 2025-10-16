<?php

namespace App\Http\Controllers;

use App\DataTables\ResultsDataTable;
use App\Models\Result;
use App\Http\Requests\StoreResultRequest;
use App\Http\Requests\UpdateResultRequest;
use App\Models\Exam;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ResultController extends Controller {
    protected Result $model;
    protected ResultsDataTable $dataTable;
    protected string $title;
    protected string $indexRoute;
    protected array $formFields;
    private $imageService;

    public function __construct(Result $model, ResultsDataTable $dataTable)
    {
        $this->model = $model;
        $this->dataTable = $dataTable;
        $this->title = __('backend.result');
        $this->indexRoute = 'results';
        $this->formFields = $this->initializeFormFields();
    }

    protected function initializeFormFields(): array
    {
        return [
            [
                'name' => 'student_id',
                'type' => 'select',
                'label' => __('backend.student'),
                'options' =>
                    ['' => __('backend.select')] +
                    Student::orderBy('name_en', 'asc')
                        ->pluck('name_en', 'id')
                        ->toArray(),
            ],
            [
                'name' => 'subject_id',
                'type' => 'select',
                'label' => __('backend.subject'),
                'options' =>
                    ['' => __('backend.select')] +
                    Subject::orderBy('name', 'asc')
                        ->pluck('name', 'id')
                        ->toArray(),
            ],
            [
                'name' => 'exam_id',
                'type' => 'select',
                'label' => __('backend.exam'),
                'options' =>
                    ['' => __('backend.select')] +
                    Exam::orderBy('name', 'asc')
                        ->pluck('name', 'id')
                        ->toArray(),
            ],
            ['name' => 'marks', 'type' => 'number', 'label' => __('backend.marks')],
            ['name' => 'grade', 'type' => 'text', 'label' => __('backend.grade')],
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
    public function store(StoreResultRequest $request)
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
    public function show(Result $result)
    {
        if (request()->ajax())
        {
            return response()->json($result);
        }

        return view('backend.common.show', [
            'title' => $this->title,
            'data' => $result,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Result $result)
    {
        if (request()->ajax())
        {
            return response()->json($result);
        }

        return view('backend.common.edit', [
            'title' => $this->title,
            'data' => $result,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateResultRequest $request, Result $result)
    {
        $result->update($request->validated());

        return $this->successResponse(
            $this->title . __('backend.updated_successfully'),
            route($this->indexRoute . '.index')
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Result $result)
    {
        $result->delete();

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
