<?php

require_once 'vendor/autoload.php';

// Bootstrap the Laravel application
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    // Check if attachments table exists
    $tables = DB::select("SELECT name FROM sqlite_master WHERE type='table'");
    $tableNames = array_column($tables, 'name');
    
    echo "All tables in database:\n";
    foreach ($tableNames as $table) {
        echo "- " . $table . "\n";
    }
    
    if (in_array('task_attachments', $tableNames)) {
        echo "\n✅ task_attachments table EXISTS!\n";
        
        // Get table structure
        $columns = DB::select("PRAGMA table_info(task_attachments)");
        echo "\nTable structure:\n";
        foreach ($columns as $column) {
            echo "- {$column->name} ({$column->type})" . ($column->notnull ? " NOT NULL" : "") . "\n";
        }
        
        // Count records
        $count = DB::table('task_attachments')->count();
        echo "\nTotal records in task_attachments: {$count}\n";
        
    } else {
        echo "\n❌ task_attachments table does NOT exist!\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}