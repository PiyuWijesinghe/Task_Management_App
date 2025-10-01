<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Task Manager</title>
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
        <h2>Register</h2>
        
        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="form-group">
                <label for="name">Name</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus>
                @error('name')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required>
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
                <label for="password_confirmation">Confirm Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required>
                @error('password_confirmation')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit">Register</button>

            <div class="link">
                <a href="{{ route('login') }}">Already registered? Login</a>
            </div>
        </form>
    </div>
</body>
</html>
