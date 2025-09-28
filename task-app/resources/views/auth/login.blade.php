<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Task Manager</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; padding: 20px; }
        .container { max-width: 400px; margin: 50px auto; background: white; border-radius: 8px; padding: 30px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h2 { text-align: center; margin-bottom: 20px; color: #333; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; color: #555; }
        input { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; }
        button { width: 100%; padding: 12px; background: #ff00d4; color: white; border: none; border-radius: 4px; font-size: 16px; cursor: pointer; }
        button:hover { background: #0056b3; }
        .link { text-align: center; margin-top: 15px; }
        .link a { color: #007bff; text-decoration: none; }
        .error { color: red; font-size: 12px; margin-top: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        
        @if (session('status'))
            <div style="color: green; margin-bottom: 15px; text-align: center;">{{ session('status') }}</div>
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
