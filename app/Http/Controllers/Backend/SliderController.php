<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\SlidersDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSliderRequest;
use App\Http\Requests\UpdateSliderRequest;
use App\Models\Slider;
use App\Services\ImageService;
use Illuminate\Http\Request;

class SliderController extends Controller
{
    protected $model;
    protected $dataTable;
    protected $title;
    protected $indexRoute;
    protected $imageService;
    protected $formFields;

    public function __construct(Slider $model, SlidersDataTable $dataTable, ImageService $imageService)
    {
        $this->model = $model;
        $this->dataTable = $dataTable;
        $this->title = trans('backend.slider');
        $this->indexRoute = 'sliders';
        $this->imageService = $imageService;
        $this->formFields = [
            ['name' => 'image', 'type' => 'file', 'label' => 'Image', 'accept' => 'image/*', 'max' => 1],
            // ['name' => 'title', 'type' => 'text', 'label' => 'Title'],
            // ['name' => 'link', 'type' => 'url', 'label' => 'Link'],
            ['name' => 'header', 'type' => 'text', 'label' => 'Header'],
            ['name' => 'details', 'type' => 'text', 'label' => 'Details'],
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'name' => $this->title,
            'title' => $this->title . ' List',
            'route' => $this->indexRoute,
            'formFields' => $this->formFields,
        ];

        return $this->dataTable->render('backend.common.index_image', $data);
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
    public function store(StoreSliderRequest $request)
    {
        $slider = Slider::create($request->validated());

        if ($request->hasFile('image')) {
            $this->imageService->uploadImage($request->file('image'), 'slider', $slider, 750, 500);
        }

        if (request()->ajax()) {
            return response()->json([
                'type' => 'success',
                'message' => $this->title . ' stored successfully',
            ], 201);
        }
        return redirect()->route($this->indexRoute . '.index')->with('success', $this->title . ' stored successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Slider $slider)
    {
        if (request()->ajax())
            return response()->json($slider, 200);

        return view('backend.common.edit', [
            'title' => $this->title . ' Edit',
            'data' => $slider,
            'formFields' => $this->formFields,
            'route' => $this->indexRoute,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Slider $slider)
    {
        if (request()->ajax())
            return response()->json($slider, 200);

        return view('backend.common.edit', [
            'title' => $this->title . ' Edit',
            'data' => $slider,
            'formFields' => $this->formFields,
            'route' => $this->indexRoute,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSliderRequest $request, Slider $slider)
    {
        $slider->update($request->validated());

        if ($request->hasFile('image')) {
            $existingImage = $slider->images()->where('type', 'slider')->first();
            if ($existingImage) {
                $this->imageService->deleteImage($existingImage);
            }
            $this->imageService->uploadImage($request->file('image'), 'slider', $slider, 500, 500);
        }

        if ($request->ajax())
            return response()->json([
                'type' => 'success',
                'message' => $this->title . ' updated successfully',
            ], 200);

        return redirect()->route($this->indexRoute . '.index')->with('success', $this->title . ' updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Slider $slider)
    {
        // Delete associated image if exists
        if ($image = $slider->images()->where('type', 'slider')->first()) {
            $this->imageService->deleteImage($image);
        }

        $slider->delete();

        $message = $this->title . ' deleted successfully';

        if (request()->ajax()) {
            return response()->json([
                'type' => 'info',
                'message' => $message,
            ], 200);
        }

        return redirect()->route($this->indexRoute . '.index')->with('success', $message);
    }

    public function status(Request $request)
    {
        $slider = $this->model->find($request->id);

        if (!$slider) {
            $message = $this->title . ' not found.';
            if ($request->ajax()) {
                return response()->json([
                    'type' => 'error',
                    'message' => $message,
                ], 404);
            }
            return redirect()->route($this->indexRoute . '.index')->with('error', $message);
        }

        $slider->status = $request->status;
        $slider->save();

        $isActive = (int) $request->status === 1;
        $type = $isActive ? 'success' : 'info';
        $message = $this->title . ' status ' . ($isActive ? 'activated' : 'inactivated') . ' successfully';

        if ($request->ajax())
            return response()->json([
                'type' => $type,
                'message' => $message,
            ], 200);

        return redirect()->route($this->indexRoute . '.index')->with('success', $message);
    }
}
