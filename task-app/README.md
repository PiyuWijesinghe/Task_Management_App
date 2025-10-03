# Task Management Application

A comprehensive collaborative task management system built with Laravel 12 and MySQL, featuring advanced user authentication, multi-user task assignment, priority management, real-time commenting, and a beautiful responsive interface.

## Key Features

### Authentication & User Management
- **Username-based Authentication**: Secure login with username or email
- **User Profile Management**: Complete profile editing with avatar initials
- **Session Management**: Database-driven secure session handling
- **Authorization Policies**: Role-based task access control

### Advanced Task Management
- **Complete CRUD Operations**: Create, read, update, and delete tasks
- **Multi-User Assignment**: Assign tasks to multiple users simultaneously
- **Priority System**: Set and filter tasks by priority (High, Medium, Low)
- **Status Workflow**: Track progress (Pending, In Progress, Completed)
- **Due Date Management**: Set deadlines with overdue alerts
- **Task Postponement**: Reschedule tasks with reason tracking

### Collaborative Features
- **Real-time Comments**: Add comments to tasks with chronological ordering
- **Comment Management**: Delete own comments with proper authorization
- **Activity Tracking**: Timestamps for all actions and updates

### Beautiful User Interface
- **Gradient Design**: Modern UI with beautiful gradient backgrounds
- **Responsive Layout**: Perfect on desktop, tablet, and mobile devices
- **Interactive Elements**: Hover effects
- **Dark Mode Support**: Toggle between light and dark themes
- **Priority Badges**: Visual priority indicators with color coding
- **Status Indicators**: Clear visual status representation

### Advanced Filtering & Search
- **Priority Filtering**: Filter tasks by High, Medium, Low priority
- **Status Filtering**: View tasks by completion status
- **User Assignment Search**: Find tasks by assigned users
- **Searchable Dropdowns**: Easy user selection with search functionality
- **Sort Options**: Sort by priority, due date, or creation date

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
