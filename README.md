# Task Management Application - Full Stack

A comprehensive collaborative task management system built with **Laravel 12** backend API and **React 19** frontend, featuring advanced user authentication, multi-user task assignment, priority management, real-time commenting, and a beautiful responsive interface with modern UI/UX design.


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
- **Interactive Elements**: Hover effects and smooth animations
- **Dark Mode Support**: Toggle between light and dark themes
- **Priority Badges**: Visual priority indicators with color coding
- **Status Indicators**: Clear visual status representation
- **Professional Loading States**: Advanced loading spinners and overlays
- **Error Handling**: Comprehensive error notifications with auto-dismiss
- **Confirmation Dialogs**: User-friendly confirmation prompts
- **Toast Notifications**: Success, warning, and error notifications

### Advanced Filtering & Search
- **Priority Filtering**: Filter tasks by High, Medium, Low priority
- **Status Filtering**: View tasks by completion status
- **User Assignment Search**: Find tasks by assigned users
- **Searchable Dropdowns**: Easy user selection with search functionality
- **Sort Options**: Sort by priority, due date, or creation date

## Technology Stack

### Backend (Laravel API)
- **Framework**: Laravel 12.x
- **Language**: PHP 8.2+
- **Database**: MySQL 8.0+
- **Authentication**: Laravel Sanctum (Token-based)
- **ORM**: Eloquent
- **Validation**: Laravel Form Requests
- **Authorization**: Policy-based Access Control
- **Package Manager**: Composer
- **Testing**: PHPUnit
- **API**: RESTful with JSON responses

### Frontend (React Client)
- **Framework**: React 19.x
- **Language**: JavaScript (ES6+)
- **Styling**: CSS3 with CSS Variables + Tailwind CSS
- **HTTP Client**: Axios
- **Routing**: React Router DOM 6.x
- **State Management**: React Context + Hooks
- **Form Handling**: React Hook Form
- **Date Handling**: date-fns
- **Build Tool**: Create React App with React Scripts
- **Package Manager**: npm/yarn
- **Testing**: Testing Library (React/Jest/DOM)

### Database & Storage
- **Primary Database**: MySQL 8.0+
- **Session Storage**: Database-driven
- **Cache**: Application-level caching
- **File Storage**: Local filesystem
- **Migrations**: Laravel Migration System

### Security & Performance
- **Authentication**: JWT Token-based with Laravel Sanctum
- **Authorization**: Policy-based access control
- **CSRF Protection**: Built-in Laravel CSRF
- **SQL Injection Protection**: Eloquent ORM
- **Input Validation**: Multi-layer validation
- **Rate Limiting**: API rate limiting
- **CORS**: Configurable cross-origin requests

### Additional Features
- **Loading States**: Advanced loading spinners and overlays
- **Error Handling**: Comprehensive error notifications
- **Confirmations**: User-friendly confirmation dialogs
- **Responsive Design**: Mobile-first approach
- **Theme Support**: Light/Dark theme system

## Quick Start Installation

### Prerequisites
- **PHP 8.2+** with required extensions (mbstring, openssl, pdo, tokenizer, xml, ctype, json, bcmath)
- **Composer** 2.0+
- **Node.js 18.x+** and npm/yarn
- **MySQL 8.0+** or MariaDB 10.3+
- **Git** for version control

---

## Backend Setup (Laravel API)

### 1. Clone & Navigate
```bash
git clone <repository-url>
cd Task_Management_App-main/task-app
```

### 2. Install PHP Dependencies
```bash
composer install
```

### 3. Environment Configuration
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Database Setup
**Create MySQL Database:**
```sql
CREATE DATABASE task_management_app;
```

**Configure Environment (`.env` file):**
```env
APP_NAME="Task Management App"
APP_ENV=local
APP_KEY=base64:generated-key-here
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=task_management_app
DB_USERNAME=root
DB_PASSWORD=your_password

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false

SANCTUM_STATEFUL_DOMAINS=localhost:3000
```

