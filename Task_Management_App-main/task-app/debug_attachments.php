<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\TaskAttachment;

echo "Checking existing task attachments:\n";
echo "ID | Original Name | Uploaded By\n";
echo "--------------------------------\n";

$attachments = TaskAttachment::get(['id', 'original_name', 'uploaded_by']);

foreach ($attachments as $attachment) {
    $uploadedBy = $attachment->uploaded_by ?? 'NULL';
    echo "{$attachment->id} | {$attachment->original_name} | {$uploadedBy}\n";
}

echo "\nTotal attachments: " . $attachments->count() . "\n";