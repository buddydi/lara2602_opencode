<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserApiController extends BaseApiController
{
    public function index(Request $request)
    {
        $users = User::with('roles', 'permissions')
            ->when($request->search, function ($query) use ($request) {
                $query->where('name', 'like', "%{$request->search}%")
                    ->orWhere('email', 'like', "%{$request->search}%");
            })
            ->when($request->role, function ($query) use ($request) {
                $query->role($request->role);
            })
            ->paginate($request->per_page ?? 15);

        return $this->success([
            'data' => $users->items(),
            'meta' => [
                'current_page' => $users->currentPage(),
                'per_page' => $users->perPage(),
                'total' => $users->total(),
                'last_page' => $users->lastPage(),
            ],
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,name',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);

        if (!empty($validated['roles'])) {
            $user->assignRole($validated['roles']);
        }

        return $this->success($user->load('roles'), '用户创建成功', 201);
    }

    public function show(User $user)
    {
        return $this->success($user->load('roles', 'permissions'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'sometimes|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $user->id,
            'password' => 'sometimes|min:8',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,name',
        ]);

        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        $user->update($validated);

        if (isset($validated['roles'])) {
            $user->syncRoles($validated['roles']);
        }

        return $this->success($user->load('roles'), '用户更新成功');
    }

    public function destroy(User $user)
    {
        if ($user->id === 1) {
            return $this->error('不能删除超级管理员', 422);
        }

        $user->delete();

        return $this->success(null, '用户删除成功');
    }

    public function assignRole(Request $request, User $user)
    {
        $validated = $request->validate([
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,name',
        ]);

        $user->assignRole($validated['roles']);

        return $this->success($user->load('roles'), '角色分配成功');
    }

    public function revokeRole(Request $request, User $user)
    {
        $validated = $request->validate([
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,name',
        ]);

        $user->removeRole($validated['roles']);

        return $this->success($user->load('roles'), '角色撤销成功');
    }

    public function givePermission(Request $request, User $user)
    {
        $validated = $request->validate([
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $user->givePermissionTo($validated['permissions']);

        return $this->success($user->load('permissions'), '权限授予成功');
    }

    public function revokePermission(Request $request, User $user)
    {
        $validated = $request->validate([
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $user->revokePermissionTo($validated['permissions']);

        return $this->success($user->load('permissions'), '权限撤销成功');
    }
}
