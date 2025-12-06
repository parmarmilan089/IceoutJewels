# Laravel 12 Admin API - Complete API Documentation

## Base URL
```
http://localhost/Laravel12AdminAPI/public/api
```

## Authentication
All authenticated endpoints require a Bearer token in the Authorization header:
```
Authorization: Bearer {your_token}
```

---

## 📋 API Endpoints Overview

### Authentication APIs
| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| POST | `/register` | Register a new user | No |
| POST | `/login` | Login user | No |
| POST | `/logout` | Logout user | Yes |
| ANY | `/refresh` | Refresh JWT token | Yes |
| GET | `/profile` | Get user profile | Yes |
| POST | `/update/profile` | Update user profile | Yes |
| POST | `/delete/profile` | Delete user account | Yes |
| POST | `/change/password` | Change password | Yes |
| POST | `/forgot-password` | Send password reset link | No |
| POST | `/reset-password` | Reset password | No |

---

## 🔐 Authentication Endpoints

### 1. Register User
**Endpoint:** `POST /api/register`

**Request Body:**
```json
{
    "first_name": "John",
    "last_name": "Doe",
    "email": "john@example.com",
    "phone": "+1234567890",
    "password": "password123"
}
```

**Success Response (201):**
```json
{
    "status": "success",
    "message": "User successfully registered",
    "user": {
        "id": 1,
        "first_name": "John",
        "last_name": "Doe",
        "email": "john@example.com",
        "phone": "+1234567890",
        "profile": null,
        "status": true,
        "role": 0,
        "created_at": "2025-12-06T11:43:14.000000Z",
        "updated_at": "2025-12-06T11:43:14.000000Z"
    },
    "token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
    "token_type": "bearer",
    "expires_in": 3600
}
```

**Error Response (422):**
```json
{
    "status": "error",
    "message": "Validation failed",
    "errors": {
        "email": ["The email has already been taken."]
    }
}
```

---

### 2. Login User
**Endpoint:** `POST /api/login`

**Request Body:**
```json
{
    "email": "john@example.com",
    "password": "password123"
}
```

**Success Response (200):**
```json
{
    "status": "success",
    "message": "logged in successfully",
    "token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
    "token_type": "bearer",
    "expires_in": 3600,
    "user": {
        "id": 1,
        "first_name": "John",
        "last_name": "Doe",
        "email": "john@example.com",
        "phone": "+1234567890",
        "profile": null,
        "status": true,
        "role": 0
    }
}
```

**Error Response (404):**
```json
{
    "status": "error",
    "message": "That email or password isn't right. Please check and try again."
}
```

---

### 3. Logout User
**Endpoint:** `POST /api/logout`

**Headers:**
```
Authorization: Bearer {token}
```

**Success Response (200):**
```json
{
    "status": "success",
    "message": "User successfully logged out"
}
```

---

### 4. Refresh Token
**Endpoint:** `ANY /api/refresh`

**Headers:**
```
Authorization: Bearer {token}
```

**Success Response (200):**
```json
{
    "status": "success",
    "message": "logged in successfully",
    "token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
    "token_type": "bearer",
    "expires_in": 3600,
    "user": {
        "id": 1,
        "first_name": "John",
        "last_name": "Doe",
        "email": "john@example.com"
    }
}
```

---

### 5. Get User Profile
**Endpoint:** `GET /api/profile`

**Headers:**
```
Authorization: Bearer {token}
```

**Success Response (200):**
```json
{
    "status": "success",
    "message": "Profile Fetch successfully",
    "data": {
        "id": 1,
        "first_name": "John",
        "last_name": "Doe",
        "email": "john@example.com",
        "phone": "+1234567890",
        "profile": "http://localhost/Laravel12AdminAPI/public/storage/profiles/profile.jpg",
        "status": true,
        "role": 0,
        "created_at": "2025-12-06T11:43:14.000000Z"
    }
}
```

---

