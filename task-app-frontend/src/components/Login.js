import React, { useState } from 'react';
import './Login.css';

const Login = () => {
  const [formData, setFormData] = useState({
    username: '',
    password: ''
  });
  const [loading, setLoading] = useState(false);
  const [message, setMessage] = useState('');
  const [user, setUser] = useState(null);

  const handleChange = (e) => {
    setFormData({
      ...formData,
      [e.target.name]: e.target.value
    });
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setLoading(true);
    setMessage('');

    try {
      // Stateless API login (Bearer token) — no CSRF/cookies needed
      const response = await fetch('http://127.0.0.1:8000/api/v1/auth/login', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          // 'X-Requested-With': 'XMLHttpRequest', // not required for stateless API
        },
        body: JSON.stringify({
          username: formData.username,
          password: formData.password
        })
      });

      const data = await response.json();

      if (response.ok && data.success) {
        setMessage(`Login successful! Welcome ${data.data.user.name}`);
        setUser(data.data.user);
        // Store token for future requests
        localStorage.setItem('token', data.data.token);
        localStorage.setItem('tokenType', data.data.token_type);
      } else {
        setMessage('Login failed: ' + (data.message || 'Invalid credentials'));
        console.error('Login response:', data);
      }
    } catch (error) {
      setMessage('Error: Unable to connect to server');
      console.error('Login error:', error);
    } finally {
      setLoading(false);
    }
  };

  const handleLogout = () => {
    setUser(null);
    setMessage('');
    setFormData({ username: '', password: '' });
    localStorage.removeItem('token');
    localStorage.removeItem('tokenType');
  };

  if (user) {
    return (
      <div className="login-container">
        <div className="welcome-card">
          <h2>Welcome, {user.name}!</h2>
          <div className="user-info">
            <p><strong>Username:</strong> {user.username}</p>
            <p><strong>Email:</strong> {user.email}</p>
            <p><strong>ID:</strong> {user.id}</p>
            <p><strong>Member since:</strong> {new Date(user.created_at).toLocaleDateString()}</p>
          </div>
          <button onClick={handleLogout} className="logout-btn">
            Logout
          </button>
        </div>
      </div>
    );
  }

  return (
    <div className="login-container">
      <div className="login-card">
        <h2>Task Management Login</h2>
        <form onSubmit={handleSubmit}>
          <div className="form-group">
            <label htmlFor="username">Username:</label>
            <input
              type="text"
              id="username"
              name="username"
              value={formData.username}
              onChange={handleChange}
              required
              placeholder="Enter your username"
            />
          </div>
          
          <div className="form-group">
            <label htmlFor="password">Password:</label>
            <input
              type="password"
              id="password"
              name="password"
              value={formData.password}
              onChange={handleChange}
              required
              placeholder="Enter your password"
            />
          </div>
          
          <button type="submit" disabled={loading} className="login-btn">
            {loading ? 'Logging in...' : 'Login'}
          </button>
        </form>
        
        {message && (
          <div className={`message ${user ? 'success' : 'error'}`}>
            {message}
          </div>
        )}
        
        <div className="demo-credentials">
          <p><strong>Demo Credentials:</strong></p>
          <p>Username: Sanjeewa</p>
          <p>Password: sanjeewa123</p>
        </div>
      </div>
    </div>
  );
};

export default Login;