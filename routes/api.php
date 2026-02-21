<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PostApiController;
use App\Http\Controllers\Api\CategoryApiController;
use App\Http\Controllers\Api\CommentApiController;
use App\Http\Controllers\Api\RoleApiController;
use App\Http\Controllers\Api\PermissionApiController;
use App\Http\Controllers\Api\UserApiController;

Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('me', [AuthController::class, 'me']);
        Route::put('profile', [AuthController::class, 'updateProfile']);
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('tokens', [AuthController::class, 'tokens']);
        Route::post('tokens', [AuthController::class, 'createToken']);
        Route::delete('tokens/{tokenId}', [AuthController::class, 'revokeToken']);
    });
});

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('posts', PostApiController::class);
    Route::get('my-posts', [PostApiController::class, 'myPosts']);
    
    Route::apiResource('categories', CategoryApiController::class);
    
    Route::apiResource('comments', CommentApiController::class);
    Route::get('my-comments', [CommentApiController::class, 'myComments']);
    Route::post('comments/{comment}/approve', [CommentApiController::class, 'approve']);
    Route::post('comments/{comment}/reject', [CommentApiController::class, 'reject']);
    
    Route::apiResource('roles', RoleApiController::class)->except(['create', 'edit']);
    Route::get('roles-permissions', [RoleApiController::class, 'permissions']);
    
    Route::apiResource('permissions', PermissionApiController::class)->except(['create', 'edit']);
    Route::get('permissions-groups', [PermissionApiController::class, 'groups']);
    
    Route::apiResource('users', UserApiController::class);
    Route::post('users/{user}/assign-role', [UserApiController::class, 'assignRole']);
    Route::post('users/{user}/revoke-role', [UserApiController::class, 'revokeRole']);
    Route::post('users/{user}/give-permission', [UserApiController::class, 'givePermission']);
    Route::post('users/{user}/revoke-permission', [UserApiController::class, 'revokePermission']);
});

Route::get('posts', [PostApiController::class, 'index']);
Route::get('posts/{post}', [PostApiController::class, 'show']);

Route::get('categories', [CategoryApiController::class, 'index']);
Route::get('categories-tree', [CategoryApiController::class, 'tree']);
Route::get('categories/{category}', [CategoryApiController::class, 'show']);

Route::get('comments', [CommentApiController::class, 'index']);
Route::get('comments/{comment}', [CommentApiController::class, 'show']);
