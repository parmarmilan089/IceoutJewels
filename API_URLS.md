# 📍 Complete API URLs - Laravel 12 Admin API

## 🌐 Base URLs

### API Base URL
```
http://localhost/Laravel12AdminAPI/public/api
```

### Admin Panel Base URL
```
http://localhost/Laravel12AdminAPI/public/admin
```

---

## 🔐 Authentication API Endpoints

### 1. Register User
```
POST http://localhost/Laravel12AdminAPI/public/api/register
```
**Auth Required:** ❌ No

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

---

### 2. Login User
```
POST http://localhost/Laravel12AdminAPI/public/api/login
```
**Auth Required:** ❌ No

**Request Body:**
```json
{
    "email": "john@example.com",
    "password": "password123"
}
```

**Response:** Returns JWT token

---

### 3. Logout User
```
POST http://localhost/Laravel12AdminAPI/public/api/logout
```
**Auth Required:** ✅ Yes (Bearer Token)

**Headers:**
```
Authorization: Bearer YOUR_TOKEN_HERE
```

---

### 4. Refresh Token
```
POST http://localhost/Laravel12AdminAPI/public/api/refresh
ANY http://localhost/Laravel12AdminAPI/public/api/refresh
```
**Auth Required:** ✅ Yes (Bearer Token)

**Headers:**
```
Authorization: Bearer YOUR_TOKEN_HERE
```

**Response:** Returns new JWT token

---

### 5. Get User Profile
```
GET http://localhost/Laravel12AdminAPI/public/api/profile
```
**Auth Required:** ✅ Yes (Bearer Token)

**Headers:**
```
Authorization: Bearer YOUR_TOKEN_HERE
```

---

### 6. Update User Profile
```
POST http://localhost/Laravel12AdminAPI/public/api/update/profile
```
**Auth Required:** ✅ Yes (Bearer Token)

**Headers:**
```
Authorization: Bearer YOUR_TOKEN_HERE
Content-Type: multipart/form-data
```

**Request Body (Form Data):**
```
first_name: John
last_name: Doe Updated
phone: +1234567890
profile: [image file]
```

---

### 7. Delete User Profile
```
POST http://localhost/Laravel12AdminAPI/public/api/delete/profile
```
**Auth Required:** ✅ Yes (Bearer Token)

**Headers:**
```
Authorization: Bearer YOUR_TOKEN_HERE
```

---

### 8. Change Password
```
POST http://localhost/Laravel12AdminAPI/public/api/change/password
```
**Auth Required:** ✅ Yes (Bearer Token)

**Headers:**
```
Authorization: Bearer YOUR_TOKEN_HERE
```

**Request Body:**
```json
{
    "current_password": "oldpassword123",
    "new_password": "newpassword123",
    "confirm_password": "newpassword123"
}
```

---

### 9. Forgot Password
```
POST http://localhost/Laravel12AdminAPI/public/api/forgot-password
```
**Auth Required:** ❌ No

**Request Body:**
```json
{
    "email": "john@example.com"
}
```

---

### 10. Reset Password
```
POST http://localhost/Laravel12AdminAPI/public/api/reset-password
```
**Auth Required:** ❌ No

**Request Body:**
```json
{
    "email": "john@example.com",
    "token": "reset_token_from_email",
    "password": "newpassword123",
    "password_confirmation": "newpassword123"
}
```

---

## 🎨 Admin Panel URLs

### Authentication

#### Admin Login Page
```
GET http://localhost/Laravel12AdminAPI/public/admin/login
```

#### Admin Login Check
```
POST http://localhost/Laravel12AdminAPI/public/admin/login/check
```

#### Admin Logout
```
POST http://localhost/Laravel12AdminAPI/public/admin/logout
```

---

### Dashboard

#### Admin Dashboard
```
GET http://localhost/Laravel12AdminAPI/public/admin/dashboard
```

---

### Category Management

#### List All Categories (DataTable)
```
GET http://localhost/Laravel12AdminAPI/public/admin/categories
```
**Returns:** DataTable with AJAX data

---

#### Show Create Category Form
```
GET http://localhost/Laravel12AdminAPI/public/admin/categories/create
```

---

#### Store New Category
```
POST http://localhost/Laravel12AdminAPI/public/admin/categories
```

**Request Body (Form Data):**
```
name: Category Name
description: Category Description
image: [image file]
parent_id: 1 (optional)
status: 1 (boolean)
sort_order: 0 (integer)
```

---

#### Show Edit Category Form
```
GET http://localhost/Laravel12AdminAPI/public/admin/categories/{id}/edit
```

**Example:**
```
GET http://localhost/Laravel12AdminAPI/public/admin/categories/1/edit
```

---

#### Update Category
```
PUT http://localhost/Laravel12AdminAPI/public/admin/categories/{id}
PATCH http://localhost/Laravel12AdminAPI/public/admin/categories/{id}
```

**Example:**
```
PUT http://localhost/Laravel12AdminAPI/public/admin/categories/1
```

**Request Body (Form Data):**
```
_method: PUT
name: Updated Category Name
description: Updated Description
image: [new image file] (optional)
parent_id: 2 (optional)
status: 1
sort_order: 5
```

---

#### Delete Category
```
DELETE http://localhost/Laravel12AdminAPI/public/admin/categories/{id}
```

**Example:**
```
DELETE http://localhost/Laravel12AdminAPI/public/admin/categories/1
```

