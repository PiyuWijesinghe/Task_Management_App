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

**Base URL:** `http://localhost:8000/api/v1`

###  Authentication Endpoints
| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| `POST` | `/auth/register` | User registration | ❌ |
| `POST` | `/auth/login` | User authentication | ❌ |
| `POST` | `/auth/forgot-password` | Password reset request | ❌ |
| `POST` | `/auth/reset-password` | Password reset | ❌ |
| `GET` | `/auth/user` | Get authenticated user | ✅ |
| `POST` | `/auth/logout` | Single session logout | ✅ |
| `POST` | `/auth/logout-all` | All sessions logout | ✅ |
| `PATCH` | `/auth/update-profile` | Update user profile | ✅ |
| `POST` | `/auth/change-password` | Change password | ✅ |

###  User Management Endpoints
| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| `GET` | `/users` | List all users | ✅ |
| `GET` | `/users/search` | Search users | ✅ |
| `GET` | `/users/{user}` | Get specific user | ✅ |
| `PUT` | `/users/{user}` | Update user (admin) | ✅ |
| `DELETE` | `/users/{user}` | Delete user (admin) | ✅ |

### Task Management Endpoints
| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| `GET` | `/tasks` | List user tasks with filters | ✅ |
| `POST` | `/tasks` | Create new task | ✅ |
| `GET` | `/tasks/{task}` | Get specific task | ✅ |
| `PUT` | `/tasks/{task}` | Update task | ✅ |
| `DELETE` | `/tasks/{task}` | Delete task | ✅ |
| `PATCH` | `/tasks/{task}/status` | Update task status | ✅ |
| `PATCH` | `/tasks/{task}/complete` | Mark as completed | ✅ |
| `PATCH` | `/tasks/{task}/priority` | Update priority | ✅ |

### Task Assignment Endpoints
| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| `POST` | `/tasks/{task}/assign` | Assign user to task | ✅ |
| `DELETE` | `/tasks/{task}/unassign/{user}` | Unassign user | ✅ |
| `GET` | `/tasks/{task}/assignees` | Get task assignees | ✅ |

### Comment Management Endpoints
| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| `GET` | `/tasks/{task}/comments` | Get task comments | ✅ |
| `POST` | `/tasks/{task}/comments` | Add comment | ✅ |
| `PUT` | `/comments/{comment}` | Update comment | ✅ |
| `DELETE` | `/comments/{comment}` | Delete comment | ✅ |
| `GET` | `/comments/{comment}` | Get specific comment | ✅ |

### Task Operations & Analytics
| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| `POST` | `/tasks/{task}/postpone` | Postpone task | ✅ |
| `GET` | `/tasks/{task}/postponements` | Postponement history | ✅ |
| `GET` | `/tasks/dashboard` | Dashboard data | ✅ |
| `GET` | `/tasks/statistics` | Task statistics | ✅ |
| `GET` | `/tasks/overdue` | Overdue tasks | ✅ |
| `GET` | `/tasks/due-today` | Tasks due today | ✅ |
| `GET` | `/tasks/postponed` | Postponed tasks | ✅ |

### Search & Bulk Operations
| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| `GET` | `/search/tasks` | Search tasks | ✅ |
| `GET` | `/search/users` | Search users | ✅ |
| `POST` | `/bulk/tasks/complete` | Complete multiple tasks | ✅ |
| `POST` | `/bulk/tasks/delete` | Delete multiple tasks | ✅ |
| `POST` | `/bulk/tasks/assign` | Assign multiple tasks | ✅ |
| `POST` | `/bulk/tasks/update-priority` | Update priority bulk | ✅ |

### Reports & Analytics
| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| `GET` | `/reports/tasks/summary` | Task summary report | ✅ |
| `GET` | `/reports/user/activity` | User activity report | ✅ |
| `GET` | `/reports/productivity` | Productivity metrics | ✅ |