### 5. Database Migration & Seeding
```bash
# Run migrations to create tables
php artisan migrate

# Optional: Seed with sample data
php artisan db:seed
```

### 6. Start Backend Server
```bash
php artisan serve
```
 **Backend API:** `http://localhost:8000`  
 **API Documentation:** `http://localhost:8000/api/v1`

---

## Frontend Setup (React Client)

### 1. Navigate to Frontend Directory
```bash
cd ../task-app-frontend
```

### 2. Install Node Dependencies
```bash
npm install


### 3. Configure API Connection
Update `src/services/TaskService.js`:
```javascript
class TaskService {
    static baseURL = 'http://localhost:8000/api/v1';
    // ... rest of the configuration
}
```

### 4. Start Frontend Development Server
```bash
npm start

 **Frontend App:** `http://localhost:3000`

### 5. Build for Production (Optional)
```bash
npm run build

---

## Complete Development Workflow

### Terminal 1: Backend Server
```bash
cd task-app
php artisan serve
# Backend running on http://localhost:8000
```

### Terminal 2: Frontend Server  
```bash
cd task-app-frontend  
npm start
# Frontend running on http://localhost:3000
```

### Terminal 3: Development Commands (Optional)
```bash
# Backend commands
php artisan migrate:fresh --seed  # Reset database
php artisan test                  # Run tests
php artisan queue:work           # Process queues

