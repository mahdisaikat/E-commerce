<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\NewslettersDataTable;
use App\Models\Newsletter;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreNewsletterRequest;
use App\Http\Requests\UpdateNewsletterRequest;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    protected $model;
    protected $dataTable;
    protected $title;
    protected $indexRoute;
    protected $formFields;

    public function __construct(Newsletter $model, NewslettersDataTable $dataTable)
    {
        $this->model = $model;
        $this->dataTable = $dataTable;
        $this->title = trans('backend.newsletter');
        $this->indexRoute = 'newsletters';
        $this->formFields = [
            ['name' => 'name', 'type' => 'text', 'label' => @trans('backend.name')],
            ['name' => 'email', 'type' => 'email', 'label' => @trans('backend.email')],
            ['name' => 'token', 'type' => 'text', 'label' => @trans('backend.token')],
            ['name' => 'source', 'type' => 'text', 'label' => @trans('backend.source')],
            // ['name' => 'confirmed_at', 'type' => 'text', 'label' => @trans('backend.confirmed_at')],
            // ['name' => 'unsubscribed_at', 'type' => 'textarea', 'label' => @trans('backend.unsubscribed_at')],
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
    public function store(StoreNewsletterRequest $request)
    {
        $data = $request->validated();
        $data['ip_address'] = request()->ip();
        $this->model->create($data);

        if (request()->ajax()) {
            return response()->json([
                'type' => 'success',
                'message' => $this->title . ' sent successfully',
            ], 201);
        }
        return redirect()->back()->with('success', $this->title . ' sent successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Newsletter $newsletter)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Newsletter $newsletter)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateNewsletterRequest $request, Newsletter $newsletter)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Newsletter $newsletter)
    {
        if (!$newsletter) {
            if (request()->ajax()) {
                return response()->json([
                    'type' => 'error',
                    'message' => $this->title . ' not found.',
                ], 200);
            }
            return redirect()->route($this->indexRoute . '.index')->with('error', $this->title . ' not found.');
        }

        $newsletter->delete();

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
