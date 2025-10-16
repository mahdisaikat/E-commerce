<?php

namespace App\Http\Controllers\Backend;

use App\Models\Product;
use App\Models\ProductTag;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductTagRequest;
use App\Http\Requests\UpdateProductTagRequest;
use App\Services\ImageService;

class ProductTagController extends Controller
{
    protected $model;
    protected $dataTable;
    protected $title;
    protected $indexRoute;
    protected $imageService;
    protected $formFields;

    public function __construct(ProductTag $product, ProductTagsDataTable $dataTable, ImageService $imageService)
    {
        $this->model = $product;
        $this->dataTable = $dataTable;
        $this->title = trans('backend.product');
        $this->indexRoute = 'products';
        $this->imageService = $imageService;
        $this->formFields = [
            ['name' => 'name', 'type' => 'text', 'label' => 'Name'],
            ['name' => 'product_category', 'type' => 'select', 'label' => 'Product Category', 'options' => ['' => __('backend.select')] + Product::where('status', 1)->pluck('name', 'id')->toArray()],
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
        //
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
    public function store(StoreProductTagRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductTag $productTag)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProductTag $productTag)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductTagRequest $request, ProductTag $productTag)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductTag $productTag)
    {
        //
    }
}