# Frontend commands
npm test                         # Run tests
npm run build                   # Production build
npm run eject                   # Eject CRA config
```

##  API Endpoints Reference

1.	User registration
POST   /api/auth/register  

Request:
{
  "name": "John",
  "username": "john_doe",
  "email": "john@doe.com",
  "password": "john@123!",
  "password_confirmation": "john@123!"
}

Response:
{
  "success": true,
  "message": "User registered successfully",
  "data": {
    "user": {
      "id": 1,
      "name": "John Doe",
      "username": "john_doe",
      "email": "john@example.com",
      "created_at": "2025-10-14T10:30:45.000000Z"
    },
    "token": "1|abc123def456ghi789jkl012mno345pqr678stu901vwx234yz",
    "token_type": "Bearer"
  }
}

2.	User authentication
POST   /api/auth/login

Request:
{
"username": "Sanjeewa",
"password":"sanjeewa123"
}

Response:
{
  "success": true,
  "message": "Login successful",
  "data": {
    "user": {
      "id": 2,
      "name": "Sanjeewa",
      "username": "Sanjeewa",
      "email": "sanjeewa@example.com",
      "created_at": "2025-10-14T09:15:30.000000Z"
    },
    "token": "2|xyz789abc123def456ghi012jkl345mno678pqr901stu234vwx",
    "token_type": "Bearer"
  }
}

3.	Logout
POST  /api/auth/logout 

Response:
{
  "success": true,
  "message": "Logged out successfully"
}

4.	Update Profile
PATCH  /api/auth/update-profile 

Request:
{
  "name": "John Updated",
  "username": "john_updated",
  "email": "john.updated@example.com"
}

Response:
{
  "success": true,
  "message": "Profile updated successfully",
  "data": {
    "user": {
      "id": 1,
      "name": "John Updated",
      "username": "john_updated",
      "email": "john.updated@example.com",
      "created_at": "2025-10-14T10:30:45.000000Z"
    }
  }
}


5.	Get All Tasks
GET /api/tasks 

Response:
{
  "success": true,
  "message": "Tasks retrieved successfully",
  "data": {
    "tasks": [
      {
        "id": 1,
        "title": "Complete Project Documentation",
        "description": "Write comprehensive documentation for the project",
        "status": "Pending",
        "priority": "High",
        "due_date": "2025-10-20",
        "user_id": 1,
        "assigned_user_id": 2,
        "created_at": "2025-10-14T10:30:45.000000Z",
        "updated_at": "2025-10-14T10:30:45.000000Z",
        "user": {
          "id": 1,
          "name": "John Doe",
          "username": "john_doe"
        },
        "assigned_users": [
          {
            "id": 2,
            "name": "Jane Smith",
            "username": "jane_smith"
          }
        ]
      }
    ],
    "pagination": {
      "current_page": 1,
      "last_page": 5,
      "per_page": 10,
      "total": 45
    }
  }
}

6.	Create task
POST  /api/tasks

Request:
{
  "title": "Complete API Documentation",
  "description": "Create comprehensive API documentation with examples",
  "priority": "High",
  "due_date": "2025-10-25",
  "assigned_user_id": 3
}

Response:
{
  "success": true,
  "message": "Task created successfully",
  "data": {
    "task": {
      "id": 5,
      "title": "Complete API Documentation",
      "description": "Create comprehensive API documentation with examples",
      "status": "Pending",
      "priority": "High",
      "due_date": "2025-10-25",
      "user_id": 1,
      "assigned_user_id": 3,
      "created_at": "2025-10-14T11:45:30.000000Z",
      "updated_at": "2025-10-14T11:45:30.000000Z"
    }
  }
}


7.	Update Task
PUT /api/tasks/{task_id} 

Request:
{
  "title": "Updated Task Title",
  "description": "Updated description",
  "priority": "Medium",
  "status": "In Progress",
  "due_date": "2025-10-28",
  "assigned_user_id": 4
}

8.	Delete Task
DELETE /api/tasks/{task_id}

Response:
{
  "success": true,
  "message": "Task deleted successfully"
}



9.	Update Task Status
PATCH  /api/tasks/{task_id}/status

Request:
{
  "status": "Completed"
}

Response :
{
  "success": true,
  "message": "Task marked as completed",
  "data": {
    "task": {
      "id": 1,
      "status": "Completed",
      "completed_at": "2025-10-14T15:30:00.000000Z"
    }
  }
}

10.	Update Task Priority
PATCH  /api/tasks/{task_id}/priority

Request:
{
  "priority": "Low"
}

11.	Assign User to Task
POST /api/tasks/{task_id}/assign

Request:
{
  "username": "demo" 
  }

Response:
{
    "success": true,
    "message": "User assigned to task successfully",
    "data": {
        "task": {
            "id": 9,
            "title": "Implement notification system",
            "description": "Add email + in-app notifications for task status changes. Cover create/update/assign events.",
            "due_date": "2025-10-20T00:00:00.000000Z",
            "status": "Pending",
            "user_id": 3,
            "created_at": "2025-10-10T10:39:36.000000Z",
            "updated_at": "2025-10-13T14:30:04.000000Z",
            "assigned_user_id": null,
            "priority": "Medium",
            "assigned_users": [
                {
                    "id": 5,
                    "name": "Admin User",
                    "email": "admin@example.com",
                    "email_verified_at": null,
                    "created_at": "2025-10-10T11:08:05.000000Z",
                    "updated_at": "2025-10-10T11:08:05.000000Z",
                    "username": "admin",
                    "pivot": {
                        "task_id": 9,
                        "user_id": 5,
                        "created_at": "2025-10-10T11:18:32.000000Z",
                        "updated_at": "2025-10-10T11:18:32.000000Z"
                    }
                },
                {
                    "id": 6,
                    "name": "Demo User",
                    "email": "demo@example.com",
                    "email_verified_at": null,
                    "created_at": "2025-10-10T11:08:05.000000Z",
                    "updated_at": "2025-10-10T11:08:05.000000Z",
                    "username": "demo",
                    "pivot": {
                        "task_id": 9,
                        "user_id": 6,
                        "created_at": "2025-10-10T11:56:12.000000Z",
                        "updated_at": "2025-10-10T11:56:12.000000Z"
                    }
                }
            ],
            "assigned_user": null
        }
    }
}


12.	Add Comment to Task
POST /api/tasks/{task_id}/comments

Request:
{
  "comment": "This is a comment on the task progress"
}
 Response:
{
  "success": true,
  "message": "Comment added successfully",
  "data": {
    "comment": {
      "id": 5,
      "comment": "This is a comment on the task progress",
      "task_id": 1,
      "user_id": 1,
      "created_at": "2025-10-14T16:30:00.000000Z",
      "updated_at": "2025-10-14T16:30:00.000000Z"
    }
  }

13.	Postpone Task
POST /tasks/{task_id}/postpone

Request:
{
  "new_due_date": "2025-10-30",
  "reason": "Need more time for research"
}

Response:
{
  "success": true,
  "message": "Task postponed successfully",
  "data": {
    "postponement": {
      "id": 1,
      "task_id": 1,
      "original_due_date": "2025-10-20",
      "new_due_date": "2025-10-30",
      "reason": "Need more time for research",
      "user_id": 1,
      "created_at": "2025-10-14T17:00:00.000000Z"
    }
  }
}

14.	Filtering 
GET /api/tasks?status=in progress

15.	Pagination
GET /api/tasks?page=1

16.	Sorting
GET /api/tasks?sort=priority&order=asc

**##File Attachment Endpoints**
Get task attachments
| `GET` | `/tasks/{task}/attachments` |
Upload file to task
| `POST` | `/tasks/{task}/attachments` |
Download attachment
| `GET` | `/tasks/{task}/attachments/{attachment}/download` |
Delete attachment
| `DELETE` | `/tasks/{task}/attachments/{attachment}` |

## File Handling & Attachments System

The Task Management App includes a comprehensive file attachment system that allows users to upload, download, and manage files associated with tasks. This system implements secure file handling with proper authorization and audit trails.

### Security Features

#### File Upload Security
- **File Type Validation**: Only allowed file extensions accepted
- **Size Limitations**: Configurable maximum file size limits
- **Virus Scanning**: Integration ready for antivirus scanning
- **Secure Storage**: Files stored outside web root
- **Unique Naming**: UUID-based file naming to prevent conflicts

#### Authorization & Access Control
- **Upload Permissions**: Only task assignees/owners can upload
- **Download Permissions**: Access controlled via secure middleware  
- **Delete Permissions**: Only uploader or task owner can delete
- **Audit Logging**: All file operations logged for security

### File Handling Best Practices

#### Upload Guidelines
- **File Size Limits**: Recommend 10MB maximum per file
- **Batch Uploads**: Support multiple files in single request
- **Progress Indicators**: Show upload progress to users
- **Error Handling**: Clear error messages for failed uploads
- **File Validation**: Client and server-side validation

#### Storage Management  
- **Regular Cleanup**: Remove orphaned files periodically
- **Backup Strategy**: Include file attachments in backups
- **Disk Space Monitoring**: Alert when storage approaches limits
- **Archive Policy**: Define retention periods for attachments

#### Security Recommendations
- **Regular Audits**: Review file access logs regularly  
- **Antivirus Integration**: Scan uploaded files for malware
- **Access Logging**: Log all file operations for compliance
- **Secure URLs**: Use temporary signed URLs for downloads
- **File Encryption**: Consider encrypting sensitive files at rest

### Troubleshooting File Issues

#### Common Problems & Solutions

**1. Upload Failures**
```bash
# Check file permissions
chmod -R 775 storage/app/
chown -R www-data:www-data storage/

