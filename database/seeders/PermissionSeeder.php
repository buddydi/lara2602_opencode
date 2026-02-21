<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            // 文章权限
            'post list',
            'post create',
            'post edit',
            'post delete',
            // 分类权限
            'category list',
            'category create',
            'category edit',
            'category delete',
            // 评论权限
            'comment list',
            'comment delete',
            // 用户权限
            'user list',
            'user create',
            'user edit',
            'user delete',
            // 角色权限
            'role list',
            'role create',
            'role edit',
            'role delete',
            // 权限管理
            'permission list',
            'permission manage',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $adminRole->givePermissionTo(Permission::all());

        $editorRole = Role::firstOrCreate(['name' => 'editor', 'guard_name' => 'web']);
        $editorRole->givePermissionTo([
            'post list', 'post create', 'post edit',
            'category list', 'category create', 'category edit',
            'comment list', 'comment delete',
        ]);

        $userRole = Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);
        $userRole->givePermissionTo([
            'post list', 'comment list',
        ]);
    }
}
