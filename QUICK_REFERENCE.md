# 🚀 Laravel 12 Admin API - Quick Reference Guide

## 📍 API Base URLs

### Local Development
```
http://localhost/Laravel12AdminAPI/public/api
```

### Admin Panel
```
http://localhost/Laravel12AdminAPI/public/admin
```

---

## 🔑 Complete API Endpoints List

### ✅ Authentication APIs (No Auth Required)

| Endpoint | Method | Description |
|----------|--------|-------------|
| `/api/register` | POST | Register new user |
| `/api/login` | POST | Login user and get token |
| `/api/forgot-password` | POST | Request password reset |
| `/api/reset-password` | POST | Reset password with token |

### 🔒 Protected APIs (Auth Required - Bearer Token)

| Endpoint | Method | Description |
|----------|--------|-------------|
| `/api/logout` | POST | Logout current user |
| `/api/refresh` | ANY | Refresh JWT token |
| `/api/profile` | GET | Get user profile |
| `/api/update/profile` | POST | Update user profile |
| `/api/delete/profile` | POST | Delete user account |
| `/api/change/password` | POST | Change user password |

---

## 📝 Quick API Usage Examples

### 1. Register a New User
```bash
POST /api/register
Content-Type: application/json

{
    "first_name": "John",
    "last_name": "Doe",
    "email": "john@example.com",
    "phone": "+1234567890",
    "password": "password123"
}
```

### 2. Login
```bash
POST /api/login
Content-Type: application/json

{
    "email": "john@example.com",
    "password": "password123"
}

Response:
{
    "token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
    "user": {...}
}
```

### 3. Get Profile (Authenticated)
```bash
GET /api/profile
Authorization: Bearer YOUR_TOKEN_HERE
```

### 4. Update Profile (Authenticated)
```bash
POST /api/update/profile
Authorization: Bearer YOUR_TOKEN_HERE
Content-Type: multipart/form-data

Form Data:
- first_name: John
- last_name: Doe
- phone: +1234567890
- profile: [image file]
```

### 5. Change Password (Authenticated)
```bash
POST /api/change/password
Authorization: Bearer YOUR_TOKEN_HERE
Content-Type: application/json

{
    "current_password": "oldpassword",
    "new_password": "newpassword",
    "confirm_password": "newpassword"
}
```

### 6. Logout (Authenticated)
```bash
POST /api/logout
Authorization: Bearer YOUR_TOKEN_HERE
```

---

## 🎯 Admin Panel Routes

### Category Management

| Route | Method | Description |
|-------|--------|-------------|
| `/admin/categories` | GET | List all categories (DataTable) |
| `/admin/categories/create` | GET | Show create form |
| `/admin/categories` | POST | Store new category |
| `/admin/categories/{id}/edit` | GET | Show edit form |
| `/admin/categories/{id}` | PUT | Update category |
| `/admin/categories/{id}` | DELETE | Delete category |
| `/admin/categories/{id}/toggle-status` | POST | Toggle status (AJAX) |

### User Management
| Route | Method | Description |
|-------|--------|-------------|
| `/admin/users` | GET | List all users |
| `/admin/users/create` | GET | Show create form |
| `/admin/users` | POST | Store new user |
| `/admin/users/{id}/edit` | GET | Show edit form |
| `/admin/users/{id}` | PUT | Update user |
| `/admin/users/{id}` | DELETE | Delete user |

### Product Management
| Route | Method | Description |
|-------|--------|-------------|
| `/admin/products` | GET | List all products |
| `/admin/products/create` | GET | Show create form |
| `/admin/products` | POST | Store new product |
| `/admin/products/{id}/edit` | GET | Show edit form |
| `/admin/products/{id}` | PUT | Update product |
| `/admin/products/{id}` | DELETE | Delete product |

---

## 🔧 Testing with cURL

### Register User
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

