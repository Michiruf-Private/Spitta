# Seminar Booking

Laravel + Livewire application for booking medical seminars. Doctors and dental practices can register for professional seminars; registrations are validated server-side and stored, with a live preview (including the calculated total price) that updates while typing.

## Requirements

- PHP 8.3+ (with `bcmath`)
- Composer
- Node.js + npm

## Getting Started

1. Install dependencies:

   ```bash
   composer install
   npm install
   ```

2. Copy the environment file:

   ```bash
   cp .env.example .env
   ```

3. Generate the application key:

   ```bash
   php artisan key:generate
   ```

4. Create the database and seed demo data:

   ```bash
   php artisan migrate:fresh --seed
   ```

   The app uses SQLite by default. Since `DB_DATABASE=laravel` is set in `.env`, the database is created as a file named `laravel` in the project root.

   To use MySQL instead, adjust the database section of your `.env` accordingly, e.g.:

   ```dotenv
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=laravel
   DB_USERNAME=app
   DB_PASSWORD=app
   ```

5. Build the frontend assets:

   ```bash
   npm run build
   ```

6. Start the development server:

   ```bash
   php artisan serve
   ```

7. Open [http://localhost:8000](http://localhost:8000) in your browser.

## Testing

```bash
php artisan test --compact
```

## Troubleshooting

### Internal Server Error: `filemtime(): stat failed`

```
ErrorException: filemtime(): stat failed for .../storage/framework/views/livewire/views/xxxxxxx.blade.php
```

A stale compiled view cache references a file that no longer exists. Clear all caches:

```bash
php artisan optimize:clear
```
