# Task Management Application - Backend API

A comprehensive collaborative task management system built with Laravel 12 and MySQL, featuring advanced user authentication, multi-user task assignment, priority management, real-time commenting, and a RESTful API backend.

## 🚀 Quick Start

### Prerequisites
- PHP 8.2 or higher
- Composer
- MySQL 8.0 or higher
- Git

### Installation & Setup

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd task-app
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database configuration**
   Update your `.env` file with database credentials:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=task_management_app
   DB_USERNAME=root
   DB_PASSWORD=your_password
   
   SESSION_DRIVER=database
   SESSION_LIFETIME=120
   ```

5. **Create database and run migrations**
   ```bash
   # Create database in MySQL
   mysql -u root -p -e "CREATE DATABASE task_management_app;"
   
   # Run migrations and seeders
   php artisan migrate
   php artisan db:seed  # Optional: seed with sample data
   ```

6. **Start the development server**
   ```bash
   php artisan serve
   ```
   
   The API will be available at: `http://localhost:8000`

## 📋 Key Features

### 🔐 Authentication & User Management
- **Username-based Authentication**: Secure login with username or email
- **Laravel Sanctum**: Token-based API authentication
- **User Profile Management**: Complete profile editing capabilities
- **Session Management**: Database-driven secure session handling
- **Authorization Policies**: Role-based task access control

### 📝 Advanced Task Management
- **Complete CRUD Operations**: Create, read, update, and delete tasks
- **Multi-User Assignment**: Assign tasks to multiple users simultaneously
- **Priority System**: Set and filter tasks by priority (High, Medium, Low)
- **Status Workflow**: Track progress (Pending, In Progress, Completed)
- **Due Date Management**: Set deadlines with overdue alerts
- **Task Postponement**: Reschedule tasks with reason tracking

### 💬 Collaborative Features
- **Real-time Comments**: Add comments to tasks with chronological ordering
- **Comment Management**: Delete own comments with proper authorization
- **Activity Tracking**: Timestamps for all actions and updates

### 🔍 Advanced Filtering & Search
- **Priority Filtering**: Filter tasks by High, Medium, Low priority
- **Status Filtering**: View tasks by completion status
- **User Assignment Search**: Find tasks by assigned users
- **Sort Options**: Sort by priority, due date, or creation date
- **Full-text Search**: Search across task titles and descriptions

## 🛠️ Technology Stack

- **Backend Framework**: Laravel 12.x
- **Database Engine**: MySQL 8.0+
- **Authentication**: Laravel Sanctum (API tokens)
- **PHP Version**: 8.2+
- **Package Manager**: Composer
- **Testing**: PHPUnit
- **API Documentation**: Built-in OpenAPI support

## 🌐 API Endpoints

### Base URL
```
http://localhost:8000/api/v1
```

### Authentication Required
Most endpoints require a Bearer token in the Authorization header:
```
Authorization: Bearer {your-token}
```

### 🔐 Authentication Endpoints

| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| `POST` | `/auth/register` | Register new user | ❌ |
| `POST` | `/auth/login` | User login | ❌ |
| `GET` | `/auth/user` | Get authenticated user | ✅ |
| `POST` | `/auth/logout` | Logout user | ✅ |
| `POST` | `/auth/logout-all` | Logout from all devices | ✅ |
| `PATCH` | `/auth/update-profile` | Update user profile | ✅ |
| `POST` | `/auth/change-password` | Change password | ✅ |

### 📝 Task Management Endpoints

| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| `GET` | `/tasks` | List all tasks with filters | ✅ |
| `POST` | `/tasks` | Create new task | ✅ |
| `GET` | `/tasks/{id}` | Get specific task | ✅ |
| `PUT` | `/tasks/{id}` | Update task | ✅ |
| `DELETE` | `/tasks/{id}` | Delete task | ✅ |
| `PATCH` | `/tasks/{id}/status` | Update task status | ✅ |
| `PATCH` | `/tasks/{id}/complete` | Mark task as completed | ✅ |
| `PATCH` | `/tasks/{id}/priority` | Update task priority | ✅ |

### 👥 Task Assignment Endpoints

| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| `GET` | `/tasks/{id}/assignees` | Get task assignees | ✅ |
| `POST` | `/tasks/{id}/assign` | Assign user to task | ✅ |
| `DELETE` | `/tasks/{id}/unassign/{userId}` | Unassign user from task | ✅ |

