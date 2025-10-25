# Places API Setup Instructions

## Using Docker (Recommended)

1. **Start the services:**
```bash
docker-compose up -d
```

2. **The API will be available at:**
```
http://localhost:8000/api/places
```

## Manual Setup

1. **Install dependencies:**
```bash
composer install
```

2. **Configure environment:**
```bash
cp .env.example .env
php artisan key:generate
```

3. **Set up PostgreSQL database and update .env:**
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=places_api
DB_USERNAME=postgres
DB_PASSWORD=password
```

4. **Run migrations and seed data:**
```bash
php artisan migrate
php artisan db:seed
```

5. **Start the server:**
```bash
php artisan serve
```

## Quick Test

Once the server is running, you can test the API:

```bash
# List all places
curl http://localhost:8000/api/places

# Create a new place
curl -X POST http://localhost:8000/api/places \
  -H "Content-Type: application/json" \
  -d '{"name": "Test Place", "city": "Test City", "state": "Test State"}'

# Search places
curl "http://localhost:8000/api/places?name=park"
```

## Running Tests

```bash
php artisan test
```

All 28 tests should pass, covering:
- API endpoints (Feature tests)
- Service layer logic (Unit tests)
- Validation and error handling
- Database operations