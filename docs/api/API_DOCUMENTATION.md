# Madrasah Aliyah Nusantara API Documentation

## Overview

This document describes the RESTful API for Madrasah Aliyah Nusantara school management system.

**Base URL**: `https://your-domain.com/api`  
**Version**: v1  
**Authentication**: Bearer Token (Sanctum)

## Authentication

All API endpoints (except public ones) require authentication using Laravel Sanctum.

### Headers
```
Content-Type: application/json
Authorization: Bearer {token}
Accept: application/json
```

### Rate Limiting
- **General API**: 60 requests/minute
- **Authentication**: 5 requests/minute
- **Students API**: 30 requests/minute
- **Admin API**: 100 requests/minute (admin), 10 requests/minute (non-admin)

## Response Format

### Success Response
```json
{
    "success": true,
    "data": {},
    "message": "Operation successful"
}
```

### Error Response
```json
{
    "success": false,
    "message": "Error description",
    "errors": {}
}
```

## Endpoints

### Students API

#### Get All Students
```http
GET /api/students
```

**Response:**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "name": "John Doe",
            "nis": "123456789",
            "email": "john@example.com",
            "phone": "08123456789",
            "class": "XII IPA 1",
            "created_at": "2024-01-01T00:00:00.000000Z",
            "updated_at": "2024-01-01T00:00:00.000000Z"
        }
    ]
}
```

#### Create Student
```http
POST /api/students
```

**Request Body:**
```json
{
    "name": "John Doe",
    "nis": "123456789",
    "email": "john@example.com",
    "phone": "08123456789",
    "class": "XII IPA 1"
}
```

**Response:** `201 Created`
```json
{
    "success": true,
    "data": {
        "id": 1,
        "name": "John Doe",
        "nis": "123456789",
        "email": "john@example.com",
        "phone": "08123456789",
        "class": "XII IPA 1"
    },
    "message": "Student berhasil ditambahkan"
}
```

#### Get Student by ID
```http
GET /api/students/{id}
```

#### Update Student
```http
PUT /api/students/{id}
```

**Request Body:** Same as create student

#### Delete Student
```http
DELETE /api/students/{id}
```

**Response:** `200 OK`
```json
{
    "success": true,
    "message": "Student berhasil dihapus"
}
```

### Data API

#### Get Login Data
```http
GET /api/data/login
```

#### Get Profile Data
```http
GET /api/data/profile/{id}
```

#### Get PPDB Data
```http
GET /api/data/ppdb
GET /api/data/ppdb/{id}
```

#### Get Bagus Data
```http
GET /api/data/bagus
```

## Error Codes

| Status Code | Description |
|-------------|-------------|
| 200 | OK |
| 201 | Created |
| 400 | Bad Request |
| 401 | Unauthorized |
| 403 | Forbidden |
| 404 | Not Found |
| 422 | Validation Error |
| 429 | Too Many Requests |
| 500 | Internal Server Error |

## Validation Rules

### Student Validation
- `name`: required, string, max:255
- `nis`: required, string, max:20, unique
- `email`: required, email, unique
- `phone`: optional, string, max:20
- `class`: optional, string, max:50

## SDK Examples

### JavaScript (Axios)
```javascript
const axios = require('axios');

const api = axios.create({
    baseURL: 'https://your-domain.com/api',
    headers: {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${token}`
    }
});

// Get all students
const getStudents = async () => {
    try {
        const response = await api.get('/students');
        return response.data;
    } catch (error) {
        console.error('Error:', error.response.data);
    }
};
```

### PHP (Guzzle)
```php
use GuzzleHttp\Client;

$client = new Client([
    'base_uri' => 'https://your-domain.com/api',
    'headers' => [
        'Authorization' => 'Bearer ' . $token,
        'Content-Type' => 'application/json'
    ]
]);

$response = $client->get('/students');
$students = json_decode($response->getBody(), true);
```

## Testing

Use the provided test suite:
```bash
php artisan test --filter=StudentApiTest
```

## Support

For API support, contact the development team or create an issue in the repository.
