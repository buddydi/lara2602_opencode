<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\ProductAttributeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductSkuController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::resource('posts', PostController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('permissions', PermissionController::class);
    Route::resource('users', UserController::class);
    
    // 商品模块
    Route::resource('product-categories', ProductCategoryController::class);
    Route::resource('brands', BrandController::class);
    Route::resource('product-attributes', ProductAttributeController::class);
    Route::resource('products', ProductController::class);
    Route::resource('products.skus', ProductSkuController::class);
    
    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('posts.comments.store');
    Route::delete('/posts/{post}/comments/{comment}', [CommentController::class, 'destroy'])->name('posts.comments.destroy');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
