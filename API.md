# API Documentation

## Authentication Endpoints

### Login
- **URL:** `/api/auth/login`
- **Method:** `POST`
- **Description:** Authenticate a user and receive an access token
- **Request Body:**
```json
{
    "email": "user@example.com",
    "password": "password"
}
```
- **Success Response:**
```json
{
    "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...",
    "token_type": "Bearer"
}
```

### Register
- **URL:** `/api/auth/register`
- **Method:** `POST`
- **Description:** Register a new user
- **Request Body:**
```json
{
    "name": "John Doe",
    "email": "user@example.com",
    "password": "password",
    "password_confirmation": "password"
}
```

## User Endpoints

### Get User Profile
- **URL:** `/api/user/profile`
- **Method:** `GET`
- **Description:** Get the authenticated user's profile
- **Headers Required:** `Authorization: Bearer {token}`

### Update User Profile
- **URL:** `/api/user/profile`
- **Method:** `PUT`
- **Description:** Update the authenticated user's profile
- **Headers Required:** `Authorization: Bearer {token}`
- **Request Body:**
```json
{
    "name": "Updated Name",
    "email": "newemail@example.com"
}
```

## Error Responses

### Common Error Format
```json
{
    "message": "Error message here",
    "errors": {
        "field": [
            "Error description"
        ]
    }
}
```

### HTTP Status Codes
- `200` - Success
- `201` - Created
- `400` - Bad Request
- `401` - Unauthorized
- `403` - Forbidden
- `404` - Not Found
- `422` - Validation Error
- `500` - Server Error 