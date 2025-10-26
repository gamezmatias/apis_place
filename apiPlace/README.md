# Places API

A simple and robust RESTful API for managing places built with Laravel 12 and PostgreSQL. This API provides full CRUD operations for places with search functionality and comprehensive testing.

## Features

- ✅ **Full CRUD Operations**: Create, Read, Update, Delete places
- ✅ **Search Functionality**: Filter places by name
- ✅ **Pagination**: Efficient data retrieval with customizable page sizes
- ✅ **Auto-generated Slugs**: Automatic slug generation from place names
- ✅ **Comprehensive Validation**: Request validation with meaningful error messages
- ✅ **PostgreSQL Database**: Robust and scalable database solution
- ✅ **Docker Support**: Easy deployment with Docker and Docker Compose
- ✅ **Extensive Testing**: Feature and unit tests with Pest PHP
- ✅ **API Documentation**: Clear endpoint documentation with examples

## Requirements

- PHP 8.3+ (required for Laravel 12 and development dependencies)
- PostgreSQL 16+
- Composer
- Docker & Docker Compose (for containerized deployment)

## Place Model

Each place contains the following fields:

| Field | Type | Description |
|-------|------|-------------|
| `id` | Integer | Primary key (auto-increment) |
| `name` | String | Place name (required, unique) |
| `slug` | String | URL-friendly identifier (auto-generated from name) |
| `city` | String | City where the place is located (required) |
| `state` | String | State/province where the place is located (required) |
| `created_at` | Timestamp | Creation date |
| `updated_at` | Timestamp | Last update date |

## Quick Start with Docker

The easiest way to get started is using Docker:

```bash
# Clone the repository
git clone https://github.com/gamezmatias/apis_places.git
cd apiPlace

# Build and start the services (first time)
docker-compose up --build -d

# For subsequent runs, use:
docker-compose up -d

# The API will be available at http://localhost:8000
```

That's it! The application will automatically:
- Set up PostgreSQL database with persistent storage
- Run migrations
- Seed sample data
- Configure nginx + php-fpm
- Start all services with health checks

### Verify Everything is Working

```bash
# Check container status
docker-compose ps

# Check logs
docker-compose logs app

# Test the API
curl http://localhost:8000/api/places
```

### Recent Docker Improvements (v2.0)

**🚀 Major Updates:**
- **PHP 8.3**: Upgraded from PHP 8.2 for full Laravel 12 compatibility
- **Fixed Volume Mounting**: Resolved vendor/autoload.php issues with anonymous volumes
- **Development Dependencies**: Included dev dependencies for complete functionality
- **Enhanced Troubleshooting**: Added comprehensive error resolution guide

**🔧 Technical Fixes:**
- Added `postgresql-client` to Dockerfile for database connectivity checks
- Configured anonymous volumes to preserve `vendor` and `node_modules`
- Removed `--no-dev` flag from composer install for PailServiceProvider compatibility
- Updated all documentation to reflect current configuration

## Manual Installation

### 1. Install Dependencies

```bash
composer install
```

### 2. Environment Configuration

```bash
# Copy environment configuration
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 3. Database Setup

Configure your PostgreSQL database in `.env`:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=places_api
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 4. Run Migrations and Seeders

```bash
# Run migrations
php artisan migrate

# Seed sample data (optional)
php artisan db:seed
```

### 5. Start the Server

```bash
php artisan serve
```

The API will be available at `http://localhost:8000`

## API Endpoints

### Base URL
```
http://localhost:8000/api
```

