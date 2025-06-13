# Laravel BestWeb API

A modern Laravel-based REST API for managing products and categories with authentication.

## Project Setup Instructions

### Prerequisites
- PHP 8.2 or higher
- Composer
- Node.js and NPM
- MySQL or SQLite database

### Installation Steps

1. Clone the repository:
```bash
git clone <repository-url>
cd laravel-bestweb
```

2. Install PHP dependencies:
```bash
composer install
```

3. Install Node.js dependencies:
```bash
npm install
```

4. Set up environment:
```bash
cp .env.example .env
php artisan key:generate
```

5. Configure your database in `.env` file:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

6. Run migrations and seeders:
```bash
php artisan migrate --seed
```

7. Start the development server:
```bash
php artisan serve
```

8. In a separate terminal, start the frontend development server:
```bash
npm run dev
```

### Default Login Credentials
```
Email: admin@example.com
Password: password
```

### API Documentation
The API documentation is available at:
```
http://localhost:8000/api/documentation
```

## API Documentation

### API Architecture

The application implements two distinct API layers:

1. **External API** (`/api/*`)
   - Public-facing REST API endpoints
   - Requires authentication via Laravel Sanctum
   - Used by external clients and third-party applications
   - Located in `routes/api.php`

2. **Internal API** (`/admin/*`)
   - Admin dashboard specific endpoints
   - Protected by web authentication middleware
   - Used by the internal admin interface
   - Located in `routes/web.php`

### External API Endpoints

#### Authentication Endpoints

- `POST /api/register` - Register a new user
- `POST /api/login` - Login user
- `POST /api/logout` - Logout user (requires authentication)

#### Product Endpoints

All product endpoints require authentication.

- `GET /api/products` - List all products
- `GET /api/products/{id}` - Get a specific product
- `POST /api/products` - Create a new product
- `PUT /api/products/{id}` - Update a product
- `DELETE /api/products/{id}` - Delete a product
- `POST /api/products/bulk-delete` - Delete multiple products
- `GET /api/export/products` - Export products data

#### Category Endpoints

All category endpoints require authentication.

- `GET /api/categories` - List all categories
- `GET /api/categories/{id}` - Get a specific category
- `POST /api/categories` - Create a new category
- `PUT /api/categories/{id}` - Update a category
- `DELETE /api/categories/{id}` - Delete a category
- `POST /api/categories/bulk-delete` - Delete multiple categories

### Web Routes & Admin Dashboard

The application includes a web-based admin dashboard with the following routes:

#### Main Pages
- `/` - Products dashboard (requires authentication)
- `/products` - Products management page
- `/categories` - Categories management page
- `/profile` - User profile management


## Assumptions and Design Choices

### Authentication
- Using Laravel Sanctum for API authentication
- Token-based authentication for secure API access
- Protected routes using middleware

### Database
- Using Laravel's Eloquent ORM for database operations
- Implemented relationships between Products and Categories
- Soft deletes for data integrity

### API Design
- RESTful API architecture
- Resource-based routing
- Consistent response format

### Frontend
- Using Inertia.js for seamless frontend integration
- Tailwind CSS for styling
- Vue.js for reactive components

### Development Tools
- Laravel Breeze for authentication scaffolding
- PHPUnit for testing


### Additional Features
- Excel export functionality using Maatwebsite/Excel
- API documentation using L5-Swagger

### API Architecture
- Separate internal and external API layers for better security and maintainability
- Internal API uses web authentication for admin dashboard
- External API uses token-based authentication for third-party access
- Consistent response formats across both API layers

## Testing

Run the test suite:
```bash
php artisan test
```

## Contributing

1. Fork the repository
2. Create your feature branch
3. Commit your changes
4. Push to the branch
5. Create a new Pull Request
