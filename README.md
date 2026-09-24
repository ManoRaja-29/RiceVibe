# Ricevibe storefront

A compact Flask-based e-commerce storefront for Ricevibe by JP Enterprises.

## Quick start

1. Install Python 3.12+
2. In the project folder, run:
   ```bash
   pip install -r requirements.txt
   flask --app app run
   ```
3. Open http://localhost:5000

## Structure

- `app.py` — app routes and SEO metadata
- `config/site_data.py` — all editable site, product and business content
- `templates/` — reusable pages and layout
- `static/` — CSS, JS and SVG assets

## Notes

- Mobile-first design inspired by premium eco-product storefronts
- Cart persists to localStorage
- Menu includes a slide-down transition on mobile
- SEO metadata and sitemap/robots are included

## Enquiry and admin portal

- The storefront is enquiry-only; product buttons open the contact form or WhatsApp.
- Admin login: `http://localhost:5000/admin`
- Default development password: `ricevibe-admin-change-me`
- Set `RICEVIBE_ADMIN_PASSWORD` and `RICEVIBE_SECRET_KEY` environment variables before deployment.
- Set `RICEVIBE_SECURE_COOKIES=1` when serving over HTTPS.
- Run behind a production WSGI server such as Waitress or Gunicorn; Flask's built-in server is for local development only.
- Admin uploads are stored in `static/uploads` and tracked in `static/uploads/uploads.json`.

## Production run

```bash
pip install -r requirements.txt
waitress-serve --listen=0.0.0.0:5000 wsgi:application
```

Set `RICEVIBE_SECRET_KEY`, `RICEVIBE_ADMIN_PASSWORD`, and `RICEVIBE_SECURE_COOKIES=1` in the deployment environment.
