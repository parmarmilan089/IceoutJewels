# 🚀 Laravel 12 Admin API

A complete Laravel 12 Admin Panel with RESTful API, JWT Authentication, and Category Management System.

![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.x-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-Database-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![JWT](https://img.shields.io/badge/JWT-Auth-000000?style=for-the-badge&logo=json-web-tokens&logoColor=white)

---

## 📋 Table of Contents

- [Features](#-features)
- [API Endpoints](#-api-endpoints)
- [Installation](#-installation)
- [Usage](#-usage)
- [Documentation](#-documentation)
- [Testing](#-testing)
- [Admin Panel](#-admin-panel)
- [Tech Stack](#-tech-stack)

---

## ✨ Features

### 🔐 Authentication System
- ✅ User Registration
- ✅ User Login with JWT Token
- ✅ Token Refresh
- ✅ User Profile Management
- ✅ Password Change
- ✅ Password Reset (Forgot Password)
- ✅ Profile Image Upload
- ✅ Account Deletion
- ✅ Logout

### 📦 Category Management (Admin Panel)
- ✅ Complete CRUD Operations
- ✅ DataTables Integration
- ✅ Image Upload & Management
- ✅ Parent-Child Relationships (Hierarchical)
- ✅ AJAX Status Toggle
- ✅ Soft Delete
- ✅ Sort Ordering
- ✅ Search & Filter
- ✅ Responsive Design

---

## 🌐 API Endpoints

### Base URL
```
http://localhost/Laravel12AdminAPI/public/api
```

### Authentication Endpoints

| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| `POST` | `/register` | ❌ | Register new user |
| `POST` | `/login` | ❌ | Login and get JWT token |
| `POST` | `/logout` | ✅ | Logout user |
| `ANY` | `/refresh` | ✅ | Refresh JWT token |
| `GET` | `/profile` | ✅ | Get user profile |
| `POST` | `/update/profile` | ✅ | Update profile |
| `POST` | `/delete/profile` | ✅ | Delete account |
| `POST` | `/change/password` | ✅ | Change password |
| `POST` | `/forgot-password` | ❌ | Request password reset |
| `POST` | `/reset-password` | ❌ | Reset password |

---

## 🔧 Installation

### Prerequisites
- PHP >= 8.0
- Composer
- MySQL
- Laravel 12

### Steps

1. **Clone or navigate to the project:**
```bash
cd c:\laragon\www\Laravel12AdminAPI
```

2. **Install dependencies:**
```bash
composer install
npm install
```

3. **Configure environment:**
```bash
cp .env.example .env
```

4. **Update `.env` file:**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password

JWT_SECRET=your_jwt_secret_key
```

5. **Generate application key:**
```bash
php artisan key:generate
```

6. **Run migrations:**
```bash
php artisan migrate
```

7. **Create storage link:**
```bash
php artisan storage:link
```

8. **Start the server:**
```bash
php artisan serve
```

---

## 💡 Usage

### Quick Start - API Testing

#### 1. Register a User
```bash
POST http://localhost/Laravel12AdminAPI/public/api/register

Body (JSON):
{
    "first_name": "John",
    "last_name": "Doe",
    "email": "john@example.com",
    "phone": "+1234567890",
    "password": "password123"
}
```

#### 2. Login
```bash
POST http://localhost/Laravel12AdminAPI/public/api/login

Body (JSON):
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

#### 3. Get Profile (Authenticated)
```bash
GET http://localhost/Laravel12AdminAPI/public/api/profile

Headers:
Authorization: Bearer YOUR_TOKEN_HERE
```

---

## 📚 Documentation

Comprehensive documentation is available in the following files:

| File | Description |
|------|-------------|
| [`API_DOCUMENTATION.md`](API_DOCUMENTATION.md) | Complete API documentation with examples |
| [`QUICK_REFERENCE.md`](QUICK_REFERENCE.md) | Quick reference guide |
| [`SETUP_COMPLETE.md`](SETUP_COMPLETE.md) | Setup summary and features |
| [`Laravel12_Admin_API.postman_collection.json`](Laravel12_Admin_API.postman_collection.json) | Postman collection |

---

## 🧪 Testing

### Using Postman (Recommended)

1. **Import Collection:**
   - Open Postman
   - Click "Import"
   - Select `Laravel12_Admin_API.postman_collection.json`

2. **Set Environment:**
   - Create new environment
   - Add variable: `base_url` = `http://localhost/Laravel12AdminAPI/public`

3. **Test Flow:**
   - Run "Register User" or "Login User"
   - Token will be auto-saved
   - Test other endpoints

### Using cURL

**Register:**
```bash
curl -X POST http://localhost/Laravel12AdminAPI/public/api/register \
  -H "Content-Type: application/json" \
  -d '{"first_name":"John","last_name":"Doe","email":"john@example.com","phone":"+1234567890","password":"password123"}'
```

**Login:**
```bash
curl -X POST http://localhost/Laravel12AdminAPI/public/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"john@example.com","password":"password123"}'
```

**Get Profile:**
```bash
curl -X GET http://localhost/Laravel12AdminAPI/public/api/profile \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

---

## 🎨 Admin Panel

### Access
```
http://localhost/Laravel12AdminAPI/public/admin/login
```

### Features

#### Category Management
- **List View:** DataTables with search, pagination, and sorting
- **Create:** Form with image upload and validation
- **Edit:** Update all fields including image replacement
- **Delete:** Soft delete with confirmation
- **Status Toggle:** AJAX-based active/inactive toggle
- **Hierarchy:** Parent-child category relationships

#### Screenshots
*(Admin panel includes responsive design with Bootstrap 4)*

---

## 🛠 Tech Stack

### Backend
- **Framework:** Laravel 12
- **Language:** PHP 8.x
- **Database:** MySQL
- **Authentication:** JWT (tymon/jwt-auth)
- **DataTables:** Yajra Laravel DataTables

### Frontend
- **CSS Framework:** Bootstrap 4
- **JavaScript:** jQuery
- **Icons:** Feather Icons
- **Alerts:** SweetAlert2
- **Tables:** DataTables

### Security
- JWT Token Authentication
- Password Hashing (Bcrypt)
- CSRF Protection
- SQL Injection Protection
- XSS Protection
- File Upload Validation

---

## 📁 Project Structure

```
Laravel12AdminAPI/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       ├── Api/
│   │       │   └── AuthController.php
│   │       └── Admin/
│   │           └── CategoryController.php
│   ├── Models/
│   │   ├── User.php
│   │   └── Category.php
│   └── Helpers/
│       └── Helper.php
├── database/
│   └── migrations/
│       └── 2025_12_06_114314_create_categories_table.php
├── resources/
│   └── views/
│       └── admin/
│           └── categories/
│               ├── index.blade.php
│               └── create.blade.php
├── routes/
│   ├── api.php
│   └── web.php
├── API_DOCUMENTATION.md
├── QUICK_REFERENCE.md
├── SETUP_COMPLETE.md
└── Laravel12_Admin_API.postman_collection.json
```

---

## 🔐 Security

- **JWT Authentication:** Secure token-based authentication
- **Password Hashing:** Bcrypt algorithm
- **Token Expiration:** 60 minutes
- **CSRF Protection:** Enabled for all forms
- **File Validation:** Image type and size validation
- **Soft Deletes:** Data recovery capability

---

## 📝 API Response Format

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

---

## 🎯 What's Working

✅ **User Registration API**  
✅ **User Login API**  
✅ **User Logout API**  
✅ **Token Refresh API**  
✅ **Get Profile API**  
✅ **Update Profile API**  
✅ **Delete Profile API**  
✅ **Change Password API**  
✅ **Forgot Password API**  
✅ **Reset Password API**  
✅ **Category CRUD (Admin Panel)**  
✅ **Category Image Upload**  
✅ **Category Status Toggle**  
✅ **Category Soft Delete**  
✅ **DataTables Integration**  

---

## 📞 Support

For detailed information, refer to:
- **API Documentation:** [`API_DOCUMENTATION.md`](API_DOCUMENTATION.md)
- **Quick Reference:** [`QUICK_REFERENCE.md`](QUICK_REFERENCE.md)
- **Setup Guide:** [`SETUP_COMPLETE.md`](SETUP_COMPLETE.md)

---

## 📄 License

This project is open-sourced software.

---

## 👨‍💻 Author

**Laravel 12 Admin API**  
Version: 1.0.0  
Created: December 6, 2025

---

## 🎉 Status

**✅ Production Ready**

All features are implemented, tested, and documented. Ready for development and production use!

---

**Happy Coding! 🚀**