**Response:** JSON with success/error message

---

#### Toggle Category Status (AJAX)
```
POST http://localhost/Laravel12AdminAPI/public/admin/categories/{id}/toggle-status
```

**Example:**
```
POST http://localhost/Laravel12AdminAPI/public/admin/categories/1/toggle-status
```

**Request Body:**
```json
{
    "id": 1
}
```

**Response:**
```json
{
    "success": true,
    "message": "Status updated successfully!",
    "status": true
}
```

---

### User Management

#### List All Users
```
GET http://localhost/Laravel12AdminAPI/public/admin/users
```

#### Create User
```
GET http://localhost/Laravel12AdminAPI/public/admin/users/create
POST http://localhost/Laravel12AdminAPI/public/admin/users
```

#### Edit User
```
GET http://localhost/Laravel12AdminAPI/public/admin/users/{id}/edit
PUT http://localhost/Laravel12AdminAPI/public/admin/users/{id}
```

#### Delete User
```
DELETE http://localhost/Laravel12AdminAPI/public/admin/users/{id}
```

---

### Product Management

#### List All Products
```
GET http://localhost/Laravel12AdminAPI/public/admin/products
```

#### Create Product
```
GET http://localhost/Laravel12AdminAPI/public/admin/products/create
POST http://localhost/Laravel12AdminAPI/public/admin/products
```

#### Edit Product
```
GET http://localhost/Laravel12AdminAPI/public/admin/products/{id}/edit
PUT http://localhost/Laravel12AdminAPI/public/admin/products/{id}
```

#### Delete Product
```
DELETE http://localhost/Laravel12AdminAPI/public/admin/products/{id}
```

---

### Profile Management

#### View Profile
```
GET http://localhost/Laravel12AdminAPI/public/admin/profile
```

#### Update Profile
```
POST http://localhost/Laravel12AdminAPI/public/admin/profile
```

#### Change Password Page
```
GET http://localhost/Laravel12AdminAPI/public/admin/change_password
```

#### Update Password
```
POST http://localhost/Laravel12AdminAPI/public/admin/change_password
```

---

## 📋 Quick Copy-Paste URLs

### API Endpoints (Copy-Paste Ready)

```
# Register
POST http://localhost/Laravel12AdminAPI/public/api/register

# Login
POST http://localhost/Laravel12AdminAPI/public/api/login

# Logout
POST http://localhost/Laravel12AdminAPI/public/api/logout

# Refresh Token
POST http://localhost/Laravel12AdminAPI/public/api/refresh

# Get Profile
GET http://localhost/Laravel12AdminAPI/public/api/profile

# Update Profile
POST http://localhost/Laravel12AdminAPI/public/api/update/profile

# Delete Profile
POST http://localhost/Laravel12AdminAPI/public/api/delete/profile

# Change Password
POST http://localhost/Laravel12AdminAPI/public/api/change/password

# Forgot Password
POST http://localhost/Laravel12AdminAPI/public/api/forgot-password

# Reset Password
POST http://localhost/Laravel12AdminAPI/public/api/reset-password
```

### Admin Panel URLs (Copy-Paste Ready)

```
# Admin Login
GET http://localhost/Laravel12AdminAPI/public/admin/login

# Admin Dashboard
GET http://localhost/Laravel12AdminAPI/public/admin/dashboard

# Categories List
GET http://localhost/Laravel12AdminAPI/public/admin/categories

# Create Category
GET http://localhost/Laravel12AdminAPI/public/admin/categories/create

# Edit Category (replace {id} with actual ID)
GET http://localhost/Laravel12AdminAPI/public/admin/categories/{id}/edit

# Users List
GET http://localhost/Laravel12AdminAPI/public/admin/users

# Products List
GET http://localhost/Laravel12AdminAPI/public/admin/products

# Profile
GET http://localhost/Laravel12AdminAPI/public/admin/profile
```

---

## 🧪 Testing Commands

### cURL Examples

#### Register User
```bash
curl -X POST http://localhost/Laravel12AdminAPI/public/api/register \
  -H "Content-Type: application/json" \
  -d '{"first_name":"John","last_name":"Doe","email":"john@example.com","phone":"+1234567890","password":"password123"}'
```

#### Login User
```bash
curl -X POST http://localhost/Laravel12AdminAPI/public/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"john@example.com","password":"password123"}'
```

#### Get Profile (Replace TOKEN)
```bash
curl -X GET http://localhost/Laravel12AdminAPI/public/api/profile \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

#### Update Profile (Replace TOKEN)
```bash
curl -X POST http://localhost/Laravel12AdminAPI/public/api/update/profile \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -F "first_name=John" \
  -F "last_name=Doe Updated" \
  -F "phone=+1234567890" \
  -F "profile=@/path/to/image.jpg"
```

---

## 📝 Notes

1. **Replace `localhost`** with your actual domain in production
2. **Replace `{id}`** with actual resource ID (e.g., 1, 2, 3)
3. **Replace `YOUR_TOKEN_HERE`** with actual JWT token from login response
4. **Token Expiration:** JWT tokens expire after 60 minutes
5. **Image Upload:** Maximum file size is 2MB
6. **Password Requirements:** Minimum 8 characters

---

## ✅ Status

**All endpoints are tested and working!** ✨

---

**Last Updated:** December 6, 2025  
**Version:** 1.0.0
