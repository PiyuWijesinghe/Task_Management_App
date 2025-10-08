# Task Management System - REST API Documentation

## Overview

This REST API provides comprehensive endpoints for managing tasks, users, comments, and postponements in the Task Management System. The API uses Laravel Sanctum for authentication and follows REST conventions.

## Base URL
```
http://localhost:8000/api/v1
```

## Authentication

The API uses Laravel Sanctum for token-based authentication. Most endpoints require authentication via Bearer token.

### Headers Required
```
Accept: application/json
Content-Type: application/json
Authorization: Bearer {your-token}  // For protected routes
```

## Response Format

All API responses follow a consistent format:

### Success Response
```json
{
    "success": true,
    "message": "Operation successful",
    "data": {
        // Response data here
    }
}
```

### Error Response
```json
{
    "success": false,
    "message": "Error description",
    "errors": {
        // Validation errors (if applicable)
    }
}
```

## Authentication Endpoints

### Register User
```
POST /auth/register
```

**Request Body:**
```json
{
    "name": "John Doe",
    "username": "johndoe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123"
}
```

**Response:**
```json
{
    "success": true,
    "message": "User registered successfully",
    "data": {
        "user": {
            "id": 1,
            "name": "John Doe",
            "username": "johndoe",
            "email": "john@example.com",
            "created_at": "2025-10-07T10:30:00.000000Z"
        },
        "token": "1|abcdef...",
        "token_type": "Bearer"
    }
}
```

### Login User
```
POST /auth/login
```

**Request Body:**
```json
{
    "login": "johndoe",  // Can be username or email
    "password": "password123"
}
```

### Get Authenticated User
```
GET /auth/user
```
*Requires Authentication*

### Logout
```
POST /auth/logout
```
*Requires Authentication*

### Logout from All Devices
```
POST /auth/logout-all
```
*Requires Authentication*

### Update Profile
```
PATCH /auth/update-profile
```
*Requires Authentication*

**Request Body:**
```json
{
    "name": "John Smith",
    "username": "johnsmith",
    "email": "johnsmith@example.com"
}
```

### Change Password
```
POST /auth/change-password
```
*Requires Authentication*

**Request Body:**
```json
{
    "current_password": "oldpassword",
    "new_password": "newpassword123",
    "new_password_confirmation": "newpassword123"
}
```

## Task Management Endpoints

### Get Tasks (Enhanced Listing with Pagination, Sorting & Filtering)
```
GET /tasks
```
*Requires Authentication*

**Query Parameters (all optional):**
- `page`: Page number (default: 1)
- `per_page`: Items per page (default: 15, max: 100)
- `sort_by`: One of `due_date`, `priority`, `priority_order`, `created_at`, `title`, `status` (default: `due_date`)
- `sort_dir`: `asc` or `desc` (default: `asc`)
- `status`: `Pending`, `In Progress`, `Completed`
- `priority`: `High`, `Medium`, `Low`
- `assigned_user_id`: Filter tasks where the user is primary or additional assignee
- `creator_id`: Filter tasks created by a specific user
- `due_from`: Start of due date range (YYYY-MM-DD)
- `due_to`: End of due date range (YYYY-MM-DD)
- `created_from`: Start of creation date range (YYYY-MM-DD)
- `created_to`: End of creation date range (YYYY-MM-DD)
- `q`: Search term applied to title & description (partial match)

**Notes:**
- `priority_order` is an alias for sorting by priority weight (High → Medium → Low).
- When sorting by `priority` descending, order reverses the weighted list.
- Secondary ordering by `created_at desc` is automatically applied (except when `sort_by=created_at`).

**Example Requests:**
```
GET /tasks?status=Pending&priority=High&sort_by=priority&sort_dir=asc
GET /tasks?q=report&due_from=2025-10-01&due_to=2025-10-31&per_page=50
GET /tasks?assigned_user_id=7&sort_by=created_at&sort_dir=desc
```

