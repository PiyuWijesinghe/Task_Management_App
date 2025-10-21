# කරන්න තියන CHECK LIST - SECURE DOWNLOADS

## ✅ මූලික පරීක්ෂණ (Basic Checks)

### 1. Routes එක check කරන්න
```bash
# Download routes බලන්න
php artisan route:list --name="download" -v

# API routes බලන්න
php artisan route:list --path="api" | findstr "download"
```

### 2. Policies register වෙලා ද බලන්න
```bash
# Verification script run කරන්න
php verify_security.php
```

### 3. Tests run කරන්න
```bash
# Security tests check කරන්න
php artisan test tests/Feature/SecureDownloadTest.php
```

## 🔧 Practical Testing කරන්න

### 4. Database වල data තියනවා ද බලන්න
```bash
php artisan tinker
```
```php
// Users count
User::count()

// Tasks count  
Task::count()

// Attachments count
TaskAttachment::count()

// Sample user permissions
$user = User::first();
$task = Task::first();
Gate::forUser($user)->allows('view', $task);
```

### 5. Manual API test කරන්න

#### Login කරන්න token එකක් ගන්න
```bash
curl -X POST http://localhost:8000/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d "{\"email\": \"user@example.com\", \"password\": \"password\"}"
```

#### File download try කරන්න
```bash
# Authorized user (should work)
curl -H "Authorization: Bearer YOUR_TOKEN" \
     "http://localhost:8000/api/v1/tasks/1/attachments/1/download"

# Wrong user (should fail with 403)
curl -H "Authorization: Bearer WRONG_TOKEN" \
     "http://localhost:8000/api/v1/tasks/1/attachments/1/download"
```

### 6. Rate limiting test කරන්න
```bash
# Multiple requests send කරන්න (50+ times)
for /L %i in (1,1,60) do curl -H "Authorization: Bearer TOKEN" "http://localhost:8000/api/v1/tasks/1/attachments/1/download"
```

### 7. Logs check කරන්න
```bash
# Laravel logs බලන්න
type storage\logs\laravel.log | findstr /i "attachment download rate limit"

# Real-time monitoring
Get-Content storage\logs\laravel.log -Wait -Tail 10
```

## 🎯 බලාපොරොත්තු වන Results

### ✅ Success Cases:
- **200 OK**: Authorized user ට file download කරන්න පුළුවන්
- **File Content**: Browser එකට actual file එක download වෙනවා
- **Logs**: Security audit logs create වෙනවා

### ❌ Failure Cases:
- **403 Forbidden**: Unauthorized user block වෙනවා  
- **429 Too Many Requests**: Rate limit exceed කළාම
- **404 Not Found**: File නැත්නම්
- **401 Unauthorized**: Authentication token නැත්නම්

## 🚨 Common Issues & Solutions

### Issue 1: Middleware apply වෙලා නැහැ
**Check**: `bootstrap/app.php` file එකේ middleware register කරලා තියනවා ද

### Issue 2: Policy work කරන්නේ නැහැ  
**Check**: `AuthServiceProvider.php` එකේ policy register කරලා තියනවා ද

### Issue 3: Rate limiting work කරන්නේ නැහැ
**Check**: Cache driver properly configure කරලා තියනවා ද

### Issue 4: Files download වෙන්නේ නැහැ
**Check**: Storage configuration සහ file permissions

## 🔍 Debug Commands

```bash
# Clear all caches
php artisan cache:clear
php artisan config:clear  
php artisan route:clear

# Check configurations
php artisan config:show auth
php artisan config:show filesystems
php artisan config:show cache
```

## 📊 Monitoring Commands

```bash
# Check current rate limits
php artisan tinker
Cache::get('download_limit_user_1')
Cache::get('download_limit_ip_127.0.0.1')

# Clear rate limits (for testing)
Cache::flush()
```

## 🎯 Final Verification

System එක properly setup කරලා තියනවා ද කියලා verify කරන්න:

1. ✅ **Routes**: Download routes middleware සමග configure කරලා තියනවා
2. ✅ **Policies**: TaskAttachmentPolicy register කරලා තියනවා  
3. ✅ **Middleware**: SecureDownloadMiddleware active කරලා තියනවා
4. ✅ **Tests**: All security tests pass වෙනවා
5. ✅ **Database**: Users සහ tasks තියනවා testing වලට
6. ✅ **Storage**: File upload/download system work කරනවා
7. ✅ **Logs**: Security events log වෙනවා

මේ සියල්ල check කරලා බලලා, system එක production ready! 🚀

## 🔐 Security Confirmation

```
✅ Authorization: Laravel Policies active
✅ Rate Limiting: 50/user/min, 100/IP/min  
✅ Audit Logging: All access attempts logged
✅ Suspicious Activity: Bot detection active
✅ File Security: Direct access blocked
✅ Error Handling: Proper HTTP responses
✅ Testing Coverage: Comprehensive test suite
```

**Your secure download system is ready! 🎉**