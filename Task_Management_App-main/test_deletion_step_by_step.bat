@echo off
echo === TESTING FILE DELETION - STEP BY STEP ===
echo.

echo Current state BEFORE deletion:
echo ==============================
cd task-app
php artisan check:attachments
cd ..
echo.

echo Files in storage directories:
echo =============================
echo Files in storage\app\task-attachments:
dir task-app\storage\app\task-attachments 2>nul || echo "Directory not found or empty"
echo.
echo Files in storage\app\private\task-attachments:  
dir task-app\storage\app\private\task-attachments 2>nul || echo "Directory not found or empty"
echo.

echo === MANUAL DELETION TEST ===
echo Please perform the following steps:
echo.
echo 1. Open browser: http://127.0.0.1:8000/test_deletion.html
echo 2. Login with: john@example.com / password123  
echo 3. Go to an existing task OR create a new task
echo 4. If no attachments exist, upload a test file first
echo 5. Click the RED "Delete" button next to any attachment
echo 6. Confirm the deletion in the popup dialog
echo 7. Verify the attachment disappears from the list
echo.

pause
echo.

echo Current state AFTER deletion:
echo ==============================
cd task-app  
php artisan check:attachments
cd ..
echo.

echo Files in storage directories AFTER deletion:
echo ==============================================
echo Files in storage\app\task-attachments:
dir task-app\storage\app\task-attachments 2>nul || echo "Directory not found or empty"
echo.
echo Files in storage\app\private\task-attachments:
dir task-app\storage\app\private\task-attachments 2>nul || echo "Directory not found or empty"  
echo.

echo === TEST COMPLETE ===
echo Compare the BEFORE and AFTER results above.
echo.
echo Expected results for a successful deletion:
echo - Database count should decrease by 1
echo - Deleted attachment should not appear in database list
echo - Physical file should be removed from storage directory
echo - UI should no longer show the deleted attachment
echo.

pause