**Successful Response:**
```json
{
    "success": true,
    "data": {
        "tasks": [
            {
                "id": 42,
                "title": "Prepare sprint report",
                "description": "Compile metrics for sprint 12",
                "due_date": "2025-10-15",
                "status": "Pending",
                "priority": "High",
                "user_id": 3,
                "assigned_user_id": 7,
                "created_at": "2025-10-08T09:15:24.000000Z",
                "updated_at": "2025-10-08T09:15:24.000000Z",
                "assigned_user": { /* eager-loaded primary assignee */ },
                "assigned_users": [ /* additional assignees */ ],
                "user": { /* creator */ }
            }
        ],
        "meta": {
            "current_page": 1,
            "last_page": 3,
            "per_page": 15,
            "total": 32,
            "from": 1,
            "to": 15,
            "sort_by": "due_date",
            "sort_dir": "asc",
            "filters": {
                "status": "Pending",
                "priority": "High",
                "q": "report"
            }
        }
    }
}
```

### Create Task
```
POST /tasks
```
*Requires Authentication*

**Request Body:**
```json
{
    "title": "New Task",
    "description": "Task description",
    "due_date": "2025-10-15",
    "priority": "High",
    "assigned_user_id": 2,
    "assigned_users": [2, 3, 4]
}
```

### Get Specific Task
```
GET /tasks/{id}
```
*Requires Authentication*

### Update Task
```
PUT /tasks/{id}
```
*Requires Authentication*

**Request Body:**
```json
{
    "title": "Updated task title",
    "description": "Updated description",
    "due_date": "2025-10-20",
    "priority": "Medium",
    "status": "In Progress"
}
```

### Delete Task
```
DELETE /tasks/{id}
```
*Requires Authentication*

### Update Task Status
```
PATCH /tasks/{id}/status
```
*Requires Authentication*

**Request Body:**
```json
{
    "status": "Completed"
}
```

### Mark Task as Completed
```
PATCH /tasks/{id}/complete
```
*Requires Authentication*

### Update Task Priority
```
PATCH /tasks/{id}/priority
```
*Requires Authentication*

**Request Body:**
```json
{
    "priority": "High"
}
```

### Assign User to Task
```
POST /tasks/{id}/assign
```
*Requires Authentication*

**Request Body:**
```json
{
    "user_id": 2,
    "type": "primary"  // or "additional"
}
```

### Unassign User from Task
```
DELETE /tasks/{id}/unassign/{user_id}
```
*Requires Authentication*

### Get Task Assignees
```
GET /tasks/{id}/assignees
```
*Requires Authentication*

### Get Dashboard Data
```
GET /tasks/dashboard
```
*Requires Authentication*

**Response:**
```json
{
    "success": true,
    "data": {
        "statistics": {
            "pending": 12,
            "in_progress": 5,
            "completed": 23,
            "overdue": 3,
            "due_today": 2,
            "total": 40
        }
    }
}
```

### Get Overdue Tasks
```
GET /tasks/overdue
```
*Requires Authentication*

### Get Tasks Due Today
```
GET /tasks/due-today
```
*Requires Authentication*

### Search Tasks
```
GET /search/tasks?query={search_term}&limit={limit}
```
*Requires Authentication*

## Task Comments Endpoints

### Get Task Comments
```
GET /tasks/{task_id}/comments
```
*Requires Authentication*

**Query Parameters:**
- `limit` (optional): 1-100, default 20
- `page` (optional): Page number
- `order` (optional): `asc`, `desc`

### Add Comment to Task
```
POST /tasks/{task_id}/comments
```
*Requires Authentication*

**Request Body:**
```json
{
    "comment": "This is a comment on the task"
}
```

### Update Comment
```
PUT /tasks/{task_id}/comments/{comment_id}
```
*Requires Authentication*

**Request Body:**
```json
{
    "comment": "Updated comment text"
}
```

### Delete Comment
```
DELETE /tasks/{task_id}/comments/{comment_id}
```
*Requires Authentication*

### Get Specific Comment
```
GET /comments/{comment_id}
```
*Requires Authentication*

## Task Postponement Endpoints

