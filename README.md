# Posts Management API

A robust Laravel API backend for posts management with comprehensive authentication, CRUD operations, and image handling. Built with Laravel 11/12, Sanctum authentication, and designed to work seamlessly with a React frontend.

## 🚀 Features

- ✅ **Complete Authentication System**: User registration, login, logout with Sanctum
- ✅ **JWT Token Management**: Secure API authentication with Laravel Sanctum
- ✅ **Posts CRUD Operations**: Full Create, Read, Update, Delete functionality
- ✅ **Image Upload & Storage**: File upload handling with validation
- ✅ **CORS Configuration**: Properly configured for frontend integration
- ✅ **Database Migrations**: Structured database schema with migrations
- ✅ **API Validation**: Comprehensive request validation and error handling
- ✅ **Protected Routes**: Middleware-protected endpoints for security

## 🛠️ Technologies Used

- **Laravel 11/12** - PHP framework
- **Laravel Sanctum** - API authentication
- **MySQL/SQLite** - Database
- **Eloquent ORM** - Database interactions
- **Laravel Validation** - Request validation
- **File Storage** - Image upload handling
- **CORS Middleware** - Cross-origin request handling

## 📋 Prerequisites

Before running this application, make sure you have:

1. **PHP** (version 8.1 or higher)
2. **Composer** (latest version)
3. **MySQL/SQLite** database
4. **Web server** (Apache/Nginx or Laravel's built-in server)
5. **Node.js & npm** (for frontend development)

## 🚀 Installation & Setup

1. **Clone the repository**:
   ```bash
   git clone <repository-url>
   cd api-testing
   ```

2. **Install PHP dependencies**:
   ```bash
   composer install
   ```

3. **Environment configuration**:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Configure database** in `.env`:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=api_testing
   DB_USERNAME=root
   DB_PASSWORD=
   ```

5. **Run database migrations**:
   ```bash
   php artisan migrate
   ```

6. **Create storage link** (for image uploads):
   ```bash
   php artisan storage:link
   ```

7. **Start the development server**:
   ```bash
   php artisan serve
   ```

   The API will be available at `http://localhost:8000`

## 🔐 Authentication Endpoints

### User Registration
```http
POST /api/register
Content-Type: application/json

{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123"
}
```

### User Login
```http
POST /api/login
Content-Type: application/json

{
    "email": "john@example.com",
    "password": "password123"
}
```

### User Logout
```http
POST /api/logout
Authorization: Bearer {your-token}
```

### Get Authenticated User
```http
GET /api/user
Authorization: Bearer {your-token}
```

## 📝 Posts API Endpoints

All posts endpoints require authentication (`Authorization: Bearer {token}`)

### Get All Posts
```http
GET /api/posts
Authorization: Bearer {your-token}
```

### Create New Post
```http
POST /api/posts
Authorization: Bearer {your-token}
Content-Type: multipart/form-data

{
    "title": "Post Title",
    "description": "Post description",
    "image": [file] // Optional
}
```

### Get Specific Post
```http
GET /api/posts/{id}
Authorization: Bearer {your-token}
```

### Update Post
```http
PUT /api/posts/{id}
Authorization: Bearer {your-token}
Content-Type: multipart/form-data

{
    "title": "Updated Title",
    "description": "Updated description",
    "image": [file] // Optional
}
```

### Delete Post
```http
DELETE /api/posts/{id}
Authorization: Bearer {your-token}
```

## 📁 Project Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── AuthController.php      # Authentication logic
│   │   └── PostController.php      # Posts CRUD operations
│   ├── Middleware/               # Custom middleware
│   └── Requests/                # Form request validation
├── Models/
│   ├── User.php                # User model
│   └── Post.php                # Post model
config/
├── cors.php                    # CORS configuration
├── sanctum.php                # Sanctum configuration
└── filesystems.php           # File storage configuration
database/
├── migrations/                # Database schema
└── seeders/                  # Database seeders
routes/
├── api.php                   # API routes
└── web.php                   # Web routes
```

## 🔧 Configuration

### CORS Setup
The CORS configuration in `config/cors.php` is set up to allow requests from your frontend:

```php
'allowed_origins' => [
    'http://localhost:5173',  // Vite dev server
    'https://localhost:5173',
    'http://127.0.0.1:5173',
    'https://127.0.0.1:5173',
],
```

### Sanctum Configuration
Laravel Sanctum is configured for API token authentication. Check `config/sanctum.php` for token settings.

### File Upload Configuration
Images are stored in `storage/app/public/upload/` directory. The storage link creates a symbolic link to `public/upload/`.

## 🗄️ Database Schema

### Users Table
```sql
- id (bigint, primary key)
- name (varchar)
- email (varchar, unique)
- email_verified_at (timestamp, nullable)
- password (varchar)
- remember_token (varchar, nullable)
- created_at (timestamp)
- updated_at (timestamp)
```

### Posts Table
```sql
- id (bigint, primary key)
- title (varchar)
- description (text)
- image (varchar, nullable)
- user_id (bigint, foreign key)
- created_at (timestamp)
- updated_at (timestamp)
```

## 🔒 Security Features

- **Authentication Middleware**: All post endpoints protected with `auth:sanctum`
- **CSRF Protection**: CSRF tokens for form submissions
- **Request Validation**: Comprehensive input validation
- **File Upload Validation**: Image type and size restrictions
- **Rate Limiting**: API rate limiting to prevent abuse
- **SQL Injection Protection**: Eloquent ORM prevents SQL injection

## 🧪 Testing

Run the test suite:

```bash
# Run all tests
php artisan test

# Run specific test
php artisan test --filter=PostTest

# Run tests with coverage
php artisan test --coverage
```

## 🚨 Error Handling

The API returns structured JSON responses for all errors:

```json
{
    "message": "Error description",
    "errors": {
        "field": ["Validation error message"]
    }
}
```

Common HTTP status codes:
- `200` - Success
- `201` - Created
- `400` - Bad Request
- `401` - Unauthorized
- `403` - Forbidden
- `404` - Not Found
- `422` - Validation Error
- `500` - Server Error

## 🔧 Development Commands

```bash
# Generate new controller
php artisan make:controller PostController --api

# Create new migration
php artisan make:migration create_posts_table

# Create new model
php artisan make:model Post -m

# Clear application cache
php artisan cache:clear

# Clear configuration cache
php artisan config:clear

# Clear route cache
php artisan route:clear

# Generate API documentation
php artisan l5-swagger:generate
```

## 🌍 Environment Variables

Key environment variables in `.env`:

```env
APP_NAME="Posts API"
APP_ENV=local
APP_KEY=base64:generated-key
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=api_testing
DB_USERNAME=root
DB_PASSWORD=

SANCTUM_STATEFUL_DOMAINS=localhost:5173,127.0.0.1:5173
```

## 🚀 Deployment

### Production Setup

1. **Set environment to production**:
   ```bash
   APP_ENV=production
   APP_DEBUG=false
   ```

2. **Optimize for production**:
   ```bash
   composer install --optimize-autoloader --no-dev
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

3. **Set proper file permissions**:
   ```bash
   chmod -R 775 storage bootstrap/cache
   ```

## 🤝 Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 📄 License

This project is open source and available under the [MIT License](LICENSE).

## 🆘 Support

If you encounter any issues:

1. Check the Laravel documentation
2. Review API endpoint documentation
3. Check server logs in `storage/logs/`
4. Create an issue in the repository

---

**Built with ❤️ using Laravel, Sanctum, and modern PHP practices**
