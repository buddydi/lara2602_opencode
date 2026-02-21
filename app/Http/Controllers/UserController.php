<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->paginate(20);
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('users.index')->with('success', '用户创建成功');
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        $permissions = Permission::all()->groupBy(function($permission) {
            $parts = explode(' ', $permission->name);
            return $parts[0] ?? 'other';
        });
        return view('users.edit', compact('user', 'roles', 'permissions'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        $user->update($validated);

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        $user->syncRoles($request->input('roles', []));

        $permissions = $request->input('permissions', []);
        $allPermissions = [];
        foreach ($permissions as $groupPermissions) {
            if (is_array($groupPermissions)) {
                $allPermissions = array_merge($allPermissions, $groupPermissions);
            }
        }
        $user->syncPermissions($allPermissions);

        return redirect()->route('users.index')->with('success', '用户更新成功');
    }

    public function destroy(User $user)
    {
        if ($user->id === 1) {
            return redirect()->route('users.index')->with('error', '不能删除超级管理员');
        }
        $user->delete();
        return redirect()->route('users.index')->with('success', '用户删除成功');
    }
}