### 6. Update User Profile
**Endpoint:** `POST /api/update/profile`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: multipart/form-data
```

**Request Body (Form Data):**
```
first_name: John
last_name: Doe Updated
phone: +1234567890
profile: [image file]
```

**Success Response (200):**
```json
{
    "status": "success",
    "message": "Profile updated successfully",
    "data": {
        "id": 1,
        "first_name": "John",
        "last_name": "Doe Updated",
        "email": "john@example.com",
        "phone": "+1234567890",
        "profile": "http://localhost/Laravel12AdminAPI/public/storage/profiles/updated.jpg",
        "status": true,
        "role": 0
    }
}
```

---

### 7. Delete User Profile
**Endpoint:** `POST /api/delete/profile`

**Headers:**
```
Authorization: Bearer {token}
```

**Success Response (200):**
```json
{
    "status": "success",
    "message": "Account deleted successfully"
}
```

---

### 8. Change Password
**Endpoint:** `POST /api/change/password`

**Headers:**
```
Authorization: Bearer {token}
```

**Request Body:**
```json
{
    "current_password": "oldpassword123",
    "new_password": "newpassword123",
    "confirm_password": "newpassword123"
}
```

**Success Response (200):**
```json
{
    "status": "success",
    "message": "Password changed successfully",
    "data": {
        "id": 1,
        "first_name": "John",
        "last_name": "Doe",
        "email": "john@example.com"
    }
}
```

**Error Response (400):**
```json
{
    "status": "error",
    "message": "Current password is incorrect."
}
```

---

### 9. Forgot Password
**Endpoint:** `POST /api/forgot-password`

**Request Body:**
```json
{
    "email": "john@example.com"
}
```

**Success Response (200):**
```json
{
    "status": "success",
    "message": "Password reset link sent to your email"
}
```

---

### 10. Reset Password
**Endpoint:** `POST /api/reset-password`

**Request Body:**
```json
{
    "email": "john@example.com",
    "token": "reset_token_from_email",
    "password": "newpassword123",
    "password_confirmation": "newpassword123"
}
```

**Success Response (200):**
```json
{
    "status": "success",
    "message": "Password has been reset successfully"
}
```

---

## 📝 Common Error Responses

### Validation Error (422)
```json
{
    "status": "error",
    "message": "Validation failed",
    "errors": {
        "field_name": ["Error message"]
    }
}
```

### Unauthorized (401)
```json
{
    "status": "error",
    "message": "Unauthorized."
}
```

### Not Found (404)
```json
{
    "status": "error",
    "message": "Resource not found"
}
```

### Server Error (500)
```json
{
    "status": "error",
    "message": "Internal server error"
}
```

---

## 🧪 Testing with Postman/cURL

### Example cURL for Registration:
```bash
curl -X POST http://localhost/Laravel12AdminAPI/public/api/register \
  -H "Content-Type: application/json" \
  -d '{
    "first_name": "John",
    "last_name": "Doe",
    "email": "john@example.com",
    "phone": "+1234567890",
    "password": "password123"
  }'
```

### Example cURL for Login:
```bash
curl -X POST http://localhost/Laravel12AdminAPI/public/api/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "john@example.com",
    "password": "password123"
  }'
```

### Example cURL for Authenticated Request:
```bash
curl -X GET http://localhost/Laravel12AdminAPI/public/api/profile \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

---

## 🔧 Admin Panel Routes (Web)

### Category Management
| Method | Route | Description |
|--------|-------|-------------|
| GET | `/admin/categories` | List all categories |
| GET | `/admin/categories/create` | Show create form |
| POST | `/admin/categories` | Store new category |
| GET | `/admin/categories/{id}/edit` | Show edit form |
| PUT | `/admin/categories/{id}` | Update category |
| DELETE | `/admin/categories/{id}` | Delete category |
| POST | `/admin/categories/{id}/toggle-status` | Toggle category status |

---

## 📌 Notes

1. **Token Expiration:** JWT tokens expire after 60 minutes (3600 seconds)
2. **Image Upload:** Maximum file size is 2MB for profile images
3. **Password Requirements:** Minimum 8 characters
4. **Email Validation:** Must be a valid email format
5. **Phone Validation:** Required for registration

---

## 🚀 Quick Start

1. **Set up your environment:**
   - Update `.env` file with database credentials
   - Run migrations: `php artisan migrate`

2. **Test the API:**
   - Register a new user
   - Login to get the token
   - Use the token for authenticated requests

3. **Access Admin Panel:**
   - URL: `http://localhost/Laravel12AdminAPI/public/admin/login`
   - Create admin user via seeder or database

---

## 📧 Support

For issues or questions, please contact the development team.

**Last Updated:** December 6, 2025
**Version:** 1.0.0
