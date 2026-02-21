@extends('admin_layout')

@section('title', '编辑分类')

@section('content')
    <h1>编辑分类</h1>
    
    <form action="{{ route('categories.update', $category) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label for="name">分类名称</label>
            <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}">
        </div>
        
        <div class="form-group">
            <label for="parent_id">父分类</label>
            <select name="parent_id" id="parent_id">
                <option value="">-- 无（顶级分类） --</option>
                @foreach($categories as $cat)
                    @if($cat->id !== $category->id)
                        <option value="{{ $cat->id }}" {{ $category->parent_id == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endif
                @endforeach
            </select>
        </div>
        
        <div class="form-group">
            <label for="order">排序</label>
            <input type="number" name="order" id="order" value="{{ old('order', $category->order) }}">
        </div>
        
        <div class="form-group">
            <label for="description">描述</label>
            <textarea name="description" id="description">{{ old('description', $category->description) }}</textarea>
        </div>
        
        <button type="submit" class="btn btn-primary">更新</button>
        <a href="{{ route('categories.index') }}" class="btn btn-secondary">返回</a>
    </form>
@endsection
