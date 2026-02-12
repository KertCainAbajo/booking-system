# API Quick Start Guide

## ✅ What Was Implemented

Your booking system now has a **complete RESTful API** that allows all users (customers, staff, business owners, and admins) to interact with the system programmatically.

---

## 🎯 Features Implemented

### 1. **Authentication System**
- ✅ User registration (customers)
- ✅ User login (all roles)
- ✅ Token-based authentication using Laravel Sanctum
- ✅ Logout from current device
- ✅ Logout from all devices
- ✅ Get authenticated user details

### 2. **Profile Management**
- ✅ View profile
- ✅ Update profile
- ✅ Change password

### 3. **Services** (Public Access)
- ✅ List all services
- ✅ Get service details

### 4. **Bookings**
- ✅ Create booking (customers)
- ✅ View bookings (filtered by role)
- ✅ View booking details
- ✅ Update booking
- ✅ Cancel booking
- ✅ Get booking history

### 5. **Customer Management** (Staff/Admin)
- ✅ List all customers
- ✅ View customer details
- ✅ Create customer (admin only)
- ✅ Update customer (admin only)
- ✅ Delete customer (admin only)

### 6. **Role-Based Access Control**
- ✅ Customers can only see/manage their own bookings
- ✅ Staff and admins can see all bookings
- ✅ Admin-only endpoints for customer management

---

## 📁 Files Created/Modified

### New Files Created:
1. `routes/api.php` - All API routes
2. `app/Http/Controllers/Api/AuthController.php` - Authentication endpoints
3. `app/Http/Controllers/Api/ProfileController.php` - Profile management
4. `app/Http/Controllers/Api/BookingController.php` - Booking management
5. `app/Http/Controllers/Api/ServiceController.php` - Service endpoints
6. `app/Http/Controllers/Api/CustomerController.php` - Customer management
7. `API_DOCUMENTATION.md` - Complete API documentation
8. `API_TESTING_EXAMPLES.md` - Testing examples and code samples
9. `API_QUICK_START_GUIDE.md` - This guide
10. `config/sanctum.php` - Sanctum configuration
11. `database/migrations/xxxx_create_personal_access_tokens_table.php` - API tokens table

### Files Modified:
1. `app/Models/User.php` - Added `HasApiTokens` trait
2. `config/auth.php` - Added API guard
3. `bootstrap/app.php` - Registered API routes
4. `composer.json` - Added Laravel Sanctum package

---

## 🚀 How to Use the API

### Step 1: Start Your Server
```bash
php artisan serve
```

Your API will be available at: `http://127.0.0.1:8000/api/v1`

### Step 2: Test the Health Endpoint
```powershell
Invoke-RestMethod -Uri "http://127.0.0.1:8000/api/health" -Method Get
```

Expected response:
```json
{
  "status": "ok",
  "time": "2026-02-12T14:29:33+00:00",
  "version": "1.0.0"
}
```

### Step 3: Register a User (Optional)
```powershell
$registerBody = @{
    name = "API Test User"
    email = "apitest@example.com"
    phone = "+1234567890"
    password = "password123"
    password_confirmation = "password123"
} | ConvertTo-Json

Invoke-RestMethod -Uri "http://127.0.0.1:8000/api/v1/register" `
    -Method Post `
    -Body $registerBody `
    -ContentType "application/json" `
    -Headers @{"Accept"="application/json"}
```

### Step 4: Login and Get Token
```powershell
$loginBody = @{
    email = "apitest@example.com"
    password = "password123"
} | ConvertTo-Json

$response = Invoke-RestMethod -Uri "http://127.0.0.1:8000/api/v1/login" `
    -Method Post `
    -Body $loginBody `
    -ContentType "application/json" `
    -Headers @{"Accept"="application/json"}

$token = $response.data.token
Write-Host "Your API Token: $token"
```

### Step 5: Make Authenticated Requests
```powershell
# Get your profile
Invoke-RestMethod -Uri "http://127.0.0.1:8000/api/v1/profile" `
    -Method Get `
    -Headers @{
        "Accept" = "application/json"
        "Authorization" = "Bearer $token"
    }
```

---

## 📋 Available API Endpoints

### **Public Endpoints** (No Authentication Required)
- `GET /api/health` - API health check
- `POST /api/v1/register` - Register new customer
- `POST /api/v1/login` - Login and get token
- `GET /api/v1/services` - List all services
- `GET /api/v1/services/{id}` - Get service details

### **Authenticated Endpoints** (Token Required)
#### Auth & Profile:
- `GET /api/v1/user` - Get current user
- `POST /api/v1/logout` - Logout current device
- `POST /api/v1/logout-all` - Logout all devices
- `GET /api/v1/profile` - Get profile
- `PUT /api/v1/profile` - Update profile
- `POST /api/v1/profile/change-password` - Change password

