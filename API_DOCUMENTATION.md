# Booking System API Documentation

## Overview
This API provides access to the Booking System for all authenticated users. It uses Laravel Sanctum for token-based authentication.

**Base URL:** `http://your-domain.com/api/v1`

**Authentication:** Bearer Token

---

## Authentication

### 1. Register (Customer Only)
**Endpoint:** `POST /api/v1/register`

**Description:** Register a new customer account

**Headers:**
```
Content-Type: application/json
Accept: application/json
```

**Request Body:**
```json
{
    "name": "John Doe",
    "email": "john@example.com",
    "phone": "+1234567890",
    "password": "password123",
    "password_confirmation": "password123"
}
```

**Response (201 Created):**
```json
{
    "success": true,
    "message": "User registered successfully",
    "data": {
        "user": {
            "id": 1,
            "name": "John Doe",
            "email": "john@example.com",
            "phone": "+1234567890",
            "role": "customer"
        },
        "token": "1|abc123...",
        "token_type": "Bearer"
    }
}
```

---

### 2. Login
**Endpoint:** `POST /api/v1/login`

**Description:** Login and receive an API token

**Headers:**
```
Content-Type: application/json
Accept: application/json
```

**Request Body:**
```json
{
    "email": "john@example.com",
    "password": "password123",
    "device_name": "Mobile App" // optional
}
```

**Response (200 OK):**
```json
{
    "success": true,
    "message": "Login successful",
    "data": {
        "user": {
            "id": 1,
            "name": "John Doe",
            "email": "john@example.com",
            "phone": "+1234567890",
            "role": "customer",
            "role_id": 1
        },
        "token": "1|abc123...",
        "token_type": "Bearer"
    }
}
```

---

### 3. Get Current User
**Endpoint:** `GET /api/v1/user`

**Description:** Get authenticated user details

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
```

**Response (200 OK):**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "phone": "+1234567890",
        "role": "customer",
        "role_id": 1,
        "email_verified_at": null,
        "created_at": "2024-01-01T00:00:00.000000Z"
    }
}
```

---

### 4. Logout
**Endpoint:** `POST /api/v1/logout`

**Description:** Logout from current device (revoke current token)

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
```

**Response (200 OK):**
```json
{
    "success": true,
    "message": "Logged out successfully"
}
```

---

### 5. Logout from All Devices
**Endpoint:** `POST /api/v1/logout-all`

**Description:** Logout from all devices (revoke all tokens)

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
```

**Response (200 OK):**
```json
{
    "success": true,
    "message": "Logged out from all devices successfully"
}
```

---

## Profile Management

### 6. Get Profile
**Endpoint:** `GET /api/v1/profile`

**Description:** Get authenticated user's profile

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
```

**Response (200 OK):**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "phone": "+1234567890",
        "role": "customer",
        "role_id": 1,
        "email_verified_at": null,
        "google2fa_enabled": false,
        "created_at": "2024-01-01T00:00:00.000000Z",
        "updated_at": "2024-01-01T00:00:00.000000Z"
    }
}
```

---

### 7. Update Profile
**Endpoint:** `PUT /api/v1/profile`

**Description:** Update authenticated user's profile

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
Accept: application/json
```

**Request Body:**
```json
{
    "name": "John Smith",
    "phone": "+0987654321",
    "email": "johnsmith@example.com"
}
```

**Response (200 OK):**
```json
{
    "success": true,
    "message": "Profile updated successfully",
    "data": {
        "id": 1,
        "name": "John Smith",
        "email": "johnsmith@example.com",
        "phone": "+0987654321"
    }
}
```

---

### 8. Change Password
**Endpoint:** `POST /api/v1/profile/change-password`

**Description:** Change user password

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
Accept: application/json
```

**Request Body:**
```json
{
    "current_password": "oldpassword123",
    "new_password": "newpassword123",
    "new_password_confirmation": "newpassword123"
}
```

**Response (200 OK):**
```json
{
    "success": true,
    "message": "Password changed successfully"
}
```

---

## Services (Public)

### 9. Get All Services
**Endpoint:** `GET /api/v1/services`

