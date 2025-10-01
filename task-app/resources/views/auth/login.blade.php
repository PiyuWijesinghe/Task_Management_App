<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Task Manager</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: linear-gradient(135deg, #667eea 0%, #764ba2 25%, #667eea 50%, #764ba2 75%, #f093fb 100%); padding: 20px; min-height: 100vh; }
        .container { max-width: 400px; margin: 50px auto; background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(20px); border-radius: 20px; padding: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); border: 1px solid rgba(255,255,255,0.2); }
        h2 { text-align: center; margin-bottom: 20px; color: #333; font-weight: bold; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; color: #555; font-weight: 500; }
        input { width: 100%; padding: 12px; border: 1px solid rgba(102, 126, 234, 0.2); border-radius: 12px; font-size: 14px; background: rgba(255,255,255,0.8); backdrop-filter: blur(10px); }
        button { width: 100%; padding: 12px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 12px; font-size: 16px; cursor: pointer; font-weight: 600; }
        button:hover { background: linear-gradient(135deg, #5a67d8 0%, #6b46c1 100%); transform: translateY(-2px); }
        .link { text-align: center; margin-top: 15px; }
        .link a { color: #007bff; text-decoration: none; }
        .error { color: red; font-size: 12px; margin-top: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        
        @if (session('status'))
            <div style="color: rgb(35, 118, 55); margin-bottom: 15px; text-align: center;">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <label for="email">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
                @error('email')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input id="password" type="password" name="password" required>
                @error('password')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>
                    <input type="checkbox" name="remember" style="width: auto; margin-right: 5px;">
                    Remember me
                </label>
            </div>

            <button type="submit">Login</button>

            <div class="link">
                @if (Route::has('register'))
                    <a href="{{ route('register') }}">Don't have an account? Register</a>
                @endif
            </div>

            @if (Route::has('password.request'))
                <div class="link">
                    <a href="{{ route('password.request') }}">Forgot your password?</a>
                </div>
            @endif
        </form>
    </div>
</body>
</html>