### Postpone Task
```
POST /tasks/{task_id}/postpone
```
*Requires Authentication*

**Request Body:**
```json
{
    "new_due_date": "2025-10-25",
    "reason": "Need more time to complete research"
}
```

### Get Task Postponement History
```
GET /tasks/{task_id}/postponements
```
*Requires Authentication*

### Get User's Postponements
```
GET /postponements
```
*Requires Authentication*

### Get Specific Postponement
```
GET /postponements/{postponement_id}
```
*Requires Authentication*

## User Management Endpoints

### Get All Users
```
GET /users
```
*Requires Authentication*

**Query Parameters:**
- `limit` (optional): 1-100, default 15
- `page` (optional): Page number
- `search` (optional): Search term

### Get Specific User
```
GET /users/{user_id}
```
*Requires Authentication*

### Search Users
```
GET /users/search?query={search_term}&limit={limit}&exclude_current={true/false}
```
*Requires Authentication*

### Get User Activity
```
GET /reports/user/activity
```
*Requires Authentication*

**Response:**
```json
{
    "success": true,
    "data": {
        "user_activity": {
            "tasks_created": 15,
            "tasks_assigned": 8,
            "tasks_completed": 12,
            "tasks_pending": 6,
            "tasks_in_progress": 5,
            "tasks_overdue": 2,
            "recent_tasks_created": 3,
            "recent_comments": 7
        }
    }
}
```

## Bulk Operations

### Bulk Complete Tasks
```
POST /bulk/tasks/complete
```
*Requires Authentication*

**Request Body:**
```json
{
    "task_ids": [1, 2, 3, 4]
}
```

### Bulk Delete Tasks
```
POST /bulk/tasks/delete
```
*Requires Authentication*

**Request Body:**
```json
{
    "task_ids": [1, 2, 3, 4]
}
```

### Bulk Assign Tasks
```
POST /bulk/tasks/assign
```
*Requires Authentication*

**Request Body:**
```json
{
    "task_ids": [1, 2, 3, 4],
    "user_id": 2
}
```

### Bulk Update Priority
```
POST /bulk/tasks/update-priority
```
*Requires Authentication*

**Request Body:**
```json
{
    "task_ids": [1, 2, 3, 4],
    "priority": "High"
}
```

## Error Codes

| Code | Description |
|------|-------------|
| 200 | Success |
| 201 | Created |
| 400 | Bad Request |
| 401 | Unauthorized |
| 403 | Forbidden |
| 404 | Not Found |
| 422 | Validation Error |
| 500 | Internal Server Error |

## Rate Limiting

The API implements rate limiting to prevent abuse:
- 60 requests per minute per IP for unauthenticated requests
- 100 requests per minute per user for authenticated requests

## Examples

### Complete API Usage Flow

1. **Register a new user:**
```bash
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
```

2. **Login and get token:**
```bash
curl -X POST http://localhost:8000/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "login": "johndoe",
    "password": "password123"
  }'
```

3. **Create a task (using token from login):**
```bash
curl -X POST http://localhost:8000/api/v1/tasks \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -d '{
    "title": "Complete API Documentation",
    "description": "Write comprehensive API documentation",
    "due_date": "2025-10-15",
    "priority": "High"
  }'
```

4. **Get all tasks:**
```bash
curl -X GET "http://localhost:8000/api/v1/tasks?status=Pending&priority=High" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

5. **Add comment to task:**
```bash
curl -X POST http://localhost:8000/api/v1/tasks/1/comments \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -d '{
    "comment": "Started working on this task"
  }'
```

## Notes

- All dates should be in YYYY-MM-DD format
- All timestamps are in ISO 8601 format with UTC timezone
- The API supports JSON only (no form-data)
- File uploads are not currently supported through the API
- All endpoints return JSON responses
- Authentication tokens expire based on Sanctum configuration (default: never, but can be configured)

## Testing

You can test the API using tools like:
- Postman
- curl
- HTTPie
- Insomnia

A Postman collection can be created based on this documentation for easier testing.