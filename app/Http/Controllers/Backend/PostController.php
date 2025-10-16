<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\PostsDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Category;
use App\Models\Post;
use App\Services\ImageService;
use Illuminate\Http\Request;

class PostController extends Controller
{
    protected $model;
    protected $dataTable;
    protected $title;
    protected $indexRoute;
    protected $service;
    protected $imageService;
    protected $formFields;

    public function __construct(Post $post, PostsDataTable $dataTable, ImageService $imageService)
    {
        $this->model = $post;
        $this->dataTable = $dataTable;
        $this->title = trans('backend.post');
        $this->indexRoute = 'posts';
        $this->imageService = $imageService;
        $this->formFields = [
            ['name' => 'title', 'type' => 'text', 'label' => 'Title'],
            ['name' => 'category_id', 'type' => 'select', 'label' => 'Post Category', 'options' => ['' => __('backend.select')] + Category::where('status', 1)->where('type', Category::TYPE_BLOG)->pluck('name', 'id')->toArray()],
            ['name' => 'content', 'type' => 'textarea', 'label' => 'Content'],
            ['name' => 'image', 'type' => 'file', 'label' => 'Image', 'accept' => 'image/*', 'max' => 1],
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'name' => $this->title,
            'title' => $this->title . 's List',
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
    public function store(StorePostRequest $request)
    {
        $post = Post::create(array_merge($request->all(), [
            'user_id' => auth()->id(),
            'datetime' => now(),
        ]));

        if ($request->hasFile('image')) {
            $this->imageService->uploadImage($request->file('image'), 'blog', $post, 500, 500);
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
    public function show(Post $post)
    {
        if (request()->ajax()) {
            return response()->json($post->load('images'), 200);
        } else {
            return view('backend.common.show', [
                'title' => $this->title,
                'data' => $post,
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        if (request()->ajax()) {
            return response()->json($post->load('images'), 200);
        } else {
            return view('backend.common.edit', [
                'title' => $this->title,
                'data' => $post,
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        $post->update($request->all());

        if ($request->hasFile('image')) {
            if ($post->images()->where('type', 'blog')->exists()) {
                $this->imageService->deleteImage($post->images()->where('type', 'blog')->first());
            }

            $this->imageService->uploadImage($request->file('image'), 'blog', $post, 500, 500);
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
    public function destroy(Post $post)
    {
        $post->delete();

        if ($image = $post->images()->first()) {
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

    public function blog(Request $request)
    {
        $posts = Post::where('status', 1)->get();

        $formattedPosts = [];
        foreach ($posts as $post) {
            $formattedPosts[] = [
                'id' => $post->id,
                'title' => $post->title,
                'image' => $post->images->map(fn($img) => [
                    'type' => $img->type,
                    'filename' => $img->filename,
                ])->toArray(),
                'datetime' => $post->datetime,
                'category' => $post->category?->name,
                'slug' => $post->slug,
                'tag' => $post->slug,
                'author' => $post->user?->name,
                'description' => $post->content,
            ];
        }

        $data = [
            'title' => $this->title,
            'posts' => $formattedPosts,
        ];

        return view('blog.index', $data);
    }


}
