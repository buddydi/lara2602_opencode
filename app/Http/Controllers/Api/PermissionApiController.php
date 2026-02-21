<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionApiController extends BaseApiController
{
    public function index(Request $request)
    {
        $permissions = Permission::when($request->search, function ($query) use ($request) {
                $query->where('name', 'like', "%{$request->search}%");
            })
            ->paginate($request->per_page ?? 15);

        return $this->success([
            'data' => $permissions->items(),
            'meta' => [
                'current_page' => $permissions->currentPage(),
                'per_page' => $permissions->perPage(),
                'total' => $permissions->total(),
                'last_page' => $permissions->lastPage(),
            ],
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|unique:permissions,name',
            'group' => 'nullable|string',
        ]);

        $permission = Permission::create($validated);

        return $this->success($permission, '权限创建成功', 201);
    }

    public function show(Permission $permission)
    {
        return $this->success($permission->load('roles'));
    }

    public function destroy(Permission $permission)
    {
        $permission->delete();
        return $this->success(null, '权限删除成功');
    }

    public function groups()
    {
        $permissions = Permission::all()->groupBy(function ($permission) {
            $parts = explode(' ', $permission->name);
            return $parts[0] ?? 'other';
        });

        return $this->success($permissions);
    }
}