#### Bookings:
- `GET /api/v1/bookings` - List bookings
- `POST /api/v1/bookings` - Create booking
- `GET /api/v1/bookings/{id}` - Get booking details
- `PUT /api/v1/bookings/{id}` - Update booking
- `DELETE /api/v1/bookings/{id}` - Cancel booking
- `GET /api/v1/my-bookings` - My active bookings (customers)
- `GET /api/v1/booking-history` - Booking history (customers)
- `GET /api/v1/all-bookings` - All bookings (staff/admin)

#### Customers (Staff/Admin):
- `GET /api/v1/customers` - List customers
- `GET /api/v1/customers/{id}` - Get customer details
- `POST /api/v1/customers` - Create customer (admin only)
- `PUT /api/v1/customers/{id}` - Update customer (admin only)
- `DELETE /api/v1/customers/{id}` - Delete customer (admin only)

---

## 🔐 Authentication Flow

1. **Register** (for new customers) or **Login** (for existing users)
2. **Save the token** from the response
3. **Include the token** in all subsequent requests:
   - Header: `Authorization: Bearer {your_token}`
4. **Logout** when done (optional, tokens persist until revoked)

---

## 📚 Documentation

- **Full API Documentation:** [API_DOCUMENTATION.md](API_DOCUMENTATION.md)
- **Testing Examples:** [API_TESTING_EXAMPLES.md](API_TESTING_EXAMPLES.md)

---

## 🛠️ Testing Tools

### Option 1: PowerShell (Windows)
```powershell
# See API_TESTING_EXAMPLES.md for full examples
Invoke-RestMethod -Uri "http://127.0.0.1:8000/api/v1/services" -Method Get
```

### Option 2: Postman
1. Download Postman
2. Import the API endpoints
3. Set up authentication with Bearer token
4. Start testing!

### Option 3: cURL (Linux/Mac/Git Bash)
```bash
curl -X GET http://127.0.0.1:8000/api/v1/services \
  -H "Accept: application/json"
```

### Option 4: Programming Languages
- See `API_TESTING_EXAMPLES.md` for JavaScript/Node.js and Python examples

---

## 🔧 Advanced Configuration

### Rate Limiting
Edit `app/Http/Kernel.php` to adjust rate limits:
```php
'api' => [
    'throttle:60,1', // 60 requests per minute
    'bindings',
],
```

### CORS (for frontend apps)
If you need to access the API from a web frontend:
```bash
composer require fruitcake/laravel-cors
```

### Token Expiration
By default, Sanctum tokens don't expire. To add expiration:

Edit `config/sanctum.php`:
```php
'expiration' => 60, // minutes
```

---

## 📊 Response Format

All API responses follow this format:

### Success Response:
```json
{
    "success": true,
    "message": "Operation successful",
    "data": { ... }
}
```

### Error Response:
```json
{
    "success": false,
    "message": "Error message",
    "errors": { ... }
}
```

---

## 🎯 Next Steps

1. **Test all endpoints** using the examples provided
2. **Integrate with your frontend** application (if needed)
3. **Set up proper CORS** if accessing from web apps
4. **Configure rate limiting** based on your needs
5. **Monitor API usage** in production
6. **Implement additional endpoints** as needed

---

## 🐛 Troubleshooting

### Issue: "Unauthenticated" error
**Solution:** Make sure you're including the token in the Authorization header:
```
Authorization: Bearer {your_token}
```

### Issue: "Route not found"
**Solution:** Make sure your server is running and you're using the correct URL format:
```
http://127.0.0.1:8000/api/v1/...
```

### Issue: "Token mismatch" or CSRF errors
**Solution:** Always include the `Accept: application/json` header in your requests.

### Issue: Can't access certain endpoints
**Solution:** Check your user role. Some endpoints are restricted to specific roles (staff, admin, etc.)

---

## 💡 Tips

1. **Save your token** securely - it's like a password
2. **Use environment variables** for API URLs in production
3. **Always include `Accept: application/json`** header
4. **Test with small data** first before bulk operations
5. **Read error messages carefully** - they usually tell you what's wrong
6. **Use Postman collections** to organize your API tests
7. **Log out when done** to revoke tokens you're not using

---

## 🎉 Success!

Your API is now ready to use! All users who log into your system can now access it programmatically using their authentication tokens.

For detailed endpoint documentation, see [API_DOCUMENTATION.md](API_DOCUMENTATION.md)

For testing examples in various languages, see [API_TESTING_EXAMPLES.md](API_TESTING_EXAMPLES.md)

---

## 📞 Support

If you have any questions or issues:
1. Check the documentation files
2. Review the testing examples
3. Verify your server is running
4. Check the Laravel logs: `storage/logs/laravel.log`

Happy coding! 🚀
