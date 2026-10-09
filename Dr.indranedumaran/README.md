# Dr. Indhra Nedumaran – Website (Laravel 13 + MySQL)

Premium, server-rendered website and admin panel for Dr. Indhra Nedumaran, Consultant Obstetrician, Gynaecologist & Laparoscopic Surgeon (Chennai).
Stack: Laravel 13 (PHP 8.3), Blade, Tailwind CSS 4, Alpine.js, MySQL, Quill (admin rich-text editor).

> **Content status.** Doctor details (qualifications, registration, affiliations, memberships, clinic address and phone)
> come from information supplied by the client. Conferences, gallery photos, achievements, email and clinic hours are
> intentionally empty and their sections stay hidden until added from `/admin`. Blog articles are general educational
> samples. Please have the doctor verify all text before launch.

## Features

**Public site** – Home (hero, about preview, expertise, pregnancy journey, featured blogs, conferences, robotic-surgery
spotlight, gallery with lightbox, FAQs, contact), About (bio, qualifications, timeline, memberships), Fertility,
Gynaecology, Pregnancy & Maternity, Blogs (search, category filter, pagination, share buttons, reading time, video embeds),
Activities / conference timeline, Contact (form, WhatsApp, phone, email, hours, Google Map, social links).

**Also included** – Consult Q&A (public questions, doctor replies from admin, FAQs at the bottom), Patient stories
(`/stories`, admin-managed, home carousel), interactive trimester chart, a 1–40 week pregnancy guide
(`/blogs?view=weekly`), a pregnancy due-date calculator (Gynaecology page), and a purple/pink/blue theme
(tokens in `resources/css/app.css`). Seeded stories, Q&A and weekly text are placeholders for the doctor to review.

**Admin (`/admin`)** – login with rate limiting; dashboard stats; CRUD for blog posts (rich text, images, YouTube/Vimeo,
draft / published / scheduled, SEO fields), categories, conferences & events, services, FAQs, pregnancy stages, profile
items, gallery; enquiries inbox; media library; site settings (branding, contact, social, about, home, SEO, analytics);
account/password page.

**SEO** – `/sitemap.xml`, `robots.txt`, canonical URLs, meta descriptions, Open Graph/Twitter cards, JSON-LD
(Physician, BlogPosting, Event, FAQPage).

**Security** – CSRF, uploads re-encoded to WebP via GD, server-side HTML sanitising, honeypot + timing trap + rate limits
on the contact form, login throttling, security headers, hashed passwords.

## Local setup

```bash
composer install
cp .env.example .env          # set DB_* (or DB_CONNECTION=sqlite for a quick try)
php artisan key:generate
php artisan migrate --seed    # tables, admin user, doctor details, placeholder content
php artisan storage:link
npm install && npm run build  # or npm run dev
php artisan serve
```

Admin login: `/admin/login`, using `ADMIN_EMAIL` / `ADMIN_PASSWORD` from `.env` at seed time (blank password = random one
printed once). **Change the password** in Admin → Account after first login. Run tests with `php artisan test`.

## Deploying to Hostinger (PHP 8.3)

1. **Build locally**: `composer install --no-dev --optimize-autoloader` and `npm run build` (`public/build` is included, so
   Node is not needed on the server).
2. **hPanel → PHP Configuration**: select PHP 8.3; enable `mbstring`, `pdo_mysql`, `gd`, `fileinfo`, `openssl`, `xml`, `curl`.
3. **hPanel → Databases → MySQL**: create a database and user.
4. **Upload** the project above the web root (e.g. `/home/uXXXX/dr-indhra/`) and set the domain's document root to its
   `public` folder.
   - If the document root cannot be changed: copy the contents of `public/` into `public_html/` and edit
     `public_html/index.php` so `vendor/autoload.php` and `bootstrap/app.php` point to the project folder.
5. **`.env`**: copy `.env.example`; set `APP_URL=https://your-domain.com`, `APP_ENV=production`, `APP_DEBUG=false`, `DB_*`,
   SMTP mail settings, `ADMIN_EMAIL`, `ADMIN_PASSWORD`.
6. **SSH**:
   ```bash
   php artisan key:generate --force
   php artisan migrate --seed --force
   php artisan storage:link
   php artisan config:cache && php artisan route:cache && php artisan view:cache
   ```
   Without SSH: export a seeded local database and import it via phpMyAdmin; if symlinks are unavailable, copy
   `storage/app/public` to `public/storage`.
7. Make `storage/` and `bootstrap/cache/` writable (`chmod -R 775`).
8. Enable free SSL in hPanel and force HTTPS.
9. Edit `public/robots.txt` so the sitemap line is absolute (`Sitemap: https://your-domain.com/sitemap.xml`) and submit it in
   Google Search Console.
10. After changing `.env` later, re-run `php artisan config:cache`.

## Notes

- Settings are defined in `config/site_settings.php` and edited in Admin → Settings (seeded values never overwrite edits).
- Admin CRUD is config-driven: see `app/Http/Controllers/Admin/CrudController.php`.
- Home data is cached for 10 minutes; caches and the sitemap are flushed on admin saves.
- Quill 2 has an advisory about its HTML export feature; it is not used, and rich text is sanitised on save by `clean_html()`.
