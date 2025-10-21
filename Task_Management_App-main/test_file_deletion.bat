@echo off
echo === FILE DELETION TEST - API Verification ===
echo.

REM Set API base URL
set BASE_URL=http://127.0.0.1:8000/api/v1

echo Step 1: Login to get authentication token...
curl -X POST %BASE_URL%/auth/login ^
  -H "Content-Type: application/json" ^
  -H "Accept: application/json" ^
  -d "{\"email\":\"john@example.com\",\"password\":\"password123\"}" ^
  -o login_response.json

echo.
echo Login Response:
type login_response.json
echo.

REM Extract token (simplified - in real scenario you'd parse JSON properly)
echo Step 2: Creating test task...
echo Note: You'll need to manually extract the token from login_response.json and use it in subsequent requests
echo.

echo Step 3: Check database for attachments before deletion...
cd task-app
php -r "
try {
    \$pdo = new PDO('mysql:host=127.0.0.1;dbname=task_management_db', 'root', '');
    \$stmt = \$pdo->query('SELECT COUNT(*) as count FROM task_attachments');
    \$result = \$stmt->fetch(PDO::FETCH_ASSOC);
    echo 'Attachments in database: ' . \$result['count'] . PHP_EOL;
    
    \$stmt = \$pdo->query('SELECT id, original_name, file_path FROM task_attachments LIMIT 5');
    while (\$row = \$stmt->fetch(PDO::FETCH_ASSOC)) {
        echo '- ID: ' . \$row['id'] . ', Name: ' . \$row['original_name'] . ', Path: ' . \$row['file_path'] . PHP_EOL;
    }
} catch (Exception \$e) {
    echo 'Database connection failed: ' . \$e->getMessage() . PHP_EOL;
}
"
cd ..

echo.
echo Step 4: Check file system for attachment files...
echo Files in storage/app/attachments:
dir task-app\storage\app\attachments 2>nul

echo.
echo === MANUAL TESTING REQUIRED ===
echo 1. Use the UI test at: http://127.0.0.1:8000/test_deletion.html
echo 2. Login with: john@example.com / password123
echo 3. Create a task and upload a file
echo 4. Note the file path and database record
echo 5. Delete the file using the UI
echo 6. Verify both database and file system are updated
echo.

pause