### 💬 Task Comments Endpoints

| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| `GET` | `/tasks/{id}/comments` | Get task comments | ✅ |
| `POST` | `/tasks/{id}/comments` | Add comment to task | ✅ |
| `PUT` | `/tasks/{id}/comments/{commentId}` | Update comment | ✅ |
| `DELETE` | `/tasks/{id}/comments/{commentId}` | Delete comment | ✅ |
| `GET` | `/comments/{id}` | Get specific comment | ✅ |

### 📅 Task Operations Endpoints

| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| `POST` | `/tasks/{id}/postpone` | Postpone task | ✅ |
| `GET` | `/tasks/{id}/postponements` | Get postponement history | ✅ |
| `GET` | `/tasks/overdue` | Get overdue tasks | ✅ |
| `GET` | `/tasks/due-today` | Get tasks due today | ✅ |
| `GET` | `/postponements` | Get user's postponements | ✅ |

### 👤 User Management Endpoints

| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| `GET` | `/users` | Get all users | ✅ |
| `GET` | `/users/{id}` | Get specific user | ✅ |
| `GET` | `/users/search` | Search users | ✅ |
| `GET` | `/reports/user/activity` | Get user activity report | ✅ |

### 📊 Dashboard & Analytics Endpoints

| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| `GET` | `/tasks/dashboard` | Get dashboard statistics | ✅ |
| `GET` | `/search/tasks` | Advanced task search | ✅ |

### 🔄 Bulk Operations Endpoints

| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| `POST` | `/bulk/tasks/complete` | Bulk complete tasks | ✅ |
| `POST` | `/bulk/tasks/delete` | Bulk delete tasks | ✅ |
| `POST` | `/bulk/tasks/assign` | Bulk assign tasks | ✅ |
| `POST` | `/bulk/tasks/update-priority` | Bulk update priority | ✅ |

### 📝 Example API Usage

#### Authentication Flow
```bash
# 1. Register a new user
curl -X POST http://localhost:8000/api/v1/auth/register \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "name": "John Doe",
    "username": "johndoe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'

# 2. Login to get token
curl -X POST http://localhost:8000/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "login": "johndoe",
    "password": "password123"
  }'
```

#### Task Management
```bash
# 3. Create a task
curl -X POST http://localhost:8000/api/v1/tasks \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "title": "Complete API Documentation",
    "description": "Write comprehensive API documentation",
    "due_date": "2025-10-15",
    "priority": "High"
  }'

# 4. Get all tasks with filters
curl -X GET "http://localhost:8000/api/v1/tasks?status=Pending&priority=High&sort_by=due_date&sort_order=asc" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Accept: application/json"

# 5. Add comment to task
curl -X POST http://localhost:8000/api/v1/tasks/1/comments \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "comment": "Started working on this task"
  }'
```

#### Query Parameters for GET /tasks
- `status`: Filter by status (`Pending`, `In Progress`, `Completed`)
- `priority`: Filter by priority (`High`, `Medium`, `Low`)
- `sort_by`: Sort by (`due_date`, `priority`, `created_at`, `title`)
- `sort_order`: Sort order (`asc`, `desc`)
- `limit`: Items per page (1-100, default: 15)
- `page`: Page number (default: 1)
- `search`: Search term for title/description

### 📄 Response Format
All API responses follow this structure:

#### Success Response
```json
{
    "success": true,
    "message": "Operation successful",
    "data": {
        // Response data here
    }
}
```

#### Error Response
```json
{
    "success": false,
    "message": "Error description",
    "errors": {
        // Validation errors (if applicable)
    }
}
```

## 🧪 Development & Testing

### Running Tests
```bash
# Run all tests
php artisan test

# Run specific test suite
php artisan test --testsuite=Feature
php artisan test --testsuite=Unit

# Run tests with coverage
php artisan test --coverage
```

### Code Quality & Formatting
```bash
# Format code with Laravel Pint
./vendor/bin/pint

# Check code style without fixing
./vendor/bin/pint --test

# Run static analysis (if PHPStan is installed)
./vendor/bin/phpstan analyse
```

