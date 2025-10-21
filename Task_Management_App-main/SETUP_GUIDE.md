# Complete Setup Guide - Task Management System

## 🚀 Full System Setup

This guide will help you set up both the Laravel backend and React frontend for the Task Management System.

## Prerequisites

- PHP 8.1 or higher
- Composer
- Node.js 16+ and npm
- MySQL/PostgreSQL database
- Git

## Backend Setup (Laravel)

### 1. Navigate to Laravel Project
```bash
cd task-app
```

### 2. Install PHP Dependencies
```bash
composer install
```

### 3. Environment Configuration
```bash
cp .env.example .env
```

Edit `.env` file with your database credentials:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=task_management_db
DB_USERNAME=your_username
DB_PASSWORD=your_password

# Add CORS settings for React frontend
FRONTEND_URL=http://localhost:3000
```

### 4. Generate Application Key
```bash
php artisan key:generate
```

### 5. Run Database Migrations
```bash
php artisan migrate
```

### 6. Seed Database (Optional)
```bash
php artisan db:seed
```

### 7. Create Storage Link
```bash
php artisan storage:link
```

### 8. Install Laravel Sanctum for API Authentication
```bash
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
php artisan migrate
```

### 9. Start Laravel Development Server
```bash
php artisan serve
```
Backend will be available at: `http://127.0.0.1:8000`

## Frontend Setup (React)

### 1. Navigate to React Project
```bash
cd ../task-app-frontend
```

### 2. Install Node Dependencies
```bash
npm install
```

### 3. Install Tailwind CSS Dependencies
```bash
npm install -D tailwindcss postcss autoprefixer @tailwindcss/forms
```

### 4. Initialize Tailwind CSS (if not already configured)
```bash
npx tailwindcss init -p
```

### 5. Environment Configuration
Create `.env` file:
```env
REACT_APP_API_BASE_URL=http://127.0.0.1:8000/api/v1
```

### 6. Start React Development Server
```bash
npm start
```
Frontend will be available at: `http://localhost:3000`

## 🔧 API Testing

You can test the API routes using the provided Postman collection or manually:

### Authentication
```bash
# Register
curl -X POST http://127.0.0.1:8000/api/v1/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "John Doe",
    "email": "john@example.com", 
    "password": "password123",
    "password_confirmation": "password123"
  }'

# Login
curl -X POST http://127.0.0.1:8000/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "john@example.com",
    "password": "password123"
  }'
```

### Tasks (with Bearer token)
```bash
# Get all tasks
curl -X GET http://127.0.0.1:8000/api/v1/tasks \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Accept: application/json"

# Create task
curl -X POST http://127.0.0.1:8000/api/v1/tasks \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -d '{
    "title": "Test Task",
    "description": "Test task description",
    "priority": "medium",
    "status": "pending"
  }'
```

## 🎯 Usage Flow

1. **Start Backend**: `php artisan serve` in `task-app/`
2. **Start Frontend**: `npm start` in `task-app-frontend/`
3. **Access Application**: Visit `http://localhost:3000`
4. **Register/Login**: Create account or login
5. **Explore Features**: 
   - Dashboard overview
   - Create and manage tasks
   - Assign tasks to users
   - Add comments and postpone tasks
   - View reports and analytics

## 📱 Available Routes

### Frontend Routes
- `/login` - Login page
- `/register` - Registration page
- `/dashboard` - Main dashboard
- `/tasks` - Task list with filters
- `/tasks/create` - Create new task
- `/tasks/:id` - Task details
- `/tasks/:id/edit` - Edit task
- `/tasks/assign` - Bulk task assignment
- `/tasks/postponed` - Postponed tasks
- `/users` - User management
- `/profile` - User profile
- `/reports` - Analytics and reports

### API Endpoints
All backend routes are prefixed with `/api/v1/`:

- **Auth**: `/auth/login`, `/auth/register`, `/auth/user`, etc.
- **Tasks**: `/tasks`, `/tasks/{id}`, `/tasks/dashboard`, etc.
- **Users**: `/users`, `/users/{id}`, `/users/search`
- **Comments**: `/tasks/{task}/comments`
- **Reports**: `/reports/tasks/summary`, `/reports/productivity`

## 🔐 Authentication Flow

1. User registers/logs in via frontend
2. Frontend receives JWT token from Laravel Sanctum
3. Token stored in localStorage
4. All subsequent API requests include Bearer token
5. Backend validates token for protected routes

## 🛠️ Development Tips

### Backend (Laravel)
- Use `php artisan tinker` for database testing
- Check logs: `tail -f storage/logs/laravel.log`
- Clear cache: `php artisan cache:clear`
- Run tests: `php artisan test`

### Frontend (React)
- Use React DevTools for component debugging
- Check Network tab for API call issues
- Use `console.log()` for debugging
- Install React Query DevTools for state inspection

## 🚨 Troubleshooting

### CORS Issues
Add to Laravel `.env`:
```env
SANCTUM_STATEFUL_DOMAINS=localhost:3000,127.0.0.1:3000
```

Update `config/cors.php`:
```php
'allowed_origins' => ['http://localhost:3000'],
'supports_credentials' => true,
```

### Database Connection Issues
- Verify database credentials in `.env`
- Ensure database server is running
- Check database exists: `CREATE DATABASE task_management_db;`

### Frontend Build Issues
- Clear node_modules: `rm -rf node_modules && npm install`
- Clear npm cache: `npm cache clean --force`
- Check Node.js version compatibility

### API Token Issues
- Verify token is being sent in Authorization header
- Check token expiration in Laravel config
- Ensure Sanctum is properly configured

## 📞 Support

For issues or questions:
1. Check the troubleshooting section above
2. Review Laravel and React documentation
3. Check browser console and network tabs
4. Review backend logs for API issues