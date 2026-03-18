<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApiEndpoint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class ApiEndpointController extends Controller
{
    public function index(Request $request)
    {
        $query = ApiEndpoint::query();
        
        if ($request->filled('group')) {
            $query->where('group', $request->group);
        }
        
        if ($request->filled('method')) {
            $query->where('method', $request->method);
        }
        
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('path', 'like', '%' . $request->search . '%');
            });
        }
        
        $endpoints = $query->orderBy('group')->orderBy('sort')->orderBy('id')->paginate(20);
        $groups = ApiEndpoint::distinct()->pluck('group')->filter()->values();
        
        return view('admin.api-endpoints.index', compact('endpoints', 'groups'));
    }

    public function create()
    {
        $groups = ApiEndpoint::distinct()->pluck('group')->filter()->values();
        return view('admin.api-endpoints.create', compact('groups'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'method' => 'required|in:GET,POST,PUT,PATCH,DELETE',
            'path' => 'required|string|max:255|unique:api_endpoints,path,NULL,id,method,' . $request->method,
            'group' => 'required|string|max:100',
            'description' => 'nullable|string',
            'parameters' => 'nullable|array',
            'response' => 'nullable|string',
            'auth_required' => 'boolean',
            'is_active' => 'boolean',
            'sort' => 'integer|min:0',
        ]);
        
        $data['auth_required'] = $request->boolean('auth_required');
        $data['is_active'] = $request->boolean('is_active');
        $data['parameters'] = $request->filled('parameters') ? json_encode($request->parameters) : null;
        
        ApiEndpoint::create($data);
        
        return redirect()->route('admin.api-endpoints.index')->with('success', '接口创建成功');
    }

    public function show(ApiEndpoint $apiEndpoint)
    {
        return view('admin.api-endpoints.show', compact('apiEndpoint'));
    }

    public function edit(ApiEndpoint $apiEndpoint)
    {
        $groups = ApiEndpoint::where('id', '!=', $apiEndpoint->id)
            ->distinct()
            ->pluck('group')
            ->filter()
            ->values();
        
        return view('admin.api-endpoints.edit', compact('apiEndpoint', 'groups'));
    }

    public function update(Request $request, ApiEndpoint $apiEndpoint)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'method' => 'required|in:GET,POST,PUT,PATCH,DELETE',
            'path' => 'required|string|max:255|unique:api_endpoints,path,' . $apiEndpoint->id . ',id,method,' . $request->method,
            'group' => 'required|string|max:100',
            'description' => 'nullable|string',
            'parameters' => 'nullable|array',
            'response' => 'nullable|string',
            'auth_required' => 'boolean',
            'is_active' => 'boolean',
            'sort' => 'integer|min:0',
        ]);
        
        $data['auth_required'] = $request->boolean('auth_required');
        $data['is_active'] = $request->boolean('is_active');
        $data['parameters'] = $request->filled('parameters') ? json_encode($request->parameters) : null;
        
        $apiEndpoint->update($data);
        
        return redirect()->route('admin.api-endpoints.index')->with('success', '接口更新成功');
    }

    public function destroy(ApiEndpoint $apiEndpoint)
    {
        $apiEndpoint->delete();
        
        return redirect()->route('admin.api-endpoints.index')->with('success', '接口删除成功');
    }

    public function toggle(ApiEndpoint $apiEndpoint)
    {
        $apiEndpoint->update(['is_active' => !$apiEndpoint->is_active]);
        
        return back()->with('success', $apiEndpoint->is_active ? '接口已启用' : '接口已禁用');
    }

    public function sync()
    {
        $routes = Route::getRoutes();
        $synced = 0;
        $created = 0;
        $updated = 0;
        
        foreach ($routes as $route) {
            if (!str_starts_with($route->uri(), 'api/')) {
                continue;
            }
            
            $methods = array_filter($route->methods(), fn($m) => !in_array($m, ['HEAD', 'OPTIONS']));
            
            foreach ($methods as $method) {
                $apiEndpoint = ApiEndpoint::where('method', $method)
                    ->where('path', '/' . $route->uri())
                    ->first();
                
                $group = $this->detectGroup($route->uri());
                
                if ($apiEndpoint) {
                    $apiEndpoint->update([
                        'name' => $apiEndpoint->name ?: $this->generateName($route->uri()),
                        'group' => $group,
                        'auth_required' => $this->requiresAuth($route->middleware()),
                    ]);
                    $updated++;
                } else {
                    ApiEndpoint::create([
                        'name' => $this->generateName($route->uri()),
                        'method' => $method,
                        'path' => '/' . $route->uri(),
                        'group' => $group,
                        'auth_required' => $this->requiresAuth($route->middleware()),
                        'is_active' => true,
                    ]);
                    $created++;
                }
                $synced++;
            }
        }
        
        return redirect()->route('admin.api-endpoints.index')
            ->with('success', "同步完成：共处理 {$synced} 个路由，新增 {$created} 个，更新 {$updated} 个");
    }

    protected function detectGroup(string $uri): string
    {
        $segments = explode('/', $uri);
        
        if (isset($segments[1])) {
            return match($segments[1]) {
                'customer' => '客户接口',
                'products' => '商品接口',
                'categories' => '分类接口',
                'posts' => '文章接口',
                'comments' => '评论接口',
                'auth' => '认证接口',
                'roles' => '权限接口',
                'users' => '用户接口',
                'permissions' => '权限接口',
                default => '其他接口',
            };
        }
        
        return '其他接口';
    }

    protected function generateName(string $uri): string
    {
        $segments = explode('/', $uri);
        $last = end($segments);
        
        $nameMap = [
            'customer' => '客户',
            'products' => '商品',
            'categories' => '分类',
            'posts' => '文章',
            'comments' => '评论',
            'auth' => '认证',
            'roles' => '角色',
            'users' => '用户',
            'permissions' => '权限',
            'register' => '注册',
            'login' => '登录',
            'logout' => '登出',
            'me' => '当前用户',
            'cart' => '购物车',
            'orders' => '订单',
            'addresses' => '地址',
            'index' => '列表',
            'store' => '创建',
            'show' => '详情',
            'update' => '更新',
            'destroy' => '删除',
            'create' => '创建页面',
            'edit' => '编辑页面',
        ];
        
        return $nameMap[$last] ?? $last;
    }

    protected function requiresAuth(array $middleware): bool
    {
        return in_array('auth:sanctum', $middleware) || in_array('auth', $middleware);
    }
}