### Database Management
```bash
# Fresh migration with seeding
php artisan migrate:fresh --seed

# Rollback migrations
php artisan migrate:rollback

# Reset database
php artisan migrate:reset

# Create new migration
php artisan make:migration create_example_table

# Create new seeder
php artisan make:seeder ExampleSeeder
```

### Cache Management
```bash
# Clear all caches
php artisan optimize:clear

# Individual cache clearing
php artisan cache:clear        # Application cache
php artisan config:clear       # Configuration cache
php artisan route:clear        # Route cache
php artisan view:clear         # View cache
php artisan event:clear        # Event cache

# Optimize for production
php artisan optimize
```

### API Testing with Postman
A Postman collection is available at `postman_task_api_collection.json`. Import it to test all API endpoints.

### Environment Variables
Create a `.env` file with the following essential variables:

```env
# Application
APP_NAME="Task Management API"
APP_ENV=local
APP_KEY=base64:generated-key-here
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=task_management_app
DB_USERNAME=root
DB_PASSWORD=

# Session & Authentication
SESSION_DRIVER=database
SESSION_LIFETIME=120
SANCTUM_STATEFUL_DOMAINS=localhost:3000

# Cache & Queue
CACHE_STORE=database
QUEUE_CONNECTION=database

# Mail (optional)
MAIL_MAILER=smtp
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
```

## 🚀 Production Deployment

### Prerequisites for Production
- PHP 8.2+ with required extensions (mbstring, pdo_mysql, etc.)
- MySQL 8.0+ or MariaDB 10.4+
- Composer
- Web server (Apache/Nginx)
- SSL certificate (recommended)

### Production Setup
```bash
# 1. Clone and setup
git clone <repository-url> /var/www/task-api
cd /var/www/task-api

# 2. Install dependencies
composer install --optimize-autoloader --no-dev

# 3. Configure environment
cp .env.example .env
php artisan key:generate

# 4. Set permissions
sudo chown -R www-data:www-data /var/www/task-api
sudo chmod -R 755 /var/www/task-api
sudo chmod -R 775 /var/www/task-api/storage
sudo chmod -R 775 /var/www/task-api/bootstrap/cache

# 5. Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

# 6. Run migrations
php artisan migrate --force
```

### Nginx Configuration Example
```nginx
server {
    listen 80;
    server_name your-api-domain.com;
    root /var/www/task-api/public;
    
    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";
    
    index index.php;
    
    charset utf-8;
    
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }
    
    error_page 404 /index.php;
    
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
    
    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

## 🔧 Troubleshooting

### Common Issues

#### Database Connection Error
```bash
# Check MySQL service
sudo systemctl status mysql

# Test database connection
php artisan tinker
>>> DB::connection()->getPdo();
```

#### Permission Issues
```bash
# Fix storage permissions
sudo chmod -R 775 storage bootstrap/cache
sudo chown -R www-data:www-data storage bootstrap/cache
```

#### Token Authentication Issues
```bash
# Clear auth cache
php artisan cache:clear
php artisan config:clear

# Check Sanctum configuration
php artisan route:list | grep sanctum
```

#### 500 Internal Server Error
```bash
# Check logs
tail -f storage/logs/laravel.log

