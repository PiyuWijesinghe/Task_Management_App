<?php

require_once 'task-app/bootstrap/app.php';

$app = app();

try {
    echo "=== LARAVEL DATABASE TEST ===\n\n";
    
    // Test database connection
    $pdo = DB::connection()->getPdo();
    echo "✓ Database connection successful\n\n";
    
    // Count attachments
    $attachmentCount = DB::table('task_attachments')->count();
    echo "📊 Total attachments in database: {$attachmentCount}\n\n";
    
    if ($attachmentCount > 0) {
        echo "Recent attachments:\n";
        $attachments = DB::table('task_attachments')
            ->join('tasks', 'task_attachments.task_id', '=', 'tasks.id')
            ->join('users', 'task_attachments.uploaded_by', '=', 'users.id')
            ->select('task_attachments.*', 'tasks.title as task_title', 'users.name as uploaded_by_name')
            ->orderBy('task_attachments.created_at', 'desc')
            ->limit(5)
            ->get();
            
        foreach ($attachments as $attachment) {
            echo "- ID: {$attachment->id}, Name: {$attachment->original_name}\n";
            echo "  Task: {$attachment->task_title}\n";
            echo "  Path: {$attachment->file_path}\n";
            echo "  Size: " . number_format($attachment->file_size / 1024, 2) . " KB\n\n";
        }
    }
    
    // Check file system
    echo "📁 FILE SYSTEM CHECK:\n";
    echo "====================\n";
    
    $storagePath = storage_path('app/attachments');
    echo "Storage path: {$storagePath}\n";
    
    if (is_dir($storagePath)) {
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($storagePath, RecursiveDirectoryIterator::SKIP_DOTS)
        );
        
        $fileCount = 0;
        foreach ($files as $file) {
            if ($file->isFile()) {
                $fileCount++;
                echo "- " . $file->getFilename() . " (" . number_format($file->getSize() / 1024, 2) . " KB)\n";
            }
        }
        echo "Total physical files: {$fileCount}\n\n";
    } else {
        echo "Attachments directory doesn't exist yet.\n\n";
    }
    
    echo "✅ Verification complete!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}