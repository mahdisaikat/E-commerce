<?php

namespace App\Http\Controllers;

use App\DataTables\StudentsDataTable;
use App\Models\Student;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Imports\StudentsImport;
use App\Services\ImageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StudentsSampleExport;
use App\Models\Section;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class StudentController extends Controller {
    protected Student $model;
    protected StudentsDataTable $dataTable;
    protected string $title;
    protected string $indexRoute;
    protected array $formFields;
    private $imageService;

    public function __construct(Student $model, StudentsDataTable $dataTable, ImageService $imageService)
    {
        $this->model = $model;
        $this->dataTable = $dataTable;
        $this->title = __('backend.student');
        $this->indexRoute = 'students';
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
        ];

        return $this->dataTable->render('backend.student.index', $viewData);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.student.create', [
            'title' => __('backend.create_student'),
            'studentId' => $studentId = Student::generateStudentId(),
            'sections' => Section::where('status', 1)->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStudentRequest $request)
    {
        $this->model->create($request->validated());

        if ($request->hasFile('profile_image'))
        {
            $this->imageService->uploadImage($request->file('profile_image'), 'student', $this->model, 500, 500);
        }

        return $this->successResponse(
            $this->title . __('backend.stored_successfully'),
            route($this->indexRoute . '.index')
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
        return view('backend.student.show', [
            'title' => __('backend.students_details'),
            'student' => $student,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student)
    {
        return view('backend.student.edit', [
            'title' => __('backend.edit_student'),
            'student' => $student,
            'sections' => Section::where('status', 1)->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStudentRequest $request, Student $student)
    {
        $student->update($request->validated());

        if ($request->hasFile('profile_image'))
        {
            $this->imageService->uploadImage($request->file('profile_image'), 'student', $student, 500, 500);
        }

        return $this->successResponse(
            $this->title . __('backend.updated_successfully'),
            route($this->indexRoute . '.index')
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        $student->delete();

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

        return $this->successResponse($message, false, $type);
    }

    public function registration()
    {
        return view('backend.student.registration', [
            'title' => __('backend.student_registration'),
            'studentId' => $studentId = Student::generateStudentId(),
        ]);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        try
        {
            Excel::import(new StudentsImport, $request->file('file'));

            return $this->successResponse(
                __('backend.students_imported_successfully'),
                route($this->indexRoute . '.index'),
                'success'
            );
        } catch (\Exception $e)
        {
            return $this->successResponse(
                'Error importing students: ' . $e->getMessage(),
                null,
                'error'
            );
        }
    }

    public function downloadSample(): BinaryFileResponse
    {
        return Excel::download(new StudentsSampleExport, 'students_import_sample.xlsx');
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