# Enable debug mode in .env
APP_DEBUG=true
```

## 📊 Performance Optimization

### Database Indexing
Key indexes are automatically created through migrations:
- `tasks.user_id` - For task owner queries
- `tasks.assigned_user_id` - For assignment queries
- `tasks.status` - For status filtering
- `tasks.priority` - For priority filtering
- `tasks.due_date` - For date-based queries

### Query Optimization
- Eager loading relationships to prevent N+1 queries
- Database query caching for dashboard statistics
- Pagination for large datasets

### API Rate Limiting
- 60 requests per minute for unauthenticated users
- 100 requests per minute for authenticated users
- Customizable via `config/sanctum.php`

## 📚 Additional Resources

- [Laravel 12 Documentation](https://laravel.com/docs/12.x)
- [Laravel Sanctum Documentation](https://laravel.com/docs/12.x/sanctum)
- [MySQL 8.0 Documentation](https://dev.mysql.com/doc/refman/8.0/en/)
- [PHPUnit Documentation](https://phpunit.de/documentation.html)

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
- `username` - Unique username for login (string, unique)
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
- `priority` - Task priority (enum: High, Medium, Low, default: Medium)
- `user_id` - Foreign key reference to users table (task creator)
- `assigned_user_id` - Foreign key reference for single user assignment
- `created_at` - Record creation timestamp
- `updated_at` - Last update timestamp

### Task User Pivot Table (task_user)
- `id` - Primary key (auto-increment)
- `task_id` - Foreign key reference to tasks table
- `user_id` - Foreign key reference to users table
- `created_at` - Assignment timestamp
- `updated_at` - Last update timestamp

### Task Comments Table (task_comments)
- `id` - Primary key (auto-increment)
- `task_id` - Foreign key reference to tasks table (cascade delete)
- `user_id` - Foreign key reference to users table (cascade delete)
- `comment` - Comment text (text, required)
- `created_at` - Comment creation timestamp
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

#### Dashboard & Task Management
- `GET /dashboard` - Main dashboard with task overview and statistics
- `GET /tasks` - List tasks with filtering and sorting options
- `GET /tasks/create` - Task creation form with user assignment
- `POST /tasks` - Store new task with validation
- `GET /tasks/{id}` - Display specific task with comments
- `GET /tasks/{id}/edit` - Edit task form
- `PUT /tasks/{id}` - Update task
- `DELETE /tasks/{id}` - Delete task
- `PATCH /tasks/{id}/complete` - Mark task as completed

#### Task Assignment & Management
- `GET /tasks-assign` - Multi-user task assignment interface
- `PATCH /tasks/{id}/assign` - Assign task to multiple users
- `POST /tasks/{id}/postpone` - Postpone task with reason
- `GET /tasks-postponed` - View postponed tasks

#### Comment System
- `POST /tasks/{id}/comments` - Add comment to task
- `DELETE /comments/{id}` - Delete comment (with authorization)

#### User Profile
- `GET /profile` - User profile management
- `PATCH /profile` - Update user profile
- `DELETE /profile` - Delete user account


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

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Support

For support and questions:
- Create an issue in the repository
- Check the troubleshooting section above
- Review Laravel documentation at [laravel.com/docs](https://laravel.com/docs)

## New Features (Latest Updates)

### Version 2.0.0 - Major Feature Release

#### Multi-User Task Assignment
- **Searchable User Dropdowns**: Easy user selection with real-time search
- **Many-to-Many Relationships**: Assign tasks to multiple users simultaneously
- **Assignment Visualization**: Clear display of all assigned users with badges
- **Assignment Management**: Dedicated interface for managing task assignments

#### Priority Management System
- **Priority Levels**: High, Medium, Low priority classification
- **Visual Indicators**: Color-coded priority badges throughout the interface
- **Priority Filtering**: Filter tasks by priority level
- **Priority Sorting**: Sort task lists by priority importance

#### Enhanced Authentication
- **Username Login**: Login with username or email address
- **User Profile Enhancement**: Complete profile management system
- **Username Uniqueness**: Enforced unique usernames across the system
- **Authentication Flexibility**: Multiple login methods for user convenience

#### Real-Time Comment System
- **Task Comments**: Add comments to any task for collaboration
- **Chronological Ordering**: Comments displayed in chronological order (oldest first)
- **User Attribution**: Each comment shows author name and timestamp
- **Comment Management**: Delete own comments with proper authorization
- **Visual Design**: Beautiful comment interface with user avatars

#### UI/UX Enhancements
- **Gradient Design System**: Modern gradient-based color scheme
- **Interactive Elements**: Hover effects and smooth transitions
- **Responsive Layout**: Perfect display on all device sizes
- **Dark Mode**: Complete dark theme support
- **Glass Morphism**: Modern glassmorphism design elements
- **Improved Navigation**: Enhanced navigation with "View Details" buttons

#### Advanced Filtering & Sorting
- **Multi-Level Filtering**: Filter by status, priority, and assignments
- **Smart Search**: Search across task titles and descriptions
- **Sort Options**: Multiple sorting criteria (priority, date, status)
- **Filter Persistence**: Maintain filter state across navigation


**Version**: 2.0.0  
**Last Updated**: October 3, 2025  
**Laravel Version**: 12.x  
**PHP Version**: 8.2+  
**Major Features**: Multi-user assignment, Priority system, Comment system, Enhanced UI
