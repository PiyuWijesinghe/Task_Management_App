<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\TaskAttachment;
use App\Models\Task;
use App\Models\User;

echo "Fixing NULL uploaded_by values...\n";

// Get attachments with NULL uploaded_by
$nullAttachments = TaskAttachment::whereNull('uploaded_by')->with('task')->get();

echo "Found {$nullAttachments->count()} attachments with NULL uploaded_by\n";

foreach ($nullAttachments as $attachment) {
    $task = $attachment->task;
    if ($task) {
        // Set uploaded_by to task creator (fallback)
        $attachment->uploaded_by = $task->user_id;
        $attachment->save();
        
        echo "Fixed attachment {$attachment->id} ({$attachment->original_name}) - set uploaded_by to {$task->user_id} (task creator)\n";
    }
}

echo "Done!\n";