# Verify PHP upload limits
php -i | grep upload_max_filesize
php -i | grep post_max_size
```

**2. Download Issues**
```bash
# Check secure download middleware
php artisan route:list | grep download

# Verify file exists
php artisan tinker
>>> Storage::exists('task-attachments/filename.pdf')
```

**3. Permission Errors**
```bash
# Check file ownership
ls -la storage/app/task-attachments/

# Fix permissions
sudo chown -R www-data:www-data storage/
sud  
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


### API Authentication Examples

#### Login & Get Token
```bash
# Login with username/email
curl -X POST http://localhost:8000/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "login": "your_username", 
    "password": "your_password"
  }'

# Response
{
  "success": true,
  "message": "Login successful",
  "data": {
    "user": { "id": 1, "username": "john_doe", "email": "john@example.com" },
    "token": "1|abcdef123456...",
    "token_type": "Bearer"
  }

```

#### Authenticated API Requests
```bash
# Get user tasks
curl -X GET http://localhost:8000/api/v1/tasks \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Accept: application/json"

# Create new task
curl -X POST http://localhost:8000/api/v1/tasks \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "title": "Complete project documentation",
    "description": "Write comprehensive README and API docs",
    "priority": "High",
    "due_date": "2025-10-15",
    "assigned_users": [2, 3, 4]
  }'
```