### 📁 File Attachment Endpoints
| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| `GET` | `/tasks/{task}/attachments` | Get task attachments | ✅ |
| `POST` | `/tasks/{task}/attachments` | Upload file to task | ✅ |
| `GET` | `/tasks/{task}/attachments/{attachment}/download` | Download attachment | ✅ |
| `DELETE` | `/tasks/{task}/attachments/{attachment}` | Delete attachment | ✅ |

---

## 📁 File Handling & Attachments System

The Task Management App includes a comprehensive file attachment system that allows users to upload, download, and manage files associated with tasks. This system implements secure file handling with proper authorization and audit trails.

### 🔧 File System Configuration

#### Backend Configuration (Laravel)
**File Storage Setup (`config/filesystems.php`):**
```php
'disks' => [
    'local' => [
        'driver' => 'local',
        'root' => storage_path('app'),
        'permissions' => [
            'file' => [
                'public' => 0644,
                'private' => 0600,
            ],
            'dir' => [
                'public' => 0755,
                'private' => 0700,
            ],
        ],
    ],
],
```

**Storage Directories:**
```
storage/
├── app/
│   ├── public/              # Public files (if needed)
│   ├── private/             # Private secure files
│   │   └── task-attachments/ # Secure task attachments
│   └── task-attachments/    # General attachments
└── logs/                    # Application logs
```

#### Environment Configuration
Add to your `.env` file:
```env
FILESYSTEM_DISK=local
MAX_UPLOAD_SIZE=10240        # Maximum file size in KB (10MB)
ALLOWED_FILE_TYPES=jpg,jpeg,png,pdf,doc,docx,txt,zip,rar
SECURE_DOWNLOADS=true        # Enable secure download middleware
```

### 🔒 Security Features

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

### 📤 File Upload Implementation

#### API Usage
**Upload Files to Task:**
```bash
curl -X POST http://localhost:8000/api/v1/tasks/1/attachments \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: multipart/form-data" \
  -F "files[]=@/path/to/file1.pdf" \
  -F "files[]=@/path/to/file2.jpg"
```

**Response:**
```json
{
  "success": true,
  "message": "Files uploaded successfully",
  "data": {
    "attachments": [
      {
        "id": 1,
        "original_name": "document.pdf",
        "file_name": "uuid-generated-name.pdf",
        "file_path": "task-attachments/uuid-generated-name.pdf",
        "mime_type": "application/pdf",
        "file_size": 1024576,
        "uploaded_by": 1,
        "created_at": "2025-10-16T10:30:00Z"
      }
    ]
  }
}
```

#### Frontend Implementation (React)
**File Upload Component:**
```javascript
// FileUpload.js
const uploadFiles = async (taskId, files) => {
  const formData = new FormData();
  
  Array.from(files).forEach(file => {
    formData.append('files[]', file);
  });

  try {
    const response = await TaskService.uploadTaskAttachment(taskId, formData);
    console.log('Files uploaded:', response.data.attachments);
    return response.data.attachments;
  } catch (error) {
    console.error('Upload failed:', error);
    throw error;
  }
};
```

**Drag & Drop Interface:**
```javascript
// Drag and drop file upload
const onDrop = useCallback((acceptedFiles) => {
  const validFiles = acceptedFiles.filter(file => {
    const isValidType = ALLOWED_TYPES.includes(file.type);
    const isValidSize = file.size <= MAX_FILE_SIZE;
    return isValidType && isValidSize;
  });
  
  if (validFiles.length > 0) {
    uploadFiles(taskId, validFiles);
  }
}, [taskId]);
```

### 📥 File Download Implementation

#### Secure Download System
**API Endpoint:**
```bash
GET /api/v1/tasks/{task}/attachments/{attachment}/download
Authorization: Bearer YOUR_TOKEN
```

