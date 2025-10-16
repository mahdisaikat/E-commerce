<?php

namespace App\Http\Controllers;

use App\DataTables\TeachersDataTable;
use App\Models\Designation;
use App\Models\Teacher;
use App\Http\Requests\StoreTeacherRequest;
use App\Http\Requests\UpdateTeacherRequest;
use App\Services\ImageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TeacherController extends Controller {
    protected Teacher $model;
    protected TeachersDataTable $dataTable;
    protected string $title;
    protected string $indexRoute;
    protected array $formFields;
    private $imageService;

    public function __construct(Teacher $model, TeachersDataTable $dataTable, ImageService $imageService)
    {
        $this->model = $model;
        $this->dataTable = $dataTable;
        $this->title = __('backend.teacher');
        $this->indexRoute = 'teachers';
        $this->formFields = $this->initializeFormFields();
    }

    protected function initializeFormFields(): array
    {
        return [
            ['name' => 'name', 'type' => 'text', 'label' => __('backend.name')],
            [
                'name' => 'designation_id',
                'type' => 'select',
                'label' => __('backend.designation'),
                'options' =>
                    ['' => __('backend.select')] +
                    Designation::orderBy('title', 'asc')
                        ->pluck('title', 'id')
                        ->toArray(),
            ],
            ['name' => 'email', 'type' => 'email', 'label' => __('backend.email')],
            ['name' => 'phone', 'type' => 'number', 'label' => __('backend.phone')],
            ['name' => 'address', 'type' => 'textarea', 'label' => __('backend.address')],
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
    public function store(StoreTeacherRequest $request)
    {
        $this->model->create($request->validated());

        if ($request->hasFile('profile_image'))
        {
            $this->imageService->uploadImage($request->file('profile_image'), 'profile', $this->model, 500, 500);
        }

        return $this->successResponse(
            $this->title . __('backend.stored_successfully'),
            route($this->indexRoute . '.index')
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Teacher $teacher)
    {
        if (request()->ajax())
        {
            return response()->json($teacher);
        }

        return view('backend.common.show', [
            'title' => $this->title,
            'data' => $teacher,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Teacher $teacher)
    {
        if (request()->ajax())
        {
            return response()->json($teacher);
        }

        return view('backend.common.edit', [
            'title' => $this->title,
            'data' => $teacher,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTeacherRequest $request, Teacher $teacher)
    {
        $teacher->update($request->validated());

        if ($request->hasFile('profile_image'))
        {
            $this->imageService->uploadImage($request->file('profile_image'), 'profile', $teacher, 500, 500);
        }

        return $this->successResponse(
            $this->title . __('backend.updated_successfully'),
            route($this->indexRoute . '.index')
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Teacher $teacher)
    {
        $teacher->delete();

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