## Features Overview

### Authentication System
- Secure JWT token-based authentication
- Username/email login support
- Protected API routes with middleware
- Session management

### Task Management
- Full CRUD operations
- Advanced filtering and search
- Priority and status management  
- Due date tracking with overdue alerts
- Task postponement with history

### Collaboration
- Multi-user task assignment
- Real-time comment system
- User search and autocomplete
- Assignment notifications

### Modern UI/UX
- Responsive design (mobile, tablet, desktop)
- Professional loading states
- Error handling with notifications
- Confirmation dialogs
- Theme system support
- Smooth animations and transitions

### Dashboard & Analytics
- Task statistics and overview
- High priority task alerts
- Due today notifications
- Progress tracking

## Performance Considerations

- **Database Indexing**: Proper indexes on frequently queried columns
- **API Caching**: Response caching for dashboard statistics
- **Lazy Loading**: Components loaded on demand
- **Optimized Queries**: Eager loading to prevent N+1 queries
- **Frontend Optimization**: Code splitting and asset optimization

## Security Features

- **CSRF Protection**: Built-in Laravel CSRF protection
- **SQL Injection Prevention**: Eloquent ORM protection
- **Authentication**: Secure token-based authentication
- **Authorization**: Policy-based access control
- **Input Validation**: Comprehensive request validation

## Browser Compatibility

- Chrome 90+
- Firefox 90+
- Safari 14+
- Edge 90+
- Mobile browsers (iOS Safari, Chrome Mobile)

## Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Support

For support and questions:
- Create an issue on GitHub
- Contact the development team
- Check the troubleshooting section above



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


---

## Development & Deployment

### Testing (Automated tests & CI)

This project uses Laravel's PHPUnit test runner for backend tests and the usual Node test tools for the frontend. Below is a focused testing plan and practical guidance so you can implement the requested automated tests (15–20+ cases) and wire them into CI.

1) Objectives
 - Core Functional Testing
   - Task CRUD: create, update, delete and mark complete. Assert DB state and HTTP responses.
   - Task Assignment: assign/unassign users and verify access control for assignees vs non-assignees.
   - Task Postpone: change due dates, store postpone reasons, and assert the history exists.
   - Priority Levels: ensure High/Medium/Low are persisted and returned by API.
   - Comments: add, fetch, update, and delete comments for tasks.

 - User & Role Testing
   - Authentication: login/logout, token/session behavior and protected routes.
   - Role-based access: Admin, Manager, User — ensure correct permissions and 403/404 responses where appropriate.

 - File Handling Tests
   - Upload valid and invalid files (types/sizes) using Laravel's UploadedFile::fake(). Assert storage and DB records.
   - Delete attachments and assert permission checks and file removal from storage.

 - Notification Tests
   - Use Notification::fake() to assert notifications are sent when: task assigned, task postponed, comment added.