**Security Middleware (`SecureDownloadMiddleware`):**
```php
public function handle($request, Closure $next)
{
    $attachment = $request->route('attachment');
    $user = $request->user();
    
    // Verify user has access to this task/attachment
    if (!$this->canDownload($user, $attachment)) {
        abort(403, 'Unauthorized access to attachment');
    }
    
    // Log download for audit trail
    Log::info('File downloaded', [
        'user_id' => $user->id,
        'attachment_id' => $attachment->id,
        'file_name' => $attachment->original_name
    ]);
    
    return $next($request);
}
```

#### Frontend Download Implementation
```javascript
// Download attachment with progress tracking
const downloadAttachment = async (taskId, attachmentId, fileName) => {
  try {
    const response = await fetch(
      `${API_BASE_URL}/tasks/${taskId}/attachments/${attachmentId}/download`,
      {
        headers: {
          'Authorization': `Bearer ${authToken}`,
        },
      }
    );
    
    if (!response.ok) throw new Error('Download failed');
    
    const blob = await response.blob();
    const url = window.URL.createObjectURL(blob);
    
    // Create download link
    const link = document.createElement('a');
    link.href = url;
    link.download = fileName;
    link.click();
    
    // Cleanup
    window.URL.revokeObjectURL(url);
  } catch (error) {
    console.error('Download error:', error);
  }
};
```

### 🗑️ File Deletion System

#### API Implementation
**Controller Logic (`TaskApiController`):**
```php
public function deleteAttachment(Request $request, Task $task, TaskAttachment $attachment)
{
    // Verify attachment belongs to task
    if ($attachment->task_id !== $task->id) {
        return response()->json([
            'success' => false,
            'message' => 'Attachment not found'
        ], 404);
    }

    // Authorization check
    $this->authorize('delete', $attachment);

    try {
        // Delete physical file
        if (Storage::exists($attachment->file_path)) {
            Storage::delete($attachment->file_path);
        }

        // Log deletion for audit
        Log::info('Attachment deleted', [
            'user_id' => $request->user()->id,
            'task_id' => $task->id,
            'attachment_id' => $attachment->id,
            'file_name' => $attachment->original_name,
        ]);

        // Delete database record
        $attachment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Attachment deleted successfully'
        ]);
    } catch (Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to delete attachment',
            'error' => $e->getMessage()
        ], 500);
    }
}
```

#### Frontend Deletion with Confirmation
```javascript
// Delete attachment with user confirmation
const handleDelete = async (attachment) => {
  const confirmed = window.confirm(
    `Are you sure you want to delete "${attachment.original_name}"?`
  );
  
  if (!confirmed) return;
  
  try {
    await TaskService.deleteTaskAttachment(taskId, attachment.id);
    
    // Update UI - remove from attachments list
    setAttachments(prev => 
      prev.filter(att => att.id !== attachment.id)
    );
    
    showSuccessMessage('File deleted successfully');
  } catch (error) {
    showErrorMessage('Failed to delete file');
  }
};
```

### 🔍 File Verification & Testing

#### Testing File Operations
**Test File Deletion:**
```bash
# Check current attachments
cd task-app
php artisan check:attachments

# Open test interface
# Visit: http://localhost:8000/test_deletion.html
# 1. Login with test credentials
# 2. Navigate to task with attachments  
# 3. Upload test files
# 4. Delete files using UI
# 5. Verify deletion in database and file system

# Verify deletion results
php artisan check:attachments
```

**Database Verification Command:**
```php
// Custom Artisan Command: CheckAttachments
php artisan check:attachments

// Output:
// === ATTACHMENT CHECK ===
// 📊 Database: 3 attachments found
// 📁 File System: 3 files found
// ✅ All database records have corresponding files
```

### 📋 File Handling Best Practices

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

### 🛠️ Troubleshooting File Issues

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
sudo chmod -R 775 storage/
```

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

### Testing

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

---

### License
This project is licensed under the **MIT License** - see the [LICENSE](LICENSE) file for details.

## Version Information

**Current Version**: 2.0.0  
**Last Updated**: October 13, 2025  
**Backend**: Laravel 12.x with PHP 8.2+  
**Frontend**: React 19.x with modern JavaScript  
**Database**: MySQL 8.0+  
