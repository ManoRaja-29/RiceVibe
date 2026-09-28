# RiceVibe Flask → Laravel migration

This branch migrates the existing RiceVibe Flask application to Laravel while preserving the current storefront design, URLs, assets, content, cart behaviour and admin capabilities.

## Source of truth

- `main` remains the working Flask production source until parity testing is complete.
- Existing `templates/`, `static/`, `config/site_data.py` and Flask routes are preserved during migration for comparison.
- The Laravel implementation will use MySQL for editable content and Laravel public storage for admin uploads.

## Parity checklist

- Home page and hero/banner carousel
- Shop filtering, search and sorting
- Product details and related products
- About, media, gallery, certificates and brochure
- FAQ, contact, policies and WhatsApp links
- Existing client-side cart behaviour
- SEO metadata, robots.txt and sitemap.xml
- Admin login/session/CSRF protection
- Admin content editing
- Banner/gallery/media/product-image uploads
- Admin delete operations
- Site settings

## Production target

Laravel 13 / PHP 8.3+ / MySQL. The production web root must point to Laravel's `public/` directory. `.env` must never be committed.

## Migration safety

Do not point `ricevibe.in` at this branch until functional and visual parity checks pass. The current Flask deployment remains unchanged during migration.