2) Test structure & best practices
 - Organize tests under `tests/Feature` and `tests/Unit`.
 - Use `RefreshDatabase` (or `RefreshDatabase` + in-memory sqlite for speed) to isolate tests:
   ```php
   use Illuminate\\Foundation\\Testing\\RefreshDatabase;

   class Feature\\TaskTest extends TestCase
   {
       use RefreshDatabase;
       // tests here
   }
   ```
 - Naming convention examples:
   - `tests/Feature/TaskTest.php` (task CRUD/assignment/postpone)
   - `tests/Feature/UserRoleTest.php` (auth and role permissions)
   - `tests/Feature/FileAttachmentTest.php` (upload/delete)
   - `tests/Feature/NotificationTest.php` (notifications)
 - Prefer readable, single-purpose tests. Aim for ~15–20 focused cases covering happy paths and important edge cases.

3) Useful Laravel testing helpers (examples)
 - Faking Storage & Uploaded files
   ```php
   use Illuminate\\Http\\UploadedFile;
   use Illuminate\\Support\\Facades\\Storage;
   
   Storage::fake('local');
   $file = UploadedFile::fake()->create('document.pdf', 100); // size in KB
   $response = $this->postJson(route('tasks.attachments.store', $task->id), [
       'files' => [$file]
   ]);
   Storage::disk('local')->assertExists('task-attachments/'.$file->hashName());
   $this->assertDatabaseHas('task_attachments', ['original_name' => 'document.pdf']);
   ```

 - Faking Notifications
   ```php
   use Illuminate\\Support\\Facades\\Notification;

   Notification::fake();
   // perform action that triggers notification
   Notification::assertSentTo($user, TaskAssignedNotification::class);
   ```

 - Asserting authorization and HTTP codes
   ```php
   $this->actingAs($unauthorizedUser)
       ->deleteJson(route('tasks.destroy', $task->id))
       ->assertStatus(403);
   ```

4) Example test cases (suggested list — expand to reach 15–20):
 - TaskTest
   1. Create task (assert 201 and DB record)
   2. Update task (assert 200 and changed fields)
   3. Delete task (assert 204 and missing in DB)
   4. Complete task (assert status changed)
   5. Assign users to task (assert assignees table entries)
   6. Unassign user (assert removed)
   7. Postpone task (assert due_date changed and postpone reason stored)
   8. Priority set & returned correctly
   9. Add comment and fetch comments

 - UserRoleTest
   10. Protected route returns 401 when unauthenticated
   11. User without admin role receives 403 for admin-only endpoints
   12. Admin can delete user (assert 200/204)

 - FileAttachmentTest
   13. Upload allowed file type and assert storage + DB
   14. Upload disallowed type and assert 422 validation error
   15. Delete file as owner (assert file removed and DB entry deleted)

 - NotificationTest
   16. Notification is sent on assignment
   17. Notification is sent on postpone
   18. Notification is sent on comment

5) Running tests locally
 - Run all backend tests (PowerShell):
   ```powershell
   cd task-app
   php artisan test
   ```
 - Run a single test class or method:
   ```powershell
   php artisan test --filter=TaskTest
   php artisan test --filter=testCreateTask
   ```

6) CI / integration notes
 - Add a CI workflow (GitHub Actions, GitLab CI) that:
   - Installs PHP, Composer and Node
   - Runs `composer install --no-interaction --prefer-dist`
   - Prepares database (sqlite in-memory or MySQL service)
   - Runs migrations and seeds (if needed)
   - Executes `php artisan test --parallel` (or plain `php artisan test`)
 - Cache composer and npm where appropriate for speed.

7) Next steps & where to implement
 - Implement test skeletons in `tests/Feature` with the naming shown above.
 - Use `Storage::fake()`, `Notification::fake()` and `Mail::fake()` for isolated assertions.
 - Add factories (in `database/factories/`) for tasks, users, attachments and comments to keep tests concise.

