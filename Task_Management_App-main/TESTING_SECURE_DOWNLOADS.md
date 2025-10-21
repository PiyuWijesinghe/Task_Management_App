# How to Check and Verify Secure Downloads Implementation

## 🔍 Verification Methods

### 1. **Route Verification**
Check if routes are properly configured with middleware:

```bash
# Check download routes with middleware
php artisan route:list --name="download" -v

# Check all attachment routes
php artisan route:list --path=api/v1/tasks --grep=attachment

# Verify middleware is registered
php artisan route:list | grep "secure.download"
```

### 2. **Policy Testing**
Test authorization in Tinker:

```bash
php artisan tinker
```

```php
// Create test data
$user1 = User::first();
$user2 = User::skip(1)->first();
$task = Task::first();
$attachment = TaskAttachment::first();

// Test authorization
Gate::forUser($user1)->allows('download', $attachment);
Gate::forUser($user2)->denies('download', $attachment);

// Check policy methods
$user1->can('download', $attachment);
$user1->can('delete', $attachment);
```

### 3. **API Testing with Postman/cURL**

#### Get Authentication Token
```bash
curl -X POST http://localhost:8000/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "test@example.com",
    "password": "password"
  }'
```

#### Test Authorized Download
```bash
curl -X GET "http://localhost:8000/api/v1/tasks/1/attachments/1/download" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Accept: application/json"
```

#### Test Unauthorized Access
```bash
# Without token (should fail)
curl -X GET "http://localhost:8000/api/v1/tasks/1/attachments/1/download"

# With wrong user token (should fail with 403)
curl -X GET "http://localhost:8000/api/v1/tasks/1/attachments/1/download" \
  -H "Authorization: Bearer WRONG_USER_TOKEN"
```

### 4. **Rate Limiting Testing**

#### Test User Rate Limits
```bash
# Send 60 requests rapidly (should hit limit at 50)
for i in {1..60}; do
  curl -X GET "http://localhost:8000/api/v1/tasks/1/attachments/1/download" \
    -H "Authorization: Bearer YOUR_TOKEN" \
    -w "Request $i: %{http_code}\n" -o /dev/null -s
  sleep 0.1
done
```

#### Check Rate Limit Response
```json
{
  "success": false,
  "message": "Too many downloads. Please try again later."
}
```

### 5. **Database Testing**
Check if policies work with different user relationships:

```sql
-- Create test scenario
INSERT INTO tasks (title, user_id, assigned_user_id) VALUES 
('Test Task', 1, 2);

INSERT INTO task_user (task_id, user_id) VALUES 
(1, 3), (1, 4);

INSERT INTO task_attachments (task_id, original_name, stored_name, file_path, uploaded_by) VALUES 
(1, 'test.pdf', 'uuid.pdf', 'task-attachments/uuid.pdf', 1);

-- Test with different users
-- User 1 (creator): Should have access ✅
-- User 2 (assigned): Should have access ✅  
-- User 3,4 (team): Should have access ✅
-- User 5 (random): Should NOT have access ❌
```

### 6. **Log Monitoring**
Check security logs in real-time:

```bash
# Monitor Laravel logs
tail -f storage/logs/laravel.log | grep -i "attachment\|download\|suspicious"

# Filter specific log types
tail -f storage/logs/laravel.log | grep "Download rate limit"
tail -f storage/logs/laravel.log | grep "Suspicious download"
```

### 7. **Automated Testing**
Run the feature tests:

```bash
# Run all tests
php artisan test

# Run specific security tests
php artisan test tests/Feature/SecureDownloadTest.php

# Run with detailed output
php artisan test --verbose tests/Feature/SecureDownloadTest.php
```

### 8. **Manual Browser Testing**

#### Test Web Interface
1. Login as task creator
2. Navigate to task with attachments
3. Try to download - should work ✅
4. Login as different user
5. Try same download - should fail ❌

#### Test Console Errors
Open browser DevTools and check for:
- 403 Forbidden responses
- Rate limit 429 responses
- Network request patterns

### 9. **Security Audit Checklist**

#### ✅ Authorization Tests
```bash
# Test 1: Task creator can download
# Test 2: Assigned user can download  
# Test 3: Team member can download
# Test 4: Random user cannot download
# Test 5: Unauthenticated request fails
```

#### ✅ Rate Limiting Tests
```bash
# Test 6: User rate limit (50/min) works
# Test 7: IP rate limit (100/min) works
# Test 8: Rate limits reset after time window
```

#### ✅ File Security Tests
```bash
# Test 9: Direct file URL access blocked
# Test 10: Invalid attachment ID handled
# Test 11: Missing file handled gracefully
# Test 12: Cross-task attachment access blocked
```

### 10. **Performance Testing**

#### Load Testing Script
```php
<?php
// load_test.php
use GuzzleHttp\Client;
use GuzzleHttp\Promise;

$client = new Client();
$token = 'YOUR_AUTH_TOKEN';
$promises = [];

// Send 100 concurrent requests
for ($i = 0; $i < 100; $i++) {
    $promises[$i] = $client->getAsync('http://localhost:8000/api/v1/tasks/1/attachments/1/download', [
        'headers' => ['Authorization' => "Bearer $token"]
    ]);
}

$responses = Promise\settle($promises)->wait();

// Analyze results
$success = 0;
$rateLimited = 0;

foreach ($responses as $response) {
    if ($response['state'] === 'fulfilled') {
        $code = $response['value']->getStatusCode();
        if ($code === 200) $success++;
        if ($code === 429) $rateLimited++;
    }
}

echo "Successful: $success\n";
echo "Rate Limited: $rateLimited\n";
```

### 11. **Middleware Verification**
Check middleware is working:

```php
// In controller, add temporary logging
Log::info('Download request received', [
    'middleware_executed' => true,
    'user_id' => auth()->id(),
    'ip' => request()->ip()
]);
```

### 12. **Cache Inspection**
Check rate limiting cache:

```bash
php artisan tinker
```

```php
// Check rate limit counters
Cache::get('download_limit_user_1');
Cache::get('download_limit_ip_127.0.0.1');
Cache::get('rapid_download_1');

// Clear rate limits for testing
Cache::forget('download_limit_user_1');
```

## 🚨 Expected Results

### ✅ Successful Cases
- **200 OK**: Authorized user downloads file
- **File Download**: Browser receives actual file content
- **Logs Created**: Security audit logs generated

### ❌ Failure Cases  
- **403 Forbidden**: Unauthorized user blocked
- **429 Too Many Requests**: Rate limit exceeded
- **404 Not Found**: File doesn't exist
- **401 Unauthorized**: No authentication token

### 📊 Monitoring Metrics
- Download success rate
- Authorization failures
- Rate limit violations  
- Suspicious activity alerts
- Average response times

## 🔧 Troubleshooting

### Common Issues:
1. **Middleware not applied**: Check bootstrap/app.php registration
2. **Policy not working**: Verify AuthServiceProvider registration  
3. **Rate limits not working**: Check cache driver configuration
4. **Files not downloading**: Check storage disk configuration

### Debug Commands:
```bash
# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear

# Check configuration
php artisan config:show auth
php artisan config:show filesystems
```

This comprehensive testing approach ensures your secure download implementation is working correctly at all levels! 🔐