**Description:** Get list of all active services (public)

**Query Parameters:**
- `per_page` (optional): Number of items per page (default: 20)
- `category_id` (optional): Filter by category ID

**Headers:**
```
Accept: application/json
```

**Response (200 OK):**
```json
{
    "success": true,
    "data": {
        "current_page": 1,
        "data": [
            {
                "id": 1,
                "name": "Oil Change",
                "description": "Complete oil change service",
                "price": "49.99",
                "duration": 30,
                "category_id": 1,
                "is_active": true,
                "category": {
                    "id": 1,
                    "name": "Maintenance"
                }
            }
        ],
        "per_page": 20,
        "total": 10
    }
}
```

---

### 10. Get Service Details
**Endpoint:** `GET /api/v1/services/{id}`

**Description:** Get details of a specific service (public)

**Headers:**
```
Accept: application/json
```

**Response (200 OK):**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "name": "Oil Change",
        "description": "Complete oil change service",
        "price": "49.99",
        "duration": 30,
        "category": {
            "id": 1,
            "name": "Maintenance"
        },
        "inventoryItems": []
    }
}
```

---

## Bookings

### 11. Get All Bookings
**Endpoint:** `GET /api/v1/bookings`

**Description:** Get all bookings (customers see only their bookings)

**Query Parameters:**
- `per_page` (optional): Number of items per page (default: 15)

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
```

**Response (200 OK):**
```json
{
    "success": true,
    "data": {
        "current_page": 1,
        "data": [
            {
                "id": 1,
                "customer_id": 1,
                "service_id": 1,
                "vehicle_id": 1,
                "booking_date": "2024-01-15",
                "booking_time": "10:00:00",
                "status": "pending",
                "notes": "Please check brakes",
                "service": {
                    "id": 1,
                    "name": "Oil Change"
                },
                "vehicle": {
                    "id": 1,
                    "make": "Toyota",
                    "model": "Camry"
                }
            }
        ]
    }
}
```

---

### 12. Create Booking
**Endpoint:** `POST /api/v1/bookings`

**Description:** Create a new booking (customers only)

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
Accept: application/json
```

**Request Body:**
```json
{
    "service_id": 1,
    "vehicle_id": 1,
    "booking_date": "2024-01-15",
    "booking_time": "10:00",
    "notes": "Please check brakes"
}
```

**Response (201 Created):**
```json
{
    "success": true,
    "message": "Booking created successfully",
    "data": {
        "id": 1,
        "customer_id": 1,
        "service_id": 1,
        "vehicle_id": 1,
        "booking_date": "2024-01-15",
        "booking_time": "10:00:00",
        "status": "pending",
        "notes": "Please check brakes"
    }
}
```

---

### 13. Get Booking Details  
**Endpoint:** `GET /api/v1/bookings/{id}`

**Description:** Get details of a specific booking

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
```

**Response (200 OK):**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "customer_id": 1,
        "service_id": 1,
        "vehicle_id": 1,
        "booking_date": "2024-01-15",
        "booking_time": "10:00:00",
        "status": "pending",
        "notes": "Please check brakes",
        "customer": {...},
        "service": {...},
        "vehicle": {...}
    }
}
```

---

### 14. Update Booking
**Endpoint:** `PUT /api/v1/bookings/{id}`

**Description:** Update a booking

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
Accept: application/json
```

**Request Body:**
```json
{
    "booking_date": "2024-01-16",
    "booking_time": "14:00",
    "status": "confirmed",
    "notes": "Updated notes"
}
```

**Response (200 OK):**
```json
{
    "success": true,
    "message": "Booking updated successfully",
    "data": {...}
}
```

---

### 15. Cancel Booking
**Endpoint:** `DELETE /api/v1/bookings/{id}`

**Description:** Cancel a booking (sets status to 'cancelled')

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
```

**Response (200 OK):**
```json
{
    "success": true,
    "message": "Booking cancelled successfully"
}
```

---

### 16. Get My Bookings (Customer Only)
**Endpoint:** `GET /api/v1/my-bookings`

**Description:** Get current user's active bookings

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
```