### Login User
```bash
curl -X POST http://localhost/Laravel12AdminAPI/public/api/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "john@example.com",
    "password": "password123"
  }'
```

### Get Profile (Replace TOKEN with actual token)
```bash
curl -X GET http://localhost/Laravel12AdminAPI/public/api/profile \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

### Update Profile with Image
```bash
curl -X POST http://localhost/Laravel12AdminAPI/public/api/update/profile \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -F "first_name=John" \
  -F "last_name=Doe Updated" \
  -F "phone=+1234567890" \
  -F "profile=@/path/to/image.jpg"
```

### Change Password
```bash
curl -X POST http://localhost/Laravel12AdminAPI/public/api/change/password \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -d '{
    "current_password": "password123",
    "new_password": "newpassword123",
    "confirm_password": "newpassword123"
  }'
```

### Logout
```bash
curl -X POST http://localhost/Laravel12AdminAPI/public/api/logout \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

---

## 📱 Testing with Postman

1. **Import Collection:**
   - Open Postman
   - Click "Import"
   - Select `Laravel12_Admin_API.postman_collection.json`

2. **Set Environment Variables:**
   - `base_url`: `http://localhost/Laravel12AdminAPI/public`
   - `auth_token`: (Auto-populated after login)

3. **Test Flow:**
   - Run "Register User" or "Login User"
   - Token will be automatically saved
   - Use other authenticated endpoints

---

## ⚙️ Configuration

### Database Setup
1. Update `.env` file:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

2. Run migrations:
```bash
php artisan migrate
```

### JWT Configuration
```env
JWT_SECRET=your_jwt_secret_key
JWT_TTL=60  # Token expiration in minutes
```

---

## 📊 Response Formats

### Success Response
```json
{
    "status": "success",
    "message": "Operation successful",
    "data": {...}
}
```

### Error Response
```json
{
    "status": "error",
    "message": "Error description",
    "errors": {...}
}
```

### Validation Error
```json
{
    "status": "error",
    "message": "Validation failed",
    "errors": {
        "field_name": ["Error message"]
    }
}
```

---

## 🎨 Category Module Features

✅ **Implemented Features:**
- Create, Read, Update, Delete (CRUD) operations
- DataTables integration with server-side processing
- Image upload and management
- Parent-child category relationships
- Status toggle (Active/Inactive)
- Soft delete functionality
- Sort ordering
- Search and filter capabilities
- AJAX-based operations
- Responsive design

---

## 📋 Field Validations

### Registration
- `first_name`: Required, string, max 255 characters
- `last_name`: Optional, string, max 255 characters
- `email`: Required, valid email, unique
- `phone`: Required, string
- `password`: Required, min 8 characters

### Category
- `name`: Required, string, max 255 characters, unique
- `description`: Optional, text
- `image`: Optional, image (jpeg, png, jpg, gif), max 2MB
- `parent_id`: Optional, must exist in categories table
- `status`: Boolean (true/false)
- `sort_order`: Optional, integer

---

## 🔐 Security Features

- JWT Authentication
- Password hashing (bcrypt)
- CSRF protection
- SQL injection protection
- XSS protection
- File upload validation
- Token expiration (60 minutes)
- Soft delete for data recovery

---

## 📞 Support & Documentation

- **Full Documentation:** `API_DOCUMENTATION.md`
- **Postman Collection:** `Laravel12_Admin_API.postman_collection.json`
- **Admin Panel:** `http://localhost/Laravel12AdminAPI/public/admin/login`

---

## ✨ Quick Start Checklist

- [ ] Configure `.env` file
- [ ] Run `php artisan migrate`
- [ ] Create storage link: `php artisan storage:link`
- [ ] Test API registration endpoint
- [ ] Test API login endpoint
- [ ] Access admin panel
- [ ] Test category module
- [ ] Import Postman collection

---

**Last Updated:** December 6, 2025  
**Version:** 1.0.0
