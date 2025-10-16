<?php

namespace App\Http\Controllers;

use App\DataTables\RoleDataTable;
use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use App\Services\AuditService;
use Illuminate\Http\Request;

class RoleController extends Controller {
    protected $index;
    protected $indexRoute;
    private $auditService;

    function __construct()
    {
        $this->index = 'Role';
        $this->indexRoute = 'roles';
        // $this->auditService = $auditService;
    }

    public function index(RoleDataTable $dataTable)
    {
        $data = [
            'name' => $this->index,
            'title' => $this->index . trans('backend.list'),
            'route' => $this->indexRoute,
            'formFields' => [],
        ];
        return $dataTable->render('backend.common.index', $data);
    }

    public function create()
    {
        $permissions = [];
        $permissionList = auth()->user()->hasRole('systemadmin')
            ? Permission::orderBy('module_name', 'asc')->get()
            : auth()->user()->getAllPermissions()->sortBy('module_name');

        foreach ($permissionList as $key => $value)
        {
            $permissions[$value['module_name']][$value['id']] = $value['display_name'];
        }

        $data = [
            'title' => 'Create ' . $this->index,
            'permissions' => $permissions,
            'route' => $this->indexRoute,
        ];
        return view('backend.role.create', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'display_name' => 'required',
        ]);

        $role = Role::create([
            'name' => strtolower(str_replace(' ', '_', $request->input('display_name'))) . '_' . uniqid(),
            'display_name' => $request->input('display_name'),
        ]);

        $permissionIds = $request->input('permission', []);
        $permissions = Permission::whereIn('id', $permissionIds)->get();

        foreach ($permissions as $permission)
        {
            // $this->auditService->audit($permission->id, 'Role Permission Update', [], $permission->toArray(), Role::class);
        }

        $role->syncPermissions($permissions);

        return redirect()->route($this->indexRoute . '.index')->with('success', trans('backend.role_create_message'));
    }

    public function show(Role $role)
    {
        return $role;
    }

    public function edit(Role $role)
    {
        $permissions = [];
        $permissionList = auth()->user()->hasRole('systemadmin')
            ? Permission::orderBy('module_name', 'asc')->get()
            : auth()->user()->getAllPermissions()->sortBy('module_name');

        $role_permissions = $role->getAllPermissions()->pluck('id', 'id')->toArray();

        foreach ($permissionList as $key => $value)
        {
            $permissions[$value['module_name']][$value['id']] = $value['display_name'];
        }
        $data = [
            'title' => 'Edit ' . $this->index,
            'role' => $role,
            'permissions' => $permissions,
            'role_permissions' => $role_permissions,
            'route' => $this->indexRoute,
        ];
        return view('backend.role.edit', $data);
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'display_name' => 'required',
        ]);

        $role->display_name = $request->input('display_name');
        $permissionIds = $request->input('permission', []);

        $permissions = Permission::whereIn('id', $permissionIds)->get();

        foreach ($permissions as $permission)
        {
            // $this->auditService->audit($permission->id, 'Role Permission Update', [], $permission->toArray(), Role::class);
        }

        $role->syncPermissions($permissions);

        $type = $role->save() ? 'success' : 'warning';
        $msg = $role->save() ? $request->display_name . ' ' . trans('backend.role_update_message') : trans('backend.role_not_update_message');
        return redirect()->route($this->indexRoute . '.index')->with($type, $msg);
    }

    public function destroy(Role $role)
    {
        $role->delete();
        if (request()->ajax())
        {
            return response()->json([
                'type' => 'success',
                'message' => $this->index . ' ' . trans('backend.data_delete_message'),
            ]);
        }
        return redirect()->route($this->indexRoute . '.index')->with('success', $this->index . ' ' . trans('backend.data_delete_message'));
    }

}
