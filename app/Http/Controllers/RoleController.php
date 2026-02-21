<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')->paginate(20);
        return view('roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::all()->groupBy(function($permission) {
            $parts = explode(' ', $permission->name);
            return $parts[0] ?? 'other';
        });
        return view('roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|unique:roles,name',
        ]);

        $role = Role::create($validated);

        $permissions = $request->input('permissions', []);
        $allPermissions = [];
        foreach ($permissions as $groupPermissions) {
            if (is_array($groupPermissions)) {
                $allPermissions = array_merge($allPermissions, $groupPermissions);
            }
        }
        $role->givePermissionTo($allPermissions);

        return redirect()->route('roles.index')->with('success', '角色创建成功');
    }

    public function show(Role $role)
    {
        $permissions = $role->permissions;
        return view('roles.show', compact('role', 'permissions'));
    }

    public function edit(Role $role)
    {
        $permissions = Permission::all()->groupBy(function($permission) {
            $parts = explode(' ', $permission->name);
            return $parts[0] ?? 'other';
        });
        return view('roles.edit', compact('role', 'permissions'));
    }

    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => 'required|unique:roles,name,' . $role->id,
        ]);

        $role->update($validated);

        $permissions = $request->input('permissions', []);
        $allPermissions = [];
        foreach ($permissions as $groupPermissions) {
            if (is_array($groupPermissions)) {
                $allPermissions = array_merge($allPermissions, $groupPermissions);
            }
        }
        $role->syncPermissions($allPermissions);

        return redirect()->route('roles.index')->with('success', '角色更新成功');
    }

    public function destroy(Role $role)
    {
        if ($role->name === 'admin') {
            return redirect()->route('roles.index')->with('error', '不能删除 admin 角色');
        }
        $role->delete();
        return redirect()->route('roles.index')->with('success', '角色删除成功');
    }
}
