# Library Management System (LMS)

This is a library management system built on the Laravel Framework, version 11.x.

## System Requirements

- PHP >= 8.2
- Composer
- MySQL/PostgreSQL/SQLite
- Node.js & NPM (for frontend assets)

## Key Features

- Book and document management
- Image processing with Intervention Image
- Social media authentication integration (Socialite)
- API Authentication with Laravel Passport
- PDF file processing with PDF Parser
- reCAPTCHA integration for security
- AWS S3 storage support

## Installation

1. Clone repository:
```bash
git clone [repository-url]
cd lms
```

2. Install dependencies:
```bash
composer install
npm install
```

3. Create environment file:
```bash
cp .env.example .env
php artisan key:generate
```

4. Configure database in `.env` file:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=lms
DB_USERNAME=root
DB_PASSWORD=
```

5. Run migrations and seeders:
```bash
php artisan migrate --seed
```

6. Start the server:
```bash
php artisan serve
```

## Directory Structure

- `app/` - Contains the core code of the application
- `config/` - Configuration files
- `database/` - Migrations and seeders
- `public/` - Frontend assets and entry point
- `resources/` - Views and uncompiled assets
- `routes/` - Route definitions
- `storage/` - File uploads and logs
- `tests/` - Unit and feature tests

## Development

- Run tests: `php artisan test`
- Format code: `./vendor/bin/pint`
- Create new migration: `php artisan make:migration [name]`
- Create new model: `php artisan make:model [name]`

## Security

- reCAPTCHA integration for important forms
- API authentication with Laravel Passport
- CSRF protection for all forms
- Input validation for all forms

## License

MIT License
