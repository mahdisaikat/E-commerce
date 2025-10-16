<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\CategoriesDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use App\Services\CategoryService;
use App\Services\ImageService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected $model;
    protected $dataTable;
    protected $title;
    protected $indexRoute;
    protected $categoryService;
    protected $imageService;
    protected $formFields;

    public function __construct(Category $model, CategoriesDataTable $dataTable, CategoryService $categoryService, ImageService $imageService)
    {
        $this->model = $model;
        $this->dataTable = $dataTable;
        $this->title = trans('backend.category');
        $this->indexRoute = 'categories';
        $this->categoryService = $categoryService;
        $this->imageService = $imageService;
        $this->formFields = [
            ['name' => 'name', 'type' => 'text', 'label' => 'Name'],
            ['name' => 'parent_category', 'type' => 'select', 'label' => 'Parent Category', 'options' => ['' => __('backend.select')] + Category::where('status', 1)->where('type', Category::TYPE_PRODUCT)->pluck('name', 'id')->toArray()],
            ['name' => 'image', 'type' => 'file', 'label' => 'Image', 'accept' => 'image/*', 'max' => 1],
            // ['name' => 'due_date', 'type' => 'date', 'label' => 'due_date'],
            // ['name' => 'amount', 'type' => 'number', 'label' => 'amount', 'min' => 0, 'step' => 0.01],
            ['name' => 'description', 'type' => 'textarea', 'label' => 'Description'],
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

        return $this->dataTable->render('backend.common.index_new', $data);
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
    public function store(StoreCategoryRequest $request)
    {
        $category = $this->categoryService->store($request->all(), Category::TYPE_PRODUCT);

        if ($request->hasFile('image')) {
            $this->imageService->uploadImage($request->file('image'), 'category', $category, 500, 500);
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
    public function show(Category $category)
    {
        if (request()->ajax()) {
            return response()->json($category->load('images', 'parent'), 200);
        } else {
            return view('backend.common.show', [
                'title' => $this->title,
                'data' => $category,
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        if (request()->ajax()) {
            return response()->json($category->load('images', 'parent'), 200);
        } else {
            return view('backend.common.edit', [
                'title' => $this->title,
                'data' => $category,
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $this->categoryService->update($category, $request->all());

        if ($request->hasFile('image')) {
            if ($category->images()->where('type', 'category')->exists()) {
                $this->imageService->deleteImage($category->images()->where('type', 'category')->first());
            }

            $this->imageService->uploadImage($request->file('image'), 'category', $category, 500, 500);
        }

        if (request()->ajax()) {
            return response()->json([
                'type' => 'success',
                'message' => $this->title . ' updated successfully',
            ], 200);
        }
        return redirect()->route($this->indexRoute . '.index')->with('success', $this->title . ' updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        if (!$category) {
            if (request()->ajax()) {
                return response()->json([
                    'type' => 'error',
                    'message' => $this->title . ' not found.',
                ], 200);
            }
            return redirect()->route($this->indexRoute . '.index')->with('error', $this->title . ' not found.');
        }

        $category->delete();

        if ($image = $category->images()->first()) {
            $this->imageService->deleteImage($image);
        }

        if (request()->ajax()) {
            return response()->json([
                'type' => 'info',
                'message' => $this->title . ' deleted successfully',
            ], 200);
        }
        return redirect()->route($this->indexRoute . '.index')->with('success', $this->title . ' deleted successfully');
    }

    public function status(Request $request)
    {
        $model = $this->model->find($request->id);

        if (!$model) {
            if (request()->ajax()) {
                return response()->json([
                    'type' => 'error',
                    'message' => $this->title . ' not found.',
                ], 200);
            }
            return redirect()->route($this->indexRoute . '.index')->with('error', $this->title . ' not found.');
        }

        $model->status = $request->status;
        $model->save();

        $type = $request->status == 1 ? 'success' : 'info';
        $message = $request->status == 1 ? $this->title . ' status activated successfully' : $this->title . ' status inactivated successfully';

        if (request()->ajax()) {
            return response()->json([
                'type' => $type,
                'message' => $message,
            ], 200);
        }
        return redirect()->route($this->indexRoute . '.index')->with('success', $message);
    }

    public function getCategory(Request $request)
    {
        $data = $this->model::where('status', 1)
            ->where('type', $request->type)
            ->when($request->parent_id, function ($query) use ($request) {
                $query->where('parent_id', $request->parent_id);
            })
            ->get();

        return response()->json($data, 200);
    }

    public function getCategoryProducts(Request $request)
    {
        $data = $this->model::where('status', 1)
            ->with([
                'images',
                'products' => function ($query) {
                    $query->orderBy('name', 'asc');
                },
                'products.stockProducts',
                'products.images'
            ])
            ->where('type', Category::TYPE_PRODUCT)
            ->when($request->parent_id, function ($query) use ($request) {
                $query->where('parent_id', $request->parent_id);
            })
            ->get();

        return response()->json($data, 200);
    }

}
