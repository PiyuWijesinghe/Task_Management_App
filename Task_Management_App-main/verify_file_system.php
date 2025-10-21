<?php

/**
 * Database and File System Verification for File Deletion
 */

echo "=== FILE DELETION VERIFICATION ===\n\n";

try {
    // Database connection
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=task_management_db', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✓ Database connection established\n\n";
    
    // Check attachments in database
    echo "📊 DATABASE STATUS:\n";
    echo "==================\n";
    
    $stmt = $pdo->query('SELECT COUNT(*) as count FROM task_attachments');
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "Total attachments in database: {$result['count']}\n\n";
    
    if ($result['count'] > 0) {
        echo "Attachment details:\n";
        $stmt = $pdo->query('
            SELECT ta.id, ta.original_name, ta.file_path, ta.file_size, ta.created_at,
                   t.title as task_title, u.name as uploaded_by_name
            FROM task_attachments ta
            LEFT JOIN tasks t ON ta.task_id = t.id  
            LEFT JOIN users u ON ta.uploaded_by = u.id
            ORDER BY ta.created_at DESC
            LIMIT 10
        ');
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "- ID: {$row['id']}\n";
            echo "  Name: {$row['original_name']}\n";
            echo "  Path: {$row['file_path']}\n";
            echo "  Size: " . number_format($row['file_size'] / 1024, 2) . " KB\n";
            echo "  Task: {$row['task_title']}\n";
            echo "  Uploaded by: {$row['uploaded_by_name']}\n";
            echo "  Created: {$row['created_at']}\n\n";
        }
    }
    
    // Check file system
    echo "📁 FILE SYSTEM STATUS:\n";
    echo "======================\n";
    
    $attachmentsDir = __DIR__ . '/task-app/storage/app/attachments';
    
    if (is_dir($attachmentsDir)) {
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($attachmentsDir, RecursiveDirectoryIterator::SKIP_DOTS)
        );
        
        $fileCount = 0;
        $totalSize = 0;
        
        echo "Files in storage/app/attachments:\n";
        foreach ($files as $file) {
            if ($file->isFile()) {
                $fileCount++;
                $totalSize += $file->getSize();
                $relativePath = str_replace(__DIR__ . '/task-app/storage/app/', '', $file->getPathname());
                echo "- {$relativePath} (" . number_format($file->getSize() / 1024, 2) . " KB)\n";
            }
        }
        
        echo "\nTotal files in storage: {$fileCount}\n";
        echo "Total storage size: " . number_format($totalSize / 1024, 2) . " KB\n\n";
    } else {
        echo "Attachments directory does not exist: {$attachmentsDir}\n\n";
    }
    
    // Cross-reference check
    echo "🔍 CONSISTENCY CHECK:\n";
    echo "====================\n";
    
    $stmt = $pdo->query('SELECT file_path FROM task_attachments');
    $dbFiles = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    $missingFiles = [];
    $existingFiles = [];
    
    foreach ($dbFiles as $filePath) {
        $fullPath = __DIR__ . '/task-app/storage/app/' . $filePath;
        if (file_exists($fullPath)) {
            $existingFiles[] = $filePath;
        } else {
            $missingFiles[] = $filePath;
        }
    }
    
    echo "Files in database that exist in file system: " . count($existingFiles) . "\n";
    echo "Files in database missing from file system: " . count($missingFiles) . "\n";
    
    if (!empty($missingFiles)) {
        echo "\nMissing files:\n";
        foreach ($missingFiles as $file) {
            echo "- {$file}\n";
        }
    }
    
    echo "\n";
    
    if (count($missingFiles) === 0) {
        echo "✅ CONSISTENCY CHECK PASSED: All database records have corresponding files\n";
    } else {
        echo "⚠️  CONSISTENCY CHECK WARNING: Some files are missing from file system\n";
    }
    
    echo "\n=== VERIFICATION COMPLETE ===\n";
    
} catch (PDOException $e) {
    echo "❌ Database error: " . $e->getMessage() . "\n";
    echo "Make sure MySQL is running and the database exists.\n";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}