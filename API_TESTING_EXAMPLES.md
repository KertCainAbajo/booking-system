# API Testing Examples

## Using PowerShell (Windows)

### 1. Test Health Endpoint
```powershell
Invoke-RestMethod -Uri "http://127.0.0.1:8000/api/health" -Method Get
```

### 2. Register a New Customer
```powershell
$registerBody = @{
    name = "Test User"
    email = "testuser@example.com"
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

### 3. Login and Get Token
```powershell
$loginBody = @{
    email = "testuser@example.com"
    password = "password123"
    device_name = "PowerShell Client"
} | ConvertTo-Json

$loginResponse = Invoke-RestMethod -Uri "http://127.0.0.1:8000/api/v1/login" `
    -Method Post `
    -Body $loginBody `
    -ContentType "application/json" `
    -Headers @{"Accept"="application/json"}

# Save the token
$token = $loginResponse.data.token
Write-Host "Token: $token"
```

### 4. Get User Profile (Authenticated)
```powershell
Invoke-RestMethod -Uri "http://127.0.0.1:8000/api/v1/profile" `
    -Method Get `
    -Headers @{
        "Accept" = "application/json"
        "Authorization" = "Bearer $token"
    }
```

### 5. Get All Services (Public)
```powershell
Invoke-RestMethod -Uri "http://127.0.0.1:8000/api/v1/services" `
    -Method Get `
    -Headers @{"Accept"="application/json"}
```

### 6. Create a Booking
```powershell
$bookingBody = @{
    service_id = 1
    vehicle_id = 1
    booking_date = "2026-02-15"
    booking_time = "10:00"
    notes = "Test booking"
} | ConvertTo-Json

Invoke-RestMethod -Uri "http://127.0.0.1:8000/api/v1/bookings" `
    -Method Post `
    -Body $bookingBody `
    -ContentType "application/json" `
    -Headers @{
        "Accept" = "application/json"
        "Authorization" = "Bearer $token"
    }
```

### 7. Get My Bookings
```powershell
Invoke-RestMethod -Uri "http://127.0.0.1:8000/api/v1/my-bookings" `
    -Method Get `
    -Headers @{
        "Accept" = "application/json"
        "Authorization" = "Bearer $token"
    }
```

### 8. Update Profile
```powershell
$profileBody = @{
    name = "Updated Name"
    phone = "+0987654321"
} | ConvertTo-Json

Invoke-RestMethod -Uri "http://127.0.0.1:8000/api/v1/profile" `
    -Method Put `
    -Body $profileBody `
    -ContentType "application/json" `
    -Headers @{
        "Accept" = "application/json"
        "Authorization" = "Bearer $token"
    }
```

### 9. Change Password
```powershell
$passwordBody = @{
    current_password = "password123"
    new_password = "newpassword123"
    new_password_confirmation = "newpassword123"
} | ConvertTo-Json

Invoke-RestMethod -Uri "http://127.0.0.1:8000/api/v1/profile/change-password" `
    -Method Post `
    -Body $passwordBody `
    -ContentType "application/json" `
    -Headers @{
        "Accept" = "application/json"
        "Authorization" = "Bearer $token"
    }
```

### 10. Logout
```powershell
Invoke-RestMethod -Uri "http://127.0.0.1:8000/api/v1/logout" `
    -Method Post `
    -Headers @{
        "Accept" = "application/json"
        "Authorization" = "Bearer $token"
    }
```

---

## Using cURL (Linux/Mac/Git Bash)

### 1. Test Health Endpoint
```bash
curl -X GET http://127.0.0.1:8000/api/health \
  -H "Accept: application/json"
```

### 2. Register a New Customer
```bash
curl -X POST http://127.0.0.1:8000/api/v1/register \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "name": "Test User",
    "email": "testuser@example.com",
    "phone": "+1234567890",
    "password": "password123",
    "password_confirmation": "password123"
  }'
```

### 3. Login and Get Token
```bash
curl -X POST http://127.0.0.1:8000/api/v1/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "email": "testuser@example.com",
    "password": "password123",
    "device_name": "cURL Client"
  }'
```

### 4. Get User Profile (Save token from login response)
```bash
TOKEN="your_token_here"

curl -X GET http://127.0.0.1:8000/api/v1/profile \
  -H "Accept: application/json" \
  -H "Authorization: Bearer $TOKEN"
```

### 5. Get All Services
```bash
curl -X GET http://127.0.0.1:8000/api/v1/services \
  -H "Accept: application/json"
```

### 6. Create a Booking
```bash
curl -X POST http://127.0.0.1:8000/api/v1/bookings \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer $TOKEN" \
  -d '{
    "service_id": 1,
    "vehicle_id": 1,
    "booking_date": "2026-02-15",
    "booking_time": "10:00",
    "notes": "Test booking"
  }'