If you'd like, I can also scaffold the 15–20 test files and a basic GitHub Actions workflow in this repo — tell me "scaffold tests" and I'll create the test skeleton files next.

#### Backend Testing (Laravel)
```bash
# Run all tests
php artisan test

# Run specific test file
php artisan test tests/Feature/TaskApiTest.php

# Run with coverage
php artisan test --coverage

# Run tests with detailed output
php artisan test --verbose
```

#### Frontend Testing (React)
```bash
# Run all tests
npm test

# Run tests in watch mode
npm test -- --watch

# Run tests with coverage
npm test -- --coverage

# Run specific test file
npm test TaskManager.test.js
```

### Production Deployment

#### Backend Deployment Steps
```bash
# 1. Clone repository
git clone <repository-url>
cd task-app

# 2. Install dependencies
composer install --optimize-autoloader --no-dev

# 3. Configure environment
cp .env.example .env
php artisan key:generate

# 4. Setup database
php artisan migrate --force
php artisan db:seed --force

# 5. Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# 6. Set permissions
chmod -R 755 storage bootstrap/cache
```

#### Frontend Production Build
```bash
# Build for production
npm run build

# Serve with static server (example)
npm install -g serve
serve -s build -l 3000
```

### Development Commands

#### Laravel Artisan Commands
```bash
# Database operations
php artisan migrate:fresh --seed    # Reset database with sample data
php artisan migrate:rollback        # Rollback last migration
php artisan db:seed                 # Run seeders only

# Cache operations
php artisan cache:clear             # Clear application cache
php artisan config:clear            # Clear config cache
php artisan route:clear             # Clear route cache
php artisan view:clear              # Clear view cache

# Queue operations
php artisan queue:work              # Process job queue
php artisan queue:retry all         # Retry failed jobs

# Development utilities
php artisan tinker                  # Interactive shell
php artisan make:controller         # Create controller
php artisan make:model              # Create model
php artisan make:migration          # Create migration
```

#### React Development Commands
```bash
# Development
npm start                           # Start development server
npm run build                       # Production build
npm test                           # Run test suite
npm run eject                      # Eject from CRA (irreversible)

# Code quality
npm run lint                       # Run ESLint
npm run format                     # Format code with Prettier
```

---

## Performance & Optimization

### Backend Optimizations
- **Database Indexing**: Foreign keys and frequently queried columns
- **Eager Loading**: Prevent N+1 query problems
- **Query Caching**: Cache frequent database queries
- **Response Caching**: Cache API responses for static data
- **Pagination**: Limit large dataset responses
- **Database Connections**: Optimized connection pooling

### Frontend Optimizations
- **Code Splitting**: Lazy load components
- **Bundle Optimization**: Webpack optimization
- **Image Optimization**: Compressed images and lazy loading
- **Caching Strategy**: Browser caching for static assets
- **Minification**: Compressed CSS and JavaScript
- **Tree Shaking**: Remove unused code

### Production Recommendations
- **HTTPS**: SSL certificate for secure communication
- **CDN**: Content delivery network for static assets
- **Load Balancing**: Multiple server instances
- **Database Optimization**: Query optimization and indexing
- **Monitoring**: Application performance monitoring
- **Backup Strategy**: Regular database backups

---

## Security Features

### Authentication & Authorization
- **JWT Tokens**: Secure token-based authentication
- **Password Hashing**: bcrypt password encryption
- **Rate Limiting**: API request rate limiting
- **CORS Configuration**: Cross-origin request handling
- **CSRF Protection**: Built-in Laravel CSRF protection

### Data Protection
- **Input Validation**: Multi-layer input validation
- **SQL Injection Prevention**: Eloquent ORM protection
- **XSS Protection**: Output escaping and sanitization
- **Mass Assignment Protection**: Eloquent fillable attributes
- **Policy Authorization**: Resource-based access control

  ## Exporting Reports & Export Feature

