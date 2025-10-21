# API Testing Script

## Testing the Core Endpoints

### 1. Register a User (Test Authentication)
```bash
curl -X POST http://localhost:8000/api/v1/auth/register \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "name": "Test User",
    "username": "testuser",
    "email": "test@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'
```

### 2. Login (Get Token)
```bash
curl -X POST http://localhost:8000/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "login": "testuser",
    "password": "password123"
  }'
```

### 3. GET /tasks (List tasks with filters)
```bash
curl -X GET "http://localhost:8000/api/v1/tasks?status=Pending&priority=High" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

### 4. POST /tasks (Create task)
```bash
curl -X POST http://localhost:8000/api/v1/tasks \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -d '{
    "title": "Test Task via API",
    "description": "This task was created via API",
    "due_date": "2025-10-15",
    "priority": "High"
  }'
```

### 5. PUT /tasks/{id} (Update task)
```bash
curl -X PUT http://localhost:8000/api/v1/tasks/1 \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -d '{
    "title": "Updated Task Title",
    "status": "In Progress",
    "priority": "Medium"
  }'
```

### 6. DELETE /tasks/{id} (Delete task)
```bash
curl -X DELETE http://localhost:8000/api/v1/tasks/1 \
  -H "Accept: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

### 7. POST /tasks/{id}/postpone
```bash
curl -X POST http://localhost:8000/api/v1/tasks/1/postpone \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -d '{
    "new_due_date": "2025-10-25",
    "reason": "Need more time for research"
  }'
```

### 8. POST /tasks/{id}/comment
```bash
curl -X POST http://localhost:8000/api/v1/tasks/1/comments \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -d '{
    "comment": "This is a comment added via API"
  }'
```

### 9. POST /tasks/{id}/assign
```bash
curl -X POST http://localhost:8000/api/v1/tasks/1/assign \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -d '{
    "user_id": 2,
    "type": "primary"
  }'
```