# PowerShell File Deletion Test
# This script tests file deletion via API

Write-Host "=== FILE DELETION TEST VIA API ===" -ForegroundColor Green
Write-Host ""

$baseUrl = "http://127.0.0.1:8000/api/v1"

# Step 1: Login
Write-Host "Step 1: Logging in..." -ForegroundColor Yellow
$loginData = @{
    email = "john@example.com"
    password = "password123"
} | ConvertTo-Json

try {
    $loginResponse = Invoke-RestMethod -Uri "$baseUrl/auth/login" -Method POST -Body $loginData -ContentType "application/json"
    $token = $loginResponse.data.token
    Write-Host "✅ Login successful! User: $($loginResponse.data.user.name)" -ForegroundColor Green
} catch {
    Write-Host "❌ Login failed: $($_.Exception.Message)" -ForegroundColor Red
    exit 1
}

# Step 2: Get tasks with attachments
Write-Host "Step 2: Getting tasks..." -ForegroundColor Yellow
$headers = @{
    "Authorization" = "Bearer $token"
    "Accept" = "application/json"
}

try {
    $tasksResponse = Invoke-RestMethod -Uri "$baseUrl/tasks" -Method GET -Headers $headers
    $tasks = $tasksResponse.data
    Write-Host "✅ Found $($tasks.Count) tasks" -ForegroundColor Green
    
    # Find a task with attachments
    $taskWithAttachments = $tasks | Where-Object { $_.attachments_count -gt 0 } | Select-Object -First 1
    
    if ($taskWithAttachments) {
        Write-Host "📎 Task '$($taskWithAttachments.title)' has $($taskWithAttachments.attachments_count) attachment(s)" -ForegroundColor Cyan
        $taskId = $taskWithAttachments.id
    } else {
        Write-Host "⚠️  No tasks with attachments found. Creating a task first..." -ForegroundColor Yellow
        
        # Create a test task
        $taskData = @{
            title = "File Deletion Test Task - $(Get-Date -Format 'yyyy-MM-dd HH:mm:ss')"
            description = "This task is created for testing file deletion functionality"
            priority = "medium"
            due_date = (Get-Date).AddDays(7).ToString("yyyy-MM-ddTHH:mm:ss")
        } | ConvertTo-Json
        
        $newTaskResponse = Invoke-RestMethod -Uri "$baseUrl/tasks" -Method POST -Body $taskData -Headers (@{
            "Authorization" = "Bearer $token"
            "Content-Type" = "application/json"
            "Accept" = "application/json"
        })
        
        $taskId = $newTaskResponse.data.task.id
        Write-Host "✅ Created new task (ID: $taskId)" -ForegroundColor Green
    }
} catch {
    Write-Host "❌ Failed to get tasks: $($_.Exception.Message)" -ForegroundColor Red
    exit 1
}

# Step 3: Get attachments for the task
Write-Host "Step 3: Getting attachments for task $taskId..." -ForegroundColor Yellow
try {
    $attachmentsResponse = Invoke-RestMethod -Uri "$baseUrl/tasks/$taskId/attachments" -Method GET -Headers $headers
    $attachments = $attachmentsResponse.data
    
    Write-Host "📎 Found $($attachments.Count) attachment(s)" -ForegroundColor Green
    
    if ($attachments.Count -eq 0) {
        Write-Host "⚠️  No attachments found for this task. Please upload a file first via the UI." -ForegroundColor Yellow
        Write-Host "UI URL: http://127.0.0.1:8000/test_deletion.html" -ForegroundColor Cyan
        exit 0
    }
    
    # Show attachments
    foreach ($attachment in $attachments) {
        Write-Host "- ID: $($attachment.id), Name: $($attachment.original_name), Size: $([math]::Round($attachment.file_size / 1024, 2)) KB" -ForegroundColor White
    }
    
} catch {
    Write-Host "❌ Failed to get attachments: $($_.Exception.Message)" -ForegroundColor Red
    exit 1
}

# Step 4: Delete the first attachment
if ($attachments.Count -gt 0) {
    $attachmentToDelete = $attachments[0]
    Write-Host ""
    Write-Host "Step 4: Deleting attachment '$($attachmentToDelete.original_name)' (ID: $($attachmentToDelete.id))..." -ForegroundColor Yellow
    
    # Ask for confirmation
    $confirmation = Read-Host "Are you sure you want to delete '$($attachmentToDelete.original_name)'? (y/N)"
    if ($confirmation -eq 'y' -or $confirmation -eq 'Y') {
        try {
            $deleteResponse = Invoke-RestMethod -Uri "$baseUrl/tasks/$taskId/attachments/$($attachmentToDelete.id)" -Method DELETE -Headers $headers
            
            if ($deleteResponse.success) {
                Write-Host "✅ File deleted successfully!" -ForegroundColor Green
                Write-Host "Message: $($deleteResponse.message)" -ForegroundColor White
            } else {
                Write-Host "❌ Deletion failed: $($deleteResponse.message)" -ForegroundColor Red
            }
        } catch {
            Write-Host "❌ API error during deletion: $($_.Exception.Message)" -ForegroundColor Red
        }
        
        # Step 5: Verify deletion
        Write-Host ""
        Write-Host "Step 5: Verifying deletion..." -ForegroundColor Yellow
        
        try {
            $verifyResponse = Invoke-RestMethod -Uri "$baseUrl/tasks/$taskId/attachments" -Method GET -Headers $headers
            $remainingAttachments = $verifyResponse.data
            
            Write-Host "📊 Attachments after deletion: $($remainingAttachments.Count)" -ForegroundColor Green
            
            $deletedAttachmentFound = $remainingAttachments | Where-Object { $_.id -eq $attachmentToDelete.id }
            if ($deletedAttachmentFound) {
                Write-Host "❌ FAILED: Deleted attachment still exists in database!" -ForegroundColor Red
            } else {
                Write-Host "✅ SUCCESS: Attachment removed from database" -ForegroundColor Green
            }
            
        } catch {
            Write-Host "❌ Failed to verify deletion: $($_.Exception.Message)" -ForegroundColor Red
        }
        
    } else {
        Write-Host "Deletion cancelled." -ForegroundColor Yellow
    }
}

Write-Host ""
Write-Host "=== TEST COMPLETE ===" -ForegroundColor Green
Write-Host "To verify file system deletion, run: php artisan check:attachments" -ForegroundColor Cyan