This application includes an export feature that lets you generate reports and download them in CSV, PDF, or JSON formats. Use the Web UI for ad-hoc exports, the API for automation/integration, or the Artisan CLI for scheduled/export tasks.

### Web UI (Interactive)
- Navigate to the Dashboard -> Reports (or click the "Reports" button in the sidebar).
- Choose a report type (e.g. "Tasks Summary", "User Activity", "Productivity").
- Use the filters (date range, users, status, priority) to narrow results.
- Select an output format from the "Format" dropdown: CSV, PDF, or JSON.
- Click "Export". A file will be generated and a download will begin automatically. Exports are also retained under `storage/app/exports/` for a limited time.

Tip: For large result sets prefer CSV or JSON to avoid long PDF generation times.

### API Usage (Programmatic)
Endpoint (example):

GET /api/v1/reports/tasks/summary

Query parameters:
- `format` — csv | pdf | json (default: json)
- `start` — start date (YYYY-MM-DD)
- `end` — end date (YYYY-MM-DD)
- filter params — any supported filters (user_id, status, priority)

Example curl (CSV):

```bash
curl -sS -X GET "http://localhost:8000/api/v1/reports/tasks/summary?format=csv&start=2025-01-01&end=2025-10-21" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: text/csv" -o tasks-summary-2025-01-01_to_2025-10-21.csv
```

Notes:
- For `format=pdf` the response will be a binary PDF stream (Content-Type: application/pdf).
- For `format=csv` the response will be a CSV file (Content-Type: text/csv; charset=utf-8) and usually includes a `Content-Disposition: attachment; filename="tasks-summary-YYYY-MM-DD.csv"` header.
- For `format=json` you'll receive a JSON object with the report data, suitable for further processing.

Sample JSON response (truncated):

```json
{
  "success": true,
  "report": "tasks_summary",
  "meta": { "start": "2025-01-01", "end": "2025-10-21", "count": 123 },
  "data": [
    { "task_id": 1, "title": "Draft README", "assigned_to": [2], "priority": "High", "status": "Completed" },
    { "task_id": 2, "title": "Design UI", "assigned_to": [3,4], "priority": "Medium", "status": "In Progress" }
  ]
}
```

### Artisan CLI (Scheduled / Server-side)
There is an Artisan command for server-side exports suitable for cron jobs or background tasks. Example usage:

```bash
php artisan reports:export tasks \ 
  --format=csv \ 
  --start=2025-01-01 \ 
  --end=2025-10-21 \ 
  --output=storage/app/exports/tasks-summary-2025-01-01_to_2025-10-21.csv
```

The command will write the exported file to `storage/app/exports/` (or the path you pass) and will print the created file path on success. You can then move, archive, or serve that file as needed.

### Supported Formats & Storage
- CSV — best for spreadsheets and bulk data processing
- JSON — best for integrations and programmatic consumption
- PDF — best for printable human-readable reports

Exports are stored temporarily in `storage/app/exports/`. Make sure the `storage` directory is writable and add regular cleanup (cron job) if you generate many files.

### Documentation & Advanced Options
For full report generation options (detailed filters, scheduled exports, and examples) see `HOW_TO_GENERATE_TASK_REPORTS.html` in the repository root.

### Troubleshooting
- If export fails with a permissions error, ensure `storage/app/exports` is writable by the webserver/CLI user.
- For timeouts on large PDF exports, increase PHP execution time or generate reports server-side via Artisan and stream to users.
- If the API returns `403`, verify your token has permission to access reports.

---

### License
This project is licensed under the **MIT License** - see the [LICENSE](LICENSE) file for details.

## Version Information

**Current Version**: 2.0.0  
**Last Updated**: October 13, 2025  
**Backend**: Laravel 12.x with PHP 8.2+  
**Frontend**: React 19.x with modern JavaScript  
**Database**: MySQL 8.0+  
