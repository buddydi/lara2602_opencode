<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>注册</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #f5f5f5; }
        .register-card { background: #fff; padding: 30px; border-radius: 8px; max-width: 400px; margin: 80px auto; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        h2 { text-align: center; margin-bottom: 30px; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; color: #333; }
        .form-group input { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; }
        .btn { width: 100%; padding: 12px; background: #e4393c; color: #fff; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; }
        .btn:hover { background: #c23536; }
        .links { text-align: center; margin-top: 20px; }
        .links a { color: #e4393c; text-decoration: none; }
        .alert { padding: 12px; background: #fdecea; border: 1px solid #fadbd8; color: #e74c3c; margin-bottom: 20px; border-radius: 4px; }
    </style>
</head>
<body>
    <div class="register-card">
        <h2>用户注册</h2>
        
        @if($errors->any())
            <div class="alert">
                {{ $errors->first() }}
            </div>
        @endif
        
        <form method="POST" action="{{ route('customer.register') }}">
            @csrf
            <div class="form-group">
                <label>用户名</label>
                <input type="text" name="name" required>
            </div>
            
            <div class="form-group">
                <label>邮箱</label>
                <input type="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label>密码</label>
                <input type="password" name="password" required minlength="8">
            </div>
            
            <div class="form-group">
                <label>确认密码</label>
                <input type="password" name="password_confirmation" required>
            </div>
            
            <button type="submit" class="btn">注册</button>
        </form>
        
        <div class="links">
            <a href="{{ route('customer.login') }}">已有账号？立即登录</a>
        </div>
    </div>
</body>
</html>