**Response (200 OK):**
```json
{
    "success": true,
    "data": [...]
}
```

---

### 17. Get Booking History (Customer Only)
**Endpoint:** `GET /api/v1/booking-history`

**Description:** Get completed and cancelled bookings

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
```

**Response (200 OK):**
```json
{
    "success": true,
    "data": [...]
}
```

---

### 18. Get All Bookings (Staff/Admin Only)
**Endpoint:** `GET /api/v1/all-bookings`

**Description:** Get all system bookings with filters

**Query Parameters:**
- `per_page` (optional): Number of items per page (default: 15)
- `status` (optional): Filter by status

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
```

---

## Customers (Staff/Admin Only)

### 19. Get All Customers
**Endpoint:** `GET /api/v1/customers`

**Description:** Get list of all customers

**Query Parameters:**
- `per_page` (optional): Number of items per page (default: 15)
- `search` (optional): Search by name, email, or phone

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
```

---

### 20. Get Customer Details
**Endpoint:** `GET /api/v1/customers/{id}`

**Description:** Get details of a specific customer

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
```

---

### 21. Create Customer (Admin Only)
**Endpoint:** `POST /api/v1/customers`

**Description:** Create a new customer

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
Accept: application/json
```

**Request Body:**
```json
{
    "name": "Jane Doe",
    "email": "jane@example.com",
    "phone": "+1234567890",
    "password": "password123"
}
```

---

### 22. Update Customer (Admin Only)
**Endpoint:** `PUT /api/v1/customers/{id}`

**Description:** Update customer details

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
Accept: application/json
```

---

### 23. Delete Customer (Admin Only)
**Endpoint:** `DELETE /api/v1/customers/{id}`

**Description:** Delete a customer

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
```

---

## Health Check

### 24. API Health Check
**Endpoint:** `GET /api/health`

**Description:** Check if API is running

**Response (200 OK):**
```json
{
    "status": "ok",
    "time": "2024-01-01T00:00:00+00:00",
    "version": "1.0.0"
}
```

---

## Error Responses

### Validation Error (422)
```json
{
    "success": false,
    "message": "Validation error",
    "errors": {
        "email": ["The email field is required."]
    }
}
```

### Unauthorized (401)
```json
{
    "success": false,
    "message": "Invalid credentials"
}
```

### Forbidden (403)
```json
{
    "success": false,
    "message": "Unauthorized access"
}
```

### Not Found (404)
```json
{
    "success": false,
    "message": "Resource not found"
}
```

### Server Error (500)
```json
{
    "success": false,
    "message": "Operation failed",
    "error": "Error details..."
}
```

---

## Usage Examples

### Using cURL

#### Register
```bash
curl -X POST http://your-domain.com/api/v1/register \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "name": "John Doe",
    "email": "john@example.com",
    "phone": "+1234567890",
    "password": "password123",
    "password_confirmation": "password123"
  }'
```

#### Login
```bash
curl -X POST http://your-domain.com/api/v1/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "email": "john@example.com",
    "password": "password123"
  }'
```

#### Get Profile (with token)
```bash
curl -X GET http://your-domain.com/api/v1/profile \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Accept: application/json"
```

### Using Postman

1. Create a new request
2. Set the method and URL
3. Add headers:
   - `Accept: application/json`
   - `Authorization: Bearer {your_token}` (for protected routes)
4. For POST/PUT requests, select Body → raw → JSON and add your request data
5. Click Send

---

## Rate Limiting

The API uses Laravel's default rate limiting:
- **Public endpoints:** 60 requests per minute
- **Authenticated endpoints:** 60 requests per minute per user

---

## Notes

1. All timestamps are in ISO 8601 format
2. Pagination is available on list endpoints using `per_page` parameter
3. All dates should be in `YYYY-MM-DD` format
4. All times should be in `HH:MM` format (24-hour)
5. Token should be included in the `Authorization` header as `Bearer {token}`
6. API responses always include a `success` boolean field
7. Customers can only see/edit their own bookings
8. Staff and admins have access to all bookings
9. Only admins can create/update/delete customers via API

---

## Support

For any issues or questions, please contact your system administrator.
