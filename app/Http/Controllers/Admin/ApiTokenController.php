<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApiToken;
use App\Models\User;
use Illuminate\Http\Request;

class ApiTokenController extends Controller
{
    public function index(Request $request)
    {
        $query = ApiToken::with('user');
        
        if ($request->filled('guard')) {
            $query->where('guard', $request->guard);
        }
        
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }
        
        $tokens = $query->orderBy('created_at', 'desc')->paginate(20);
        $users = User::all();
        
        return view('admin.api-tokens.index', compact('tokens', 'users'));
    }

    public function create()
    {
        $users = User::all();
        $abilities = $this->getAbilityOptions();
        
        return view('admin.api-tokens.create', compact('users', 'abilities'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'user_id' => 'nullable|exists:users,id',
            'guard' => 'required|in:sanctum,customer',
            'abilities' => 'nullable|array',
            'expires_at' => 'nullable|date|after:now',
            'is_active' => 'boolean',
            'description' => 'nullable|string',
        ]);
        
        $data['token'] = ApiToken::generateToken();
        $data['abilities'] = $request->filled('abilities') ? json_encode($request->abilities) : null;
        $data['is_active'] = $request->boolean('is_active', true);
        
        $apiToken = ApiToken::create($data);
        
        return redirect()->route('admin.api-tokens.show', $apiToken)
            ->with('success', 'Token 创建成功，请及时复制保存！');
    }

    public function show(ApiToken $apiToken)
    {
        $apiToken->load('user');
        $apiToken->makeVisible('token');
        return view('admin.api-tokens.show', compact('apiToken'));
    }

    public function edit(ApiToken $apiToken)
    {
        $users = User::all();
        $abilities = $this->getAbilityOptions();
        
        return view('admin.api-tokens.edit', compact('apiToken', 'users', 'abilities'));
    }

    public function update(Request $request, ApiToken $apiToken)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'user_id' => 'nullable|exists:users,id',
            'guard' => 'required|in:sanctum,customer',
            'abilities' => 'nullable|array',
            'expires_at' => 'nullable|date|after:now',
            'is_active' => 'boolean',
            'description' => 'nullable|string',
        ]);
        
        $data['abilities'] = $request->filled('abilities') ? json_encode($request->abilities) : null;
        $data['is_active'] = $request->boolean('is_active', true);
        
        $apiToken->update($data);
        
        return redirect()->route('admin.api-tokens.index')->with('success', 'Token 更新成功');
    }

    public function destroy(ApiToken $apiToken)
    {
        $apiToken->delete();
        
        return redirect()->route('admin.api-tokens.index')->with('success', 'Token 删除成功');
    }

    public function toggle(ApiToken $apiToken)
    {
        $apiToken->update(['is_active' => !$apiToken->is_active]);
        
        return back()->with('success', $apiToken->is_active ? 'Token 已启用' : 'Token 已禁用');
    }

    public function regenerate(ApiToken $apiToken)
    {
        $apiToken->update(['token' => ApiToken::generateToken()]);
        
        return redirect()->route('admin.api-tokens.show', $apiToken)
            ->with('success', 'Token 已重新生成，请及时复制保存！');
    }

    public function test(Request $request)
    {
        $request->validate([
            'method' => 'required|in:GET,POST,PUT,PATCH,DELETE',
            'url' => 'required|url',
            'token' => 'nullable|string',
            'body' => 'nullable|string',
        ]);
        
        $method = $request->method;
        $url = $request->url;
        $token = $request->token;
        $body = $request->body;
        
        $ch = curl_init();
        
        $headers = ['Accept: application/json'];
        if ($token) {
            $headers[] = 'Authorization: Bearer ' . $token;
        }
        
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        
        if (in_array($method, ['POST', 'PUT', 'PATCH']) && $body) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
            $headers[] = 'Content-Type: application/json';
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        
        curl_close($ch);
        
        $result = [
            'status' => $httpCode,
            'response' => json_decode($response, true) ?? $response,
            'error' => $error,
        ];
        
        return response()->json($result);
    }

    protected function getAbilityOptions(): array
    {
        return [
            '*' => '全部权限',
            'read' => '读取',
            'write' => '写入',
            'delete' => '删除',
        ];
    }
}