### Endpoints Overview

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/places` | List all places (with pagination and search) |
| POST | `/places` | Create a new place |
| GET | `/places/{id}` | Get a specific place |
| PUT | `/places/{id}` | Update a place |
| DELETE | `/places/{id}` | Delete a place |

---

### 1. List Places

**GET** `/api/places`

#### Query Parameters

| Parameter | Type | Description | Default |
|-----------|------|-------------|---------|
| `name` | string | Filter places by name (partial match) | - |
| `per_page` | integer | Number of items per page | 15 |
| `page` | integer | Page number | 1 |

#### Example Request

```bash
curl -X GET "http://localhost:8000/api/places?name=park&per_page=10&page=1"
```

#### Example Response

```json
{
  "current_page": 1,
  "data": [
    {
      "id": 1,
      "name": "Central Park",
      "slug": "central-park",
      "city": "New York",
      "state": "New York",
      "created_at": "2025-10-25T10:30:00.000000Z",
      "updated_at": "2025-10-25T10:30:00.000000Z"
    }
  ],
  "first_page_url": "http://localhost:8000/api/places?page=1",
  "from": 1,
  "last_page": 1,
  "last_page_url": "http://localhost:8000/api/places?page=1",
  "links": [...],
  "next_page_url": null,
  "path": "http://localhost:8000/api/places",
  "per_page": 15,
  "prev_page_url": null,
  "to": 1,
  "total": 1
}
```

---

### 2. Create Place

**POST** `/api/places`

#### Request Body

```json
{
  "name": "Beautiful Beach",
  "city": "Miami",
  "state": "Florida",
  "slug": "beautiful-beach" // Optional - auto-generated if not provided
}
```

#### Example Request

```bash
curl -X POST "http://localhost:8000/api/places" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Beautiful Beach",
    "city": "Miami",
    "state": "Florida"
  }'
```

#### Example Response

```json
{
  "data": {
    "id": 2,
    "name": "Beautiful Beach",
    "slug": "beautiful-beach",
    "city": "Miami",
    "state": "Florida",
    "created_at": "2025-10-25T10:30:00.000000Z",
    "updated_at": "2025-10-25T10:30:00.000000Z"
  }
}
```

---

### 3. Get Specific Place

**GET** `/api/places/{id}`

#### Example Request

```bash
curl -X GET "http://localhost:8000/api/places/1"
```

#### Example Response

```json
{
  "data": {
    "id": 1,
    "name": "Central Park",
    "slug": "central-park",
    "city": "New York",
    "state": "New York",
    "created_at": "2025-10-25T10:30:00.000000Z",
    "updated_at": "2025-10-25T10:30:00.000000Z"
  }
}
```

---

### 4. Update Place

**PUT** `/api/places/{id}`

#### Request Body
You can update any combination of fields:

```json
{
  "name": "Updated Place Name",
  "city": "Updated City",
  "state": "Updated State",
  "slug": "custom-slug" // Optional
}
```

#### Example Request

```bash
curl -X PUT "http://localhost:8000/api/places/1" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Central Park Updated",
    "city": "New York City"
  }'
```

#### Example Response

```json
{
  "data": {
    "id": 1,
    "name": "Central Park Updated",
    "slug": "central-park-updated",
    "city": "New York City",
    "state": "New York",
    "created_at": "2025-10-25T10:30:00.000000Z",
    "updated_at": "2025-10-25T11:45:00.000000Z"
  }
}
```

---

### 5. Delete Place

**DELETE** `/api/places/{id}`

#### Example Request

```bash
curl -X DELETE "http://localhost:8000/api/places/1"
```

#### Example Response

```json
{
  "message": "Place deleted successfully"
}
```

---

## Error Responses

The API returns standard HTTP status codes and JSON error responses:

### Validation Errors (422)

```json
{
  "message": "The given data was invalid.",
  "errors": {
    "name": ["The name field is required."],
    "city": ["The city field is required."]
  }
}
```

### Not Found (404)

```json
{
  "message": "No query results for model [App\\Models\\Place] 1"
}
```

### Server Error (500)

```json
{
  "message": "Server Error"
}
```

---

## Testing

This project includes comprehensive test coverage using Pest PHP.

### Run All Tests

```bash
# Run all tests
php artisan test

# Run tests with coverage
php artisan test --coverage
```

### Run Specific Test Types

```bash
# Run only feature tests
php artisan test tests/Feature/

# Run only unit tests
php artisan test tests/Unit/
```

### Test Structure

- **Feature Tests**: Test API endpoints end-to-end
- **Unit Tests**: Test individual service methods and business logic

## Architecture

The application follows Laravel best practices and clean architecture principles:

```
app/
├── Http/
│   ├── Controllers/
│   │   └── PlaceController.php      # API endpoint controller
│   └── Requests/
│       ├── StorePlaceRequest.php    # Create validation
│       └── UpdatePlaceRequest.php   # Update validation
├── Models/
│   └── Place.php                    # Eloquent model
└── Services/
    └── PlaceService.php             # Business logic layer