```

### 7. Get My Bookings
```bash
curl -X GET http://127.0.0.1:8000/api/v1/my-bookings \
  -H "Accept: application/json" \
  -H "Authorization: Bearer $TOKEN"
```

### 8. Logout
```bash
curl -X POST http://127.0.0.1:8000/api/v1/logout \
  -H "Accept: application/json" \
  -H "Authorization: Bearer $TOKEN"
```

---

## Using Postman

1. **Create a new collection** for your API
2. **Add environment variables:**
   - `base_url`: `http://127.0.0.1:8000/api/v1`
   - `token`: (will be set after login)

3. **Create requests:**

   **a. Login Request:**
   - Method: POST
   - URL: `{{base_url}}/login`
   - Headers: 
     - `Accept: application/json`
     - `Content-Type: application/json`
   - Body (raw JSON):
     ```json
     {
       "email": "testuser@example.com",
       "password": "password123"
     }
     ```
   - Tests (to auto-save token):
     ```javascript
     var jsonData = pm.response.json();
     if (jsonData.success && jsonData.data.token) {
         pm.environment.set("token", jsonData.data.token);
     }
     ```

   **b. Protected Endpoints:**
   - Headers:
     - `Accept: application/json`
     - `Authorization: Bearer {{token}}`

4. **Test the flow:**
   - Register → Login → Get Profile → Create Booking → Logout

---

## Testing with JavaScript/Node.js

```javascript
const axios = require('axios');

const API_BASE = 'http://127.0.0.1:8000/api/v1';
let authToken = '';

// Register
async function register() {
    try {
        const response = await axios.post(`${API_BASE}/register`, {
            name: 'Test User',
            email: 'testuser@example.com',
            phone: '+1234567890',
            password: 'password123',
            password_confirmation: 'password123'
        });
        console.log('Register:', response.data);
        authToken = response.data.data.token;
    } catch (error) {
        console.error('Error:', error.response?.data);
    }
}

// Login
async function login() {
    try {
        const response = await axios.post(`${API_BASE}/login`, {
            email: 'testuser@example.com',
            password: 'password123',
            device_name: 'Node.js Client'
        });
        console.log('Login:', response.data);
        authToken = response.data.data.token;
    } catch (error) {
        console.error('Error:', error.response?.data);
    }
}

// Get Profile
async function getProfile() {
    try {
        const response = await axios.get(`${API_BASE}/profile`, {
            headers: {
                'Authorization': `Bearer ${authToken}`,
                'Accept': 'application/json'
            }
        });
        console.log('Profile:', response.data);
    } catch (error) {
        console.error('Error:', error.response?.data);
    }
}

// Run tests
(async () => {
    await login();
    await getProfile();
})();
```

---

## Testing with Python

```python
import requests

API_BASE = 'http://127.0.0.1:8000/api/v1'
auth_token = ''

# Register
def register():
    global auth_token
    response = requests.post(f'{API_BASE}/register', json={
        'name': 'Test User',
        'email': 'testuser@example.com',
        'phone': '+1234567890',
        'password': 'password123',
        'password_confirmation': 'password123'
    }, headers={'Accept': 'application/json'})
    
    data = response.json()
    print('Register:', data)
    if data['success']:
        auth_token = data['data']['token']

# Login
def login():
    global auth_token
    response = requests.post(f'{API_BASE}/login', json={
        'email': 'testuser@example.com',
        'password': 'password123',
        'device_name': 'Python Client'
    }, headers={'Accept': 'application/json'})
    
    data = response.json()
    print('Login:', data)
    if data['success']:
        auth_token = data['data']['token']

# Get Profile
def get_profile():
    response = requests.get(f'{API_BASE}/profile', headers={
        'Authorization': f'Bearer {auth_token}',
        'Accept': 'application/json'
    })
    print('Profile:', response.json())

# Run tests
if __name__ == '__main__':
    login()
    get_profile()
```

---

## Common Issues and Solutions

### 1. CORS Issues (when accessing from frontend)
If you're accessing the API from a different domain, you may need to configure CORS in Laravel.

### 2. Token Not Working
- Make sure you include the "Bearer " prefix
- Check if the token hasn't been revoked
- Verify the token is being sent in the Authorization header

### 3. 419 CSRF Token Mismatch
This shouldn't happen with API routes, but if it does, make sure:
- You're using `/api` routes
- The Accept header is set to `application/json`

### 4. 401 Unauthenticated
- Verify you're logged in and have a valid token
- Check the token hasn't expired or been revoked
- Ensure the Authorization header is correctly formatted

---

## Production Considerations

1. **Use HTTPS** in production
2. **Set proper rate limiting** for your API
3. **Enable API throttling** to prevent abuse
4. **Implement proper logging** for API requests
5. **Use environment variables** for sensitive data
6. **Consider API versioning** for future updates
7. **Document all endpoints** thoroughly
8. **Implement proper error handling**
9. **Add request validation** on all inputs
10. **Monitor API performance** and usage
