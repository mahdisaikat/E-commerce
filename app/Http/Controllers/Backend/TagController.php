<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\TagsDataTable;
use App\Enums\TagType;
use App\Http\Controllers\Controller;
use App\Models\Tag;
use App\Http\Requests\StoreTagRequest;
use App\Http\Requests\UpdateTagRequest;
use Illuminate\Http\Request;

class TagController extends Controller
{
    protected $model;
    protected $dataTable;
    protected $title;
    protected $indexRoute;
    protected $service;
    protected $formFields;

    public function __construct(Tag $model, TagsDataTable $dataTable)
    {
        $this->model = $model;
        $this->dataTable = $dataTable;
        $this->title = trans('backend.tag');
        $this->indexRoute = 'tags';

        // Get the current route 'type' param or default to null
        $routeType = request()->route('type') ?? null;

        // Map route param to enum value if possible
        $typeValue = null;
        if ($routeType) {
            foreach (TagType::cases() as $case) {
                if (strtolower($case->name) === strtolower($routeType)) {
                    $typeValue = $case->value;
                    break;
                }
            }
        }

        $this->formFields = [
            [
                'name' => 'name',
                'type' => 'text',
                'label' => __('backend.name'),
            ],
        ];
        if ($typeValue === null) {
            $this->formFields[] = [
                'name' => 'type',
                'type' => 'select',
                'label' => __('backend.tag_type'),
                'options' => [0 => __('backend.select')] + collect(TagType::cases())->mapWithKeys(fn($case) => [$case->value => $case->label()])->toArray(),
            ];
        } else {
            $this->formFields[] = [
                'name' => 'type',
                'type' => 'default',
                'value' => $typeValue,
            ];
        }

    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get type from route parameter, normalize case to match enum names
        $typeParam = request()->route('type') ?? null; // e.g. 'color', 'size', etc.

        // Map the param to TagType enum value (case insensitive)
        $tagType = null;
        foreach (TagType::cases() as $case) {
            if (strtolower($case->name) === strtolower($typeParam)) {
                $tagType = $case->value;
                break;
            }
        }

        // Pass $tagType to DataTable for filtering (or null for all)
        return $this->dataTable
            ->with('tag_type', $tagType)
            ->render('backend.common.index_new', [
                'name' => $this->title,
                'title' => $this->title . ' List',
                'route' => $this->indexRoute,
                'formFields' => $this->formFields,
            ]);
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
    public function store(StoreTagRequest $request)
    {
        $data = $request->only(['name', 'type']);

        // If type is not present in the form, try to get from route
        if (!isset($data['type']) || $data['type'] === null) {
            $routeType = request()->route('type');

            $typeEnum = collect(TagType::cases())
                ->first(fn($case) => strtolower($case->name) === strtolower($routeType));

            $data['type'] = $typeEnum?->value ?? TagType::Unknown->value;
        }

        Tag::createOrFirst($data);

        if (request()->ajax()) {
            return response()->json([
                'type' => 'success',
                'message' => $this->title . ' stored successfully',
            ], 201);
        }

        return redirect()
            ->route($this->indexRoute . '.index')
            ->with('success', $this->title . ' stored successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Tag $tag)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tag $tag)
    {
        if (request()->ajax()) {
            return response()->json($tag, 200);
        } else {
            return view('backend.common.edit', [
                'title' => $this->title,
                'data' => $tag,
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTagRequest $request, Tag $tag)
    {
        $tag->update($request->all());

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
    public function destroy(Tag $tag)
    {
        if (!$tag) {
            if (request()->ajax()) {
                return response()->json([
                    'type' => 'error',
                    'message' => $this->title . ' not found.',
                ], 200);
            }
            return redirect()->route($this->indexRoute . '.index')->with('error', $this->title . ' not found.');
        }

        $tag->delete();

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
}
