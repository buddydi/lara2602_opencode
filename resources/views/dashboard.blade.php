@extends('admin_layout')

@section('title', '仪表盘')

@section('content')
    <h1>仪表盘</h1>
    
    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-top: 20px;">
        <div style="background: #fff; padding: 20px; border-radius: 8px; border: 1px solid #ddd;">
            <h3 style="margin: 0 0 10px 0; color: #666;">文章总数</h3>
            <p style="font-size: 32px; margin: 0; color: #3498db;">{{ \App\Models\Post::count() }}</p>
        </div>
        <div style="background: #fff; padding: 20px; border-radius: 8px; border: 1px solid #ddd;">
            <h3 style="margin: 0 0 10px 0; color: #666;">分类总数</h3>
            <p style="font-size: 32px; margin: 0; color: #2ecc71;">{{ \App\Models\Category::count() }}</p>
        </div>
        <div style="background: #fff; padding: 20px; border-radius: 8px; border: 1px solid #ddd;">
            <h3 style="margin: 0 0 10px 0; color: #666;">用户总数</h3>
            <p style="font-size: 32px; margin: 0; color: #9b59b6;">{{ \App\Models\User::count() }}</p>
        </div>
        <div style="background: #fff; padding: 20px; border-radius: 8px; border: 1px solid #ddd;">
            <h3 style="margin: 0 0 10px 0; color: #666;">角色总数</h3>
            <p style="font-size: 32px; margin: 0; color: #e74c3c;">{{ \Spatie\Permission\Models\Role::count() }}</p>
        </div>
    </div>
@endsection
