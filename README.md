# Task Management Application

A comprehensive task management system built with Laravel 12 and MySQL, featuring user authentication, task CRUD operations, and a clean web interface.

## Features

- User registration and authentication with Laravel Breeze
- Create, read, update, and delete tasks
- Task status management (Pending, In Progress, Completed)
- Due date tracking and management
- Task assignment to authenticated users
- Responsive web interface with Tailwind CSS
- Database-driven session management
- User profile management and security
- Task authorization policies

## Technology Stack

- **Backend Framework**: Laravel 12.x
- **Database Engine**: MySQL 8.0+
- **Frontend**: Blade Templates with Tailwind CSS
- **Authentication**: Laravel Breeze
- **PHP Version**: 8.2+
- **Package Manager**: Composer
- **Asset Bundling**: Vite

## Installation Instructions

### Prerequisites
- PHP 8.2+ with required extensions
- Composer
- Node.js and npm
- Git

### Setup Steps

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd task-app
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node.js dependencies**
   ```bash
   npm install
   ```

4. **Environment Configuration**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Database Configuration**
   Create a MySQL database and update your `.env` file:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=new_task_app
   DB_USERNAME=root
   DB_PASSWORD=your_password
   
   SESSION_DRIVER=database
   SESSION_LIFETIME=120
   ```

6. **Create Database**
   ```sql
   CREATE DATABASE new_task_app;
   ```

7. **Run Database Migrations**
   ```bash
   php artisan migrate
   ```

8. **Build Frontend Assets**
   ```bash
   npm run build
   # For development with hot reload:
   # npm run dev
   ```

9. **Start the Development Server**
   ```bash
   php artisan serve
   ```

10. **Access the Application**
    Open your browser and visit: `http://127.0.0.1:8000`

## Database Schema

### Users Table
- `id` - Primary key (auto-increment)
- `name` - User's full name (string)
- `email` - User's email address (string, unique)
- `email_verified_at` - Email verification timestamp
- `password` - Hashed password
- `remember_token` - Remember me functionality
- `created_at` - Record creation timestamp
- `updated_at` - Last update timestamp

### Tasks Table
- `id` - Primary key (auto-increment)
- `title` - Task title (string, required)
- `description` - Task description (text, nullable)
- `due_date` - Task due date (date, nullable)
- `status` - Task status (enum: Pending, In Progress, Completed)
- `user_id` - Foreign key reference to users table (with cascade delete)
- `created_at` - Record creation timestamp
- `updated_at` - Last update timestamp

### Sessions Table
- `id` - Session identifier (string, primary key)
- `user_id` - Foreign key to users table (nullable)
- `ip_address` - User's IP address (string, nullable)
- `user_agent` - Browser user agent (text, nullable)
- `payload` - Session data (longtext)
- `last_activity` - Last activity timestamp (integer)

## Application Routes

### Authentication Routes
- `GET /` - Redirects to login page
- `GET /login` - Login form display
- `POST /login` - Process user login
- `POST /logout` - User logout
- `GET /register` - Registration form
- `POST /register` - Process user registration

### Protected Routes (Require Authentication)
- `GET /dashboard` - Main dashboard
- `GET /tasks` - List user's tasks
- `GET /tasks/create` - Task creation form
- `POST /tasks` - Store new task
- `GET /tasks/{id}` - Display specific task
- `GET /tasks/{id}/edit` - Edit task form
- `PUT /tasks/{id}` - Update task
- `DELETE /tasks/{id}` - Delete task
- `GET /profile` - User profile management
- `PATCH /profile` - Update user profile
- `DELETE /profile` - Delete user account

## Key Features

### Task Management
- Full CRUD operations for tasks
- Task status workflow management
- Due date tracking and alerts
- User-specific task isolation
- Task authorization policies

### Security Features
- CSRF protection on all forms
- Password hashing with Laravel Hash
- SQL injection prevention via Eloquent ORM
- XSS protection through Blade templating
- Authentication middleware protection
- Authorization policies for task access

### Session Management
- Database-driven session storage
- Secure session configuration
- User activity tracking
- Session cleanup and management

## Development

### Running Tests
```bash
php artisan test
```

### Code Formatting
This project uses Laravel Pint for code formatting:
```bash
./vendor/bin/pint
```

### Cache Management
Clear all application caches:
```bash
php artisan optimize:clear
```

Individual cache clearing:
```bash
php artisan cache:clear        # Application cache
php artisan config:clear       # Configuration cache
php artisan route:clear        # Route cache
php artisan view:clear         # View cache
```

## Troubleshooting

### Common Issues

1. **Database Connection Error**
   - Verify MySQL service is running
   - Check database credentials in `.env` file
   - Ensure the database exists

2. **Sessions Table Missing Error**
   - Run `php artisan migrate` to create all tables
   - Verify sessions table exists in database
   - Check SESSION_DRIVER=database in `.env`

3. **Permission Denied Errors**
   - Set proper permissions on storage directory:
     ```bash
     chmod -R 775 storage
     chmod -R 775 bootstrap/cache
     ```

4. **Frontend Assets Not Loading**
   - Run `npm run build` to compile assets
   - Clear browser cache
   - Verify Vite configuration

5. **Authentication Not Working**
   - Clear application cache
   - Verify APP_KEY is set in `.env`
   - Check session configuration

## Environment Configuration

### Required Environment Variables
```env
APP_NAME=Task Management App
APP_ENV=local
APP_KEY=base64:generated-key-here
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=new_task_app
DB_USERNAME=root
DB_PASSWORD=

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false

CACHE_STORE=database
QUEUE_CONNECTION=database
```

## Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

**Version**: 1.0.0  
**Last Updated**: September 27, 2025  
**Laravel Version**: 12.x  
**PHP Version**: 8.2+
