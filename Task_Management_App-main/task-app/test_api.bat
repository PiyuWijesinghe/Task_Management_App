@echo off
echo ====================================
echo   TASK MANAGEMENT API TESTING
echo ====================================
echo.

echo [1/8] Testing User Registration...
curl -X POST http://localhost:8000/api/v1/auth/register ^
  -H "Content-Type: application/json" ^
  -H "Accept: application/json" ^
  -d "{\"name\":\"API Test User\",\"username\":\"apitest3\",\"email\":\"apitest3@example.com\",\"password\":\"password123\",\"password_confirmation\":\"password123\"}"

echo.
echo.
echo [2/8] Testing User Login...
curl -X POST http://localhost:8000/api/v1/auth/login ^
  -H "Content-Type: application/json" ^
  -H "Accept: application/json" ^
  -d "{\"login\":\"apitest3\",\"password\":\"password123\"}" > login_response.txt

echo Token saved to login_response.txt
echo.

echo [3/8] Testing GET /tasks (list tasks)...
curl -X GET "http://localhost:8000/api/v1/tasks" ^
  -H "Accept: application/json" ^
  -H "Authorization: Bearer 2|TOKEN_PLACEHOLDER"

echo.
echo.
echo [4/8] Testing POST /tasks (create task)...
curl -X POST http://localhost:8000/api/v1/tasks ^
  -H "Content-Type: application/json" ^
  -H "Accept: application/json" ^
  -H "Authorization: Bearer 2|TOKEN_PLACEHOLDER" ^
  -d "{\"title\":\"API Test Task\",\"description\":\"Created via batch script\",\"due_date\":\"2025-10-15\",\"priority\":\"High\"}"

echo.
echo.
echo [5/8] Testing PUT /tasks/1 (update task)...
curl -X PUT http://localhost:8000/api/v1/tasks/1 ^
  -H "Content-Type: application/json" ^
  -H "Accept: application/json" ^
  -H "Authorization: Bearer 2|TOKEN_PLACEHOLDER" ^
  -d "{\"title\":\"Updated Task Title\",\"status\":\"In Progress\"}"

echo.
echo.
echo [6/8] Testing POST /tasks/1/postpone...
curl -X POST http://localhost:8000/api/v1/tasks/1/postpone ^
  -H "Content-Type: application/json" ^
  -H "Accept: application/json" ^
  -H "Authorization: Bearer 2|TOKEN_PLACEHOLDER" ^
  -d "{\"new_due_date\":\"2025-10-25\",\"reason\":\"Testing postponement via API\"}"

echo.
echo.
echo [7/8] Testing POST /tasks/1/comments (add comment)...
curl -X POST http://localhost:8000/api/v1/tasks/1/comments ^
  -H "Content-Type: application/json" ^
  -H "Accept: application/json" ^
  -H "Authorization: Bearer 2|TOKEN_PLACEHOLDER" ^
  -d "{\"comment\":\"This comment was added via API test\"}"

echo.
echo.
echo [8/8] Testing POST /tasks/1/assign...
curl -X POST http://localhost:8000/api/v1/tasks/1/assign ^
  -H "Content-Type: application/json" ^
  -H "Accept: application/json" ^
  -H "Authorization: Bearer 2|TOKEN_PLACEHOLDER" ^
  -d "{\"user_id\":1,\"type\":\"additional\"}"

echo.
echo.
echo ====================================
echo   ALL API TESTS COMPLETED!
echo ====================================

pause