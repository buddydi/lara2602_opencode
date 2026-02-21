@extends('admin_layout')

@section('title', '新建分类')

@section('content')
    <h1>新建分类</h1>
    
    <form action="{{ route('categories.store') }}" method="POST">
        @csrf
        
        <div class="form-group">
            <label for="name">分类名称</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}">
        </div>
        
        <div class="form-group">
            <label for="parent_id">父分类</label>
            <select name="parent_id" id="parent_id">
                <option value="">-- 无（顶级分类） --</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>
        
        <div class="form-group">
            <label for="order">排序</label>
            <input type="number" name="order" id="order" value="{{ old('order', 0) }}">
        </div>
        
        <div class="form-group">
            <label for="description">描述</label>
            <textarea name="description" id="description">{{ old('description') }}</textarea>
        </div>
        
        <button type="submit" class="btn btn-primary">创建</button>
        <a href="{{ route('categories.index') }}" class="btn btn-secondary">返回</a>
    </form>
@endsection
