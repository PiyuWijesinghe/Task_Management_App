# Task File Management System Test Guide

## Features Implemented

### For Task Creators (user_id === task.user_id):
✅ **Can view all uploaded files**
✅ **Can download all uploaded files**  
✅ **Can upload new files**
✅ **Can delete uploaded files**

### For Assigned Users (assigned_user_id or in assignedUsers relationship):
✅ **Can view all uploaded files**
✅ **Can download all uploaded files**
❌ **Cannot upload new files**
❌ **Cannot delete uploaded files**

## How to Test

### Backend API Testing

1. **Create a task with a file attachment:**
```bash
POST /api/v1/tasks
Content-Type: multipart/form-data
Authorization: Bearer {creator_token}

{
    "title": "Task with Attachments",
    "description": "Test task for file sharing",
    "attachments": [file1.pdf, file2.jpg]
}
```

2. **Assign the task to another user:**
```bash
POST /api/v1/tasks/{task_id}/assign
Authorization: Bearer {creator_token}

{
    "user_id": {assignee_user_id}
}
```

3. **View attachments as assigned user:**
```bash
GET /api/v1/tasks/{task_id}/attachments
Authorization: Bearer {assignee_token}
```

4. **Download attachment as assigned user:**
```bash
GET /api/v1/tasks/{task_id}/attachments/{attachment_id}/download
Authorization: Bearer {assignee_token}
```

5. **Try to upload as assigned user (should work but UI will hide upload button):**
```bash
POST /api/v1/tasks/{task_id}/attachments
Authorization: Bearer {assignee_token}
Content-Type: multipart/form-data

{
    "attachment": file3.pdf
}
```

### Frontend UI Testing

1. **Login as task creator**
2. **Create a new task with file attachments**
3. **Assign the task to another user**
4. **Verify that as creator you can:**
   - See all attachments
   - Download all attachments
   - Upload new attachments (Add Files button visible)
   - Delete attachments (Delete button visible)

5. **Login as assigned user**
6. **View the assigned task**
7. **Verify that as assignee you can:**
   - See all attachments uploaded by creator
   - Download all attachments
   - See "View & Download Only" indicator
   - NOT see "Add Files" button
   - NOT see delete buttons on attachments

## File Permission Logic

### Backend (Laravel)
- `TaskPolicy::view()` - Allows creator, assigned_user, and many-to-many assignees to view tasks
- `userHasAccessToTask()` method in TaskApiController validates access for all attachment operations
- File download uses Laravel's `Storage::download()` with authorization check

### Frontend (React)
- `AttachmentsList` component receives `canEdit` prop
- `canEdit = getCurrentUser()?.id === task.user_id` (only creator can edit)
- Upload/delete buttons only shown when `canEdit === true`
- Download button always shown (if user has task access)

## Security Features

✅ **Authorization checks on all API endpoints**
✅ **File access restricted to task participants only**  
✅ **User can only edit attachments on tasks they created**
✅ **File downloads use secure Laravel storage system**
✅ **Frontend UI prevents unauthorized actions**

## API Endpoints Used

- `GET /api/v1/tasks/{task}/attachments` - List attachments
- `POST /api/v1/tasks/{task}/attachments` - Upload attachment  
- `GET /api/v1/tasks/{task}/attachments/{attachment}/download` - Download file
- `DELETE /api/v1/tasks/{task}/attachments/{attachment}` - Delete attachment

All endpoints require authentication and validate user access to the task.