database/
├── factories/
│   └── PlaceFactory.php             # Model factory for testing
├── migrations/
│   └── *_create_places_table.php    # Database schema
└── seeders/
    ├── DatabaseSeeder.php           # Main seeder
    └── PlaceSeeder.php              # Sample data seeder

tests/
├── Feature/
│   └── PlaceApiTest.php             # API endpoint tests
└── Unit/
    └── PlaceServiceTest.php         # Service layer tests
```

## Key Features

### Auto-generated Slugs
When creating or updating a place, if no slug is provided, it will be automatically generated from the place name using Laravel's `Str::slug()` helper.

### Comprehensive Validation
- **Create**: All fields required except slug
- **Update**: Partial updates supported, validation respects existing records
- **Unique Constraints**: Names and slugs must be unique

### Pagination
All list endpoints return paginated results with metadata including:
- Current page, total pages
- Items per page, total items
- Navigation URLs

### Search Functionality
Search places by name using case-insensitive partial matching (PostgreSQL ILIKE).

---

## Docker Services

The application runs with the following services:

| Service | Image | Port | Description |
|---------|-------|------|-------------|
| **app** | Custom (PHP 8.3-fpm + nginx) | 8000 | Laravel application |
| **postgres** | postgres:16-alpine | 5432 | PostgreSQL database |
| **redis** | redis:7-alpine | 6379 | Redis cache |

### Service Configuration

- **PostgreSQL**: Database `places_api`, user `postgres`, password `password`
- **Persistent Data**: PostgreSQL data is stored in `postgres_data` volume
- **Dependencies**: `vendor` and `node_modules` are preserved in anonymous volumes
- **Health Checks**: All services include health checks for reliability
- **Networking**: Services communicate through `places-network`

---

## Troubleshooting

### Docker Issues

**Problem**: API returns "socket hang up" or connection refused
```bash
# Check if containers are running
docker-compose ps

# Check application logs
docker-compose logs app

# Restart services
docker-compose down && docker-compose up -d
```

**Problem**: Database connection issues
```bash
# Check PostgreSQL health
docker-compose logs postgres

# Connect to database directly
docker-compose exec postgres psql -U postgres -d places_api
```

**Problem**: App container won't start (waiting for PostgreSQL)
```bash
# This was fixed by adding postgresql-client to Dockerfile
# If you encounter this, rebuild the image:
docker-compose down
docker-compose up --build -d
```

**Problem**: "Failed to open stream: No such file or directory" (vendor/autoload.php)
```bash
# This occurs when volumes overwrite the container's vendor directory
# Fixed by adding anonymous volumes in docker-compose.yml:
# volumes:
#   - .:/var/www/html
#   - /var/www/html/vendor      # Preserves vendor from build
#   - /var/www/html/node_modules # Preserves node_modules from build
```

**Problem**: "Class 'Laravel\Pail\PailServiceProvider' not found"
```bash
# This occurs with PHP version incompatibility
# Fixed by upgrading to PHP 8.3 in Dockerfile and including dev dependencies:
# FROM php:8.3-fpm
# RUN composer install --optimize-autoloader (without --no-dev)
```

**Problem**: Dependency version conflicts during composer install
```bash
# Check PHP version compatibility:
docker-compose exec app php -v

# If using PHP 8.2, some Laravel 12 dev dependencies require PHP 8.3+
# Solution: Upgrade Dockerfile to use php:8.3-fpm
```

### Manual Installation Issues

**Problem**: Database connection failed
- Verify PostgreSQL is running and accessible
- Check database credentials in `.env`
- Ensure database `places_api` exists

**Problem**: Migration errors
```bash
# Reset database
php artisan migrate:fresh --seed
```

---

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests for new functionality
5. Ensure all tests pass
6. Submit a pull request

---

## Version Information

**Current Version:** 2.0  
**Laravel:** 12.x  
**PHP:** 8.3+  
**Database:** PostgreSQL 16  

**Last Updated:** October 2025  
**Status:** ✅ Production Ready


