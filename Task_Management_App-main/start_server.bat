@echo off
title Laravel Task Management Server

echo.
echo ================================================
echo    Laravel Task Management App Server
echo ================================================
echo.
echo Starting server...
echo.

cd /d "%~dp0task-app"

if not exist "artisan" (
    echo Error: artisan file not found in task-app directory
    echo Please ensure you are running this from the correct location
    pause
    exit /b 1
)

echo Server starting on http://127.0.0.1:8000
echo.
echo ================================================
echo   Server is now running! Open your browser to:
echo   http://127.0.0.1:8000
echo.
echo   Navigate to Reports section after login to
echo   generate your Task Reports with PDF export!
echo ================================================
echo.
echo Press Ctrl+C to stop the server
echo.

php artisan serve --host=127.0.0.1 --port=8000

pause