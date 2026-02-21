<?php use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth; ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', '管理后台')</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; }
        .container { display: flex; min-height: 100vh; }
        .sidebar { width: 160px; background: #2c3e50; padding: 20px 0; flex-shrink: 0; transition: width 0.3s; overflow: visible; position: relative; }
        .sidebar.collapsed { width: 60px; }
        .sidebar h2 { color: #fff; text-align: center; margin-bottom: 30px; padding: 0 10px; white-space: nowrap; font-size: 16px; }
        .sidebar.collapsed h2 { display: none; }
        
        /* 用户信息区域 */
        .user-info { 
            padding: 15px; 
            border-bottom: 1px solid #3d5267; 
            margin-bottom: 15px;
            text-align: center;
        }
        .sidebar.collapsed .user-info { display: none; }
        .user-name { color: #fff; font-weight: bold; margin-bottom: 5px; }
        .user-role { color: #3498db; font-size: 12px; }
        .logout-btn { 
            display: block; 
            margin-top: 10px; 
            padding: 6px 12px; 
            background: #e74c3c; 
            color: #fff; 
            border-radius: 4px; 
            text-decoration: none; 
            font-size: 12px;
        }
        .logout-btn:hover { background: #c0392b; }
        
        .sidebar a { display: flex; align-items: center; color: #ecf0f1; padding: 12px 15px; text-decoration: none; border-left: 3px solid transparent; white-space: nowrap; transition: all 0.3s; position: relative; }
        .sidebar a:hover, .sidebar a.active { background: #34495e; border-left-color: #3498db; }
        .sidebar .menu-icon { 
            display: inline-flex; 
            align-items: center; 
            justify-content: center; 
            width: 32px; 
            height: 32px; 
            background: #3498db; 
            border-radius: 6px; 
            font-size: 14px; 
            font-weight: bold; 
            margin-right: 10px; 
            flex-shrink: 0;
        }
        .sidebar .menu-text { display: inline; }
        .sidebar.collapsed .menu-text { display: none; }
        .sidebar.collapsed a { justify-content: center; padding: 12px 10px; }
        .sidebar.collapsed .menu-icon { margin-right: 0; width: 36px; height: 36px; font-size: 16px; }
        
        /* Hover tooltip - only show on hover when collapsed */
        .sidebar .tooltip { display: none; }
        .sidebar.collapsed a .tooltip { 
            display: none; 
            position: absolute; 
            left: 100%; 
            top: 50%; 
            transform: translateY(-50%); 
            background: #2c3e50; 
            color: #fff; 
            padding: 8px 12px; 
            border-radius: 4px; 
            white-space: nowrap; 
            z-index: 1000;
            margin-left: 5px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        }
        .sidebar.collapsed a:hover .tooltip { display: block; }
        
        .toggle-btn { position: fixed; left: 15px; bottom: 15px; z-index: 1000; background: #2c3e50; color: #fff; border: none; padding: 8px 12px; border-radius: 4px; cursor: pointer; }
        .toggle-btn:hover { background: #34495e; }
        .main { flex: 1; padding: 20px 30px; overflow-x: auto; }
        h1 { margin-top: 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #f4f4f4; }
        .btn { padding: 8px 16px; text-decoration: none; border-radius: 4px; display: inline-block; margin-right: 5px; margin-bottom: 10px; }
        .btn-primary { background-color: #007bff; color: white; }
        .btn-secondary { background-color: #6c757d; color: white; }
        .btn-danger { background-color: #dc3545; color: white; border: none; cursor: pointer; }
        .btn-outline { background-color: #fff; border: 1px solid #007bff; color: #007bff; }
        .alert { padding: 10px; background-color: #d4edda; border: 1px solid #c3e6cb; margin-bottom: 20px; }
        .error { padding: 10px; background-color: #f8d7da; border: 1px solid #f5c6cb; margin-bottom: 20px; }
        .badge { display: inline-block; padding: 3px 8px; background: #e9ecef; border-radius: 3px; margin: 2px; font-size: 12px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="text"], input[type="email"], input[type="password"], textarea, select { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        textarea { height: 100px; }
        .checkbox-grid { display: grid; grid-template-columns: repeat(5, 1fr); gap: 10px; }
        .checkbox-item { margin: 3px 0; }
        .checkbox-item label { font-weight: normal; cursor: pointer; }
        .permission-group { margin-bottom: 20px; border: 1px solid #ddd; border-radius: 4px; padding: 15px; }
        .permission-group h3, .permission-group h4 { margin-top: 0; margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid #eee; }
        .section-title { font-size: 18px; border-bottom: 2px solid #007bff; padding-bottom: 10px; margin-bottom: 15px; }
    </style>
    @yield('styles')
</head>
<body>
    <button class="toggle-btn" onclick="toggleSidebar()">☰</button>
    <?php
        $currentRoute = Route::currentRouteName();
    ?>
    <div class="container">
        <div class="sidebar" id="sidebar">
            <?php $user = Auth::user(); ?>
            @if($user)
            <div class="user-info">
                <div class="user-name">{{ $user->name }}</div>
                <div class="user-role">{{ $user->roles->pluck('name')->implode(', ') ?: '无角色' }}</div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="logout-btn" style="width: 100%; border: none; cursor: pointer;">退出登录</button>
                </form>
            </div>
            @endif
            <h2>管理后台</h2>
            <a href="{{ route('dashboard') }}" {{ str_starts_with($currentRoute, 'dashboard') ? 'class="active"' : '' }}>
                <span class="menu-icon">仪</span>
                <span class="menu-text">仪表盘</span>
                <span class="tooltip">仪表盘</span>
            </a>
            <a href="{{ route('posts.index') }}" {{ str_starts_with($currentRoute, 'posts') ? 'class=active' : '' }}>
                <span class="menu-icon">文</span>
                <span class="menu-text">文章管理</span>
                <span class="tooltip">文章管理</span>
            </a>
            <a href="{{ route('categories.index') }}" {{ str_starts_with($currentRoute, 'categories') ? 'class=active' : '' }}>
                <span class="menu-icon">分</span>
                <span class="menu-text">分类管理</span>
                <span class="tooltip">分类管理</span>
            </a>
            <a href="{{ route('users.index') }}" {{ str_starts_with($currentRoute, 'users') ? 'class=active' : '' }}>
                <span class="menu-icon">用</span>
                <span class="menu-text">用户管理</span>
                <span class="tooltip">用户管理</span>
            </a>
            <a href="{{ route('roles.index') }}" {{ str_starts_with($currentRoute, 'roles') ? 'class=active' : '' }}>
                <span class="menu-icon">角</span>
                <span class="menu-text">角色管理</span>
                <span class="tooltip">角色管理</span>
            </a>
            <a href="{{ route('permissions.index') }}" {{ str_starts_with($currentRoute, 'permissions') ? 'class=active' : '' }}>
                <span class="menu-icon">权</span>
                <span class="menu-text">权限管理</span>
                <span class="tooltip">权限管理</span>
            </a>
        </div>
        <div class="main" id="main">
            @if (session('success'))
                <div class="alert">{{ session('success') }}</div>
            @endif
            
            @if (session('error'))
                <div class="error">{{ session('error') }}</div>
            @endif
            
            @yield('content')
        </div>
    </div>
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('collapsed');
            localStorage.setItem('sidebar_collapsed', sidebar.classList.contains('collapsed'));
        }
        
        // 页面加载时恢复状态
        document.addEventListener('DOMContentLoaded', function() {
            const isCollapsed = localStorage.getItem('sidebar_collapsed') === 'true';
            if (isCollapsed) {
                document.getElementById('sidebar').classList.add('collapsed');
            }
        });
    </script>
</body>
</html>
