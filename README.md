# RiceVibe on Laravel

The Laravel application lives in this directory. It uses Blade views, Eloquent, MySQL in production, and SQLite for local development and tests.

## Local development

Requirements: PHP 8.3+, Composer 2, and the `pdo_sqlite`, `pdo_mysql`, `mbstring`, `openssl`, `fileinfo`, and `gd` extensions.

```powershell
composer install
php artisan migrate:fresh --seed
php artisan storage:link
php artisan serve
```

The local `.env` is ignored by Git. Set `ADMIN_EMAIL` and `ADMIN_PASSWORD` before running the seeder if you need an admin account. There is no default admin user or password.

## Hostinger deployment

Create a MySQL database and database user in hPanel. Configure the domain document root to this Laravel application's `public` directory, not the project root. The hosting plan must provide PHP 8.3+, Composer 2 or an uploadable `vendor` directory, and the PHP MySQL/`pdo_mysql` extension.

Set these values in the server-side `.env` file or hosting environment settings. Never commit `.env` or send these values in chat.

```dotenv
APP_NAME=Ricevibe
APP_ENV=production
APP_DEBUG=false
APP_URL=https://ricevibe.in
DB_CONNECTION=mysql
DB_HOST=<Hostinger database host>
DB_PORT=3306
DB_DATABASE=<database name>
DB_USERNAME=<database user>
DB_PASSWORD=<database password>
FILESYSTEM_DISK=public
ADMIN_EMAIL=<admin email>
ADMIN_PASSWORD=<strong unique password>
```

Generate a unique `APP_KEY` on the server with `php artisan key:generate`. Then deploy dependencies and database schema:

```bash
composer install --no-dev --optimize-autoloader
php artisan migrate --seed --force
php artisan storage:link
php artisan optimize
```

The admin is available at `/admin/login`. The seeder creates it only when both admin environment variables are present. Products, banners, editable site sections, and enquiries are stored in MySQL; uploaded images use Laravel's public storage disk. Keep `storage` and `bootstrap/cache` writable by PHP, and back up both the database and `storage/app/public`.

## Application areas

- Public storefront: home, shop, product detail, gallery, media, FAQ, policies, contact, and brochure.
- Admin: product and banner management, JSON-backed site content editing, and enquiry inbox.
- Database: categories, products, banners, site content, enquiries, and Laravel admin users.
