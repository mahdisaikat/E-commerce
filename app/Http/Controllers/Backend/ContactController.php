<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\ContactsDataTable;
use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Http\Requests\StoreContactRequest;
use App\Http\Requests\UpdateContactRequest;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    protected $model;
    protected $dataTable;
    protected $title;
    protected $indexRoute;
    protected $formFields;

    public function __construct(Contact $model, ContactsDataTable $dataTable)
    {
        $this->model = $model;
        $this->dataTable = $dataTable;
        $this->title = trans('backend.contact');
        $this->indexRoute = 'contacts';
        $this->formFields = [
            ['name' => 'name', 'type' => 'text', 'label' => 'Name'],
            ['name' => 'email', 'type' => 'email', 'label' => 'Email'],
            ['name' => 'phone', 'type' => 'text', 'label' => 'Phone'],
            ['name' => 'subject', 'type' => 'text', 'label' => 'Subject'],
            ['name' => 'message', 'type' => 'textarea', 'label' => 'Message'],
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
    public function store(StoreContactRequest $request)
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
    public function show(Contact $contact)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Contact $contact)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateContactRequest $request, Contact $contact)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contact $contact)
    {
        if (!$contact) {
            if (request()->ajax()) {
                return response()->json([
                    'type' => 'error',
                    'message' => $this->title . ' not found.',
                ], 200);
            }
            return redirect()->route($this->indexRoute . '.index')->with('error', $this->title . ' not found.');
        }

        $contact->delete();

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
