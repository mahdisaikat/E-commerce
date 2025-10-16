<?php

namespace App\Http\Controllers;

use App\DataTables\ConfigurationsDataTable;
use App\Http\Requests\StoreConfigurationRequest;
use App\Http\Requests\UpdateConfigurationRequest;
use App\Models\Configuration;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class ConfigurationController extends Controller {
    protected $model;
    protected $dataTable;
    protected $title;
    protected $indexRoute;
    protected $formFields;
    protected $imageService;

    private const SYSTEM_KEYS = [
        'app_name',
        'page_title_show',
        'dark_mode',
        'collapse_sidebar',
        'app_logo_link',
        'app_favicon_link',
        'footer_copyright_title',
        'footer_copyright_url',
    ];

    private const AUTH_KEYS = [
        //
    ];

    private const CONFIG_KEYS = [
        'sidebar_color',
        'sidebar_hover',
        'btn_success',
        'bg_success',
        'btn_primary',
        'btn_info',
        'btn_danger',
        'btn_warning',
        'progress_bar',
        'bg_warning',
    ];

    public function __construct(Configuration $model, ConfigurationsDataTable $dataTable, ImageService $imageService)
    {
        $this->model = $model;
        $this->dataTable = $dataTable;
        $this->title = trans('backend.configuration');
        $this->indexRoute = 'configurations';
        $this->formFields = [
            ['name' => 'type', 'type' => 'text', 'label' => trans('backend.type')],
            ['name' => 'key', 'type' => 'text', 'label' => trans('backend.key')],
            ['name' => 'value', 'type' => 'textarea', 'label' => trans('backend.value')],
            ['name' => 'remarks', 'type' => 'textarea', 'label' => trans('backend.remarks')],
        ];
        $this->imageService = $imageService;
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

        return $this->dataTable->render('backend.common.configuration', $data);
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
    public function store(StoreConfigurationRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('value_file'))
        {
            $data['value'] = 'storage/images/' . $this->imageService->upload($request->file('value_file'), 'configuration', 300, 200);
        }

        $this->model->create($data);

        // optimize:clear command to clear the cache
        Artisan::call('optimize:clear');

        return $this->successResponse('stored');
    }

    /**
     * Display the specified resource.
     */
    public function show(Configuration $configuration)
    {
        return $this->responseResource($configuration, 'backend.common.show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Configuration $configuration)
    {
        return $this->responseResource($configuration, 'backend.common.edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateConfigurationRequest $request, Configuration $configuration)
    {
        $data = $request->validated();
        $configuration->update($data);

        if (request()->ajax())
        {
            return response()->json([
                'type' => 'success',
                'message' => $this->title . ' updated successfully',
            ], 200);
        }
        // optimize:clear command to clear the cache
        Artisan::call('optimize:clear');

        return redirect()->route($this->indexRoute . '.index')->with('success', $this->title . ' updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Configuration $configuration)
    {
        $configuration->delete();

        Artisan::call('optimize:clear');

        return $this->successResponse('deleted', 'info');
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
        // optimize:clear command to clear the cache
        Artisan::call('optimize:clear');

        $type = $request->status == 1 ? 'success' : 'info';
        $message = $request->status == 1 ? $this->title . ' status activated successfully' : $this->title . ' status inactivated successfully';

        return $this->successResponse($message, $type, false);
    }

    /**
     * Show system settings.
     */
    public function system_settings()
    {
        return $this->settingsView('System Settings', self::SYSTEM_KEYS);
    }

    /**
     * Show authentication settings.
     */
    public function auth_settings()
    {
        return $this->settingsView('System Configuration', self::AUTH_KEYS);
    }

    /**
     * Update system settings.
     */
    public function system_settings_update(Request $request)
    {
        $settings = $request->input('settings', []);

        if ($request->hasFile('file'))
        {
            foreach ($request->file('file') as $key => $file)
            {
                $settings[$key] = 'storage/' . $this->imageService->upload($file, 'configuration', 300, 200);
            }
        }

        foreach ($settings as $key => $value)
        {
            Configuration::updateOrCreate(['key' => $key], ['value' => $value]);
        }
        // optimize:clear command to clear the cache
        Artisan::call('optimize:clear');

        return redirect()->back()->with('success', 'Settings saved successfully.');
    }

    /**
     * Show configuration settings.
     */
    public function config_settings()
    {
        return $this->settingsView('Configuration Settings', self::CONFIG_KEYS);
    }

    /**
     * Upload a setting file and return its public URL.
     */
    private function uploadSettingFile($file, $path = 'configuration'): string
    {
        if (!$file->isValid())
        {
            throw new \Exception('Invalid file uploaded');
        }

        $safeName = str_replace(' ', '_', pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
        $safeName = substr($safeName, 0, 20) . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

        $storagePath = 'backend/' . trim($path, '/');
        $filePath = $file->storeAs($storagePath, $safeName, 'public');

        return Storage::disk('public')->url($filePath);
    }

    /**
     * Helper to return settings view.
     */
    private function settingsView(string $title, array $keys)
    {
        $configurations = Configuration::whereIn('key', $keys)->get(['type', 'key', 'value']);
        $data = $configurations->toArray();
        return view('backend.common.settings', compact('title', 'data'));
    }

    /**
     * Helper to return resource as JSON or view.
     */
    private function responseResource($resource, $view)
    {
        if (request()->ajax())
        {
            return response()->json($resource, 200);
        }
        return view($view, [
            'title' => $this->title,
            'data' => $resource,
        ]);
    }

    /**
     * Helper to return success response.
     */
    private function successResponse($action, $type = 'success', $withTitle = true)
    {
        $message = $withTitle ? $this->title . ' ' . $action . ' successfully' : $this->title . ' ' . $action;
        if (request()->ajax())
        {
            return response()->json([
                'type' => $type,
                'message' => $message,
            ], $type === 'success' ? 200 : 201);
        }
        return redirect()->route($this->indexRoute . '.index')->with($type, $message);
    }

    /**
     * Helper to return error response.
     */
    private function errorResponse($message)
    {
        if (request()->ajax())
        {
            return response()->json([
                'type' => 'error',
                'message' => $this->title . ' ' . $message,
            ], 404);
        }
        return redirect()->route($this->indexRoute . '.index')->with('error', $this->title . ' ' . $message);
    }
}
