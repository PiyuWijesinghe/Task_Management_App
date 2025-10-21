<?php
/**
 * Quick Security Verification Script
 * Run with: php verify_security.php
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "🔐 SECURE DOWNLOAD VERIFICATION\n";
echo "================================\n\n";

// 1. Check if policies are registered
echo "1. Checking Policy Registration...\n";
$gate = app('Illuminate\Contracts\Auth\Access\Gate');
$policies = $gate->policies();

if (isset($policies['App\Models\TaskAttachment'])) {
    echo "   ✅ TaskAttachmentPolicy: REGISTERED\n";
} else {
    echo "   ❌ TaskAttachmentPolicy: NOT FOUND\n";
}

if (isset($policies['App\Models\Task'])) {
    echo "   ✅ TaskPolicy: REGISTERED\n";
} else {
    echo "   ❌ TaskPolicy: NOT FOUND\n";
}

// 2. Check if middleware exists
echo "\n2. Checking Middleware...\n";
if (class_exists('App\Http\Middleware\SecureDownloadMiddleware')) {
    echo "   ✅ SecureDownloadMiddleware: EXISTS\n";
} else {
    echo "   ❌ SecureDownloadMiddleware: NOT FOUND\n";
}

// 3. Check cache configuration
echo "\n3. Checking Cache Configuration...\n";
$cacheDriver = config('cache.default');
echo "   📊 Cache Driver: $cacheDriver\n";

if (in_array($cacheDriver, ['redis', 'memcached', 'database'])) {
    echo "   ✅ Cache: SUITABLE for rate limiting\n";
} else {
    echo "   ⚠️  Cache: Using '$cacheDriver' - consider Redis/Memcached for production\n";
}

// 4. Check file storage configuration
echo "\n4. Checking Storage Configuration...\n";
$defaultDisk = config('filesystems.default');
echo "   📁 Default Disk: $defaultDisk\n";

if (config("filesystems.disks.$defaultDisk")) {
    echo "   ✅ Storage: CONFIGURED\n";
} else {
    echo "   ❌ Storage: NOT PROPERLY CONFIGURED\n";
}

// 5. Check if test files exist
echo "\n5. Checking Test Files...\n";
if (file_exists(__DIR__ . '/tests/Feature/SecureDownloadTest.php')) {
    echo "   ✅ Security Tests: AVAILABLE\n";
} else {
    echo "   ❌ Security Tests: NOT FOUND\n";
}

// 6. Sample policy test (if users exist)
echo "\n6. Testing Policy Logic...\n";
try {
    $userCount = \App\Models\User::count();
    $taskCount = \App\Models\Task::count();
    
    if ($userCount > 0 && $taskCount > 0) {
        echo "   📊 Database: $userCount users, $taskCount tasks\n";
        echo "   ✅ Ready for policy testing\n";
        
        // Test basic policy instantiation
        $policy = new \App\Policies\TaskAttachmentPolicy();
        echo "   ✅ Policy: Can be instantiated\n";
    } else {
        echo "   ⚠️  Database: No test data available\n";
        echo "   💡 Run seeders to test with real data\n";
    }
} catch (Exception $e) {
    echo "   ⚠️  Database: " . $e->getMessage() . "\n";
}

// 7. Route verification
echo "\n7. Checking Routes...\n";
$routes = collect(\Illuminate\Support\Facades\Route::getRoutes())->filter(function ($route) {
    return str_contains($route->uri(), 'download');
});

if ($routes->count() > 0) {
    echo "   ✅ Download Routes: FOUND ({$routes->count()} routes)\n";
    foreach ($routes as $route) {
        $middleware = implode(', ', $route->gatherMiddleware());
        echo "   📍 {$route->uri()} - Middleware: [$middleware]\n";
    }
} else {
    echo "   ❌ Download Routes: NOT FOUND\n";
}

echo "\n🎯 VERIFICATION COMPLETE!\n\n";

// Quick test commands
echo "💡 NEXT STEPS - Run these commands to test:\n";
echo "==========================================\n";
echo "php artisan test tests/Feature/SecureDownloadTest.php\n";
echo "php artisan tinker\n";
echo "curl -H 'Authorization: Bearer TOKEN' 'http://localhost:8000/api/v1/tasks/1/attachments/1/download'\n";
echo "\n";