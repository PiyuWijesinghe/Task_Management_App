<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\TaskAttachment;
use Illuminate\Support\Facades\Storage;

class CheckAttachments extends Command
{
    protected $signature = 'check:attachments';
    protected $description = 'Check attachments in database and file system';

    public function handle()
    {
        $this->info('=== ATTACHMENT CHECK ===');
        $this->newLine();
        
        // Database check
        $attachments = TaskAttachment::with(['task'])->get();
        $this->info("📊 Database: {$attachments->count()} attachments found");
        $this->newLine();
        
        if ($attachments->count() > 0) {
            $this->info('Attachment details:');
            foreach ($attachments as $attachment) {
                $this->line("- ID: {$attachment->id}");
                $this->line("  Name: {$attachment->original_name}");
                $this->line("  Path: {$attachment->file_path}");
                $this->line("  Task: " . ($attachment->task ? $attachment->task->title : 'N/A'));
                $this->line("  Size: " . number_format($attachment->file_size / 1024, 2) . " KB");
                
                // Check if file exists
                $exists = Storage::exists($attachment->file_path);
                $this->line("  File exists: " . ($exists ? '✅ Yes' : '❌ No'));
                $this->newLine();
            }
        }
        
        // File system check
        $this->info('📁 File System Check:');
        $storagePath = storage_path('app/attachments');
        
        if (is_dir($storagePath)) {
            $files = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($storagePath, \RecursiveDirectoryIterator::SKIP_DOTS)
            );
            
            $fileCount = 0;
            foreach ($files as $file) {
                if ($file->isFile()) {
                    $fileCount++;
                    $this->line("- " . $file->getFilename() . " (" . number_format($file->getSize() / 1024, 2) . " KB)");
                }
            }
            $this->info("Total physical files: {$fileCount}");
        } else {
            $this->warn('Attachments directory does not exist yet.');
        }
        
        return 0;
    }
}