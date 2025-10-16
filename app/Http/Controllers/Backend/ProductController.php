<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\ProductsDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Services\ImageService;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $model;
    protected $dataTable;
    protected $title;
    protected $indexRoute;
    protected $service;
    protected $imageService;
    protected $formFields;

    public function __construct(Product $product, ProductsDataTable $dataTable, ProductService $service, ImageService $imageService)
    {
        $this->model = $product;
        $this->dataTable = $dataTable;
        $this->title = trans('backend.product');
        $this->indexRoute = 'products';
        $this->service = $service;
        $this->imageService = $imageService;
        $this->formFields = [
            ['name' => 'name', 'type' => 'text', 'label' => 'Name'],
            ['name' => 'product_category', 'type' => 'select', 'label' => 'Product Category', 'options' => ['' => __('backend.select')] + Category::where('status', 1)->where('type', Category::TYPE_PRODUCT)->pluck('name', 'id')->toArray()],
            ['name' => 'image', 'type' => 'file', 'label' => 'Image', 'accept' => 'image/*', 'max' => 1],
            // ['name' => 'due_date', 'type' => 'date', 'label' => 'due_date'],
            // ['name' => 'amount', 'type' => 'number', 'label' => 'amount', 'min' => 0, 'step' => 0.01],
            ['name' => 'description', 'type' => 'textarea', 'label' => 'Description'],
            ['name' => 'details', 'type' => 'textarea', 'label' => 'Details'],
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
        $data = [
            'formFields' => $this->formFields,
            'title' => $this->title . ' Create',
            'route' => $this->indexRoute,
        ];

        return view('backend.common.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        $product = $this->service->store($request->all());

        if ($request->hasFile('image')) {
            $this->imageService->uploadImage($request->file('image'), 'product', $product, 500, 500);
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
    public function show(Product $product)
    {
        if (request()->ajax()) {
            return response()->json($product->load('images', 'categories'), 200);
        } else {
            return view('backend.common.show', [
                'title' => $this->title,
                'data' => $product,
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $product->load('categories');

        if (request()->ajax()) {
            return response()->json($product->load('images', 'categories'), 200);
        } else {
            return view('backend.common.edit', [
                'title' => $this->title,
                'data' => $product,
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        $this->service->update($product, $request->all());

        if ($request->hasFile('image')) {
            if ($product->images()->where('type', 'product')->exists()) {
                $this->imageService->deleteImage($product->images()->where('type', 'product')->first());
            }

            $this->imageService->uploadImage($request->file('image'), 'product', $product, 500, 500);
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
    public function destroy(Product $product)
    {
        if (!$product) {
            if (request()->ajax()) {
                return response()->json([
                    'type' => 'error',
                    'message' => $this->title . ' not found.',
                ], 200);
            }
            return redirect()->route($this->indexRoute . '.index')->with('error', $this->title . ' not found.');
        }

        $product->delete();

        if ($image = $product->images()->first()) {
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

    public function getProduct(Request $request)
    {
        $data = Product::where('status', 1)
            ->when($request->category_id, function ($query) use ($request) {
                $query->whereHas('categories', function ($q) use ($request) {
                    $q->where('id', $request->category_id);
                });
            })
            ->where('slug', $request->slug)
            ->get();

        return response()->json($data, 200);
    }
}
