import json
import os
import secrets
from copy import deepcopy
from datetime import datetime
from pathlib import Path
from flask import Flask, abort, flash, redirect, render_template, request, Response, send_file, session, url_for
from werkzeug.utils import secure_filename
from config.site_data import SITE_DATA

app = Flask(__name__)
app.config["TEMPLATES_AUTO_RELOAD"] = True
app.secret_key = os.environ.get("RICEVIBE_SECRET_KEY", "development-only-change-this")
app.config["MAX_CONTENT_LENGTH"] = 8 * 1024 * 1024
app.config["SESSION_COOKIE_HTTPONLY"] = True
app.config["SESSION_COOKIE_SAMESITE"] = "Lax"
app.config["SESSION_COOKIE_SECURE"] = os.environ.get("RICEVIBE_SECURE_COOKIES", "0") == "1"

SITE_URL = "https://ricevibe.in"
SOURCE_MEDIA_DIR = Path(app.root_path) / "static" / "assets" / "media"
UPLOAD_DIR = Path(app.root_path) / "static" / "uploads"
UPLOAD_MANIFEST = UPLOAD_DIR / "uploads.json"
CONTENT_MANIFEST = UPLOAD_DIR / "content.json"
ALLOWED_UPLOADS = {"png", "jpg", "jpeg", "webp", "gif"}


def canonical_url(path: str) -> str:
    return f"{SITE_URL}{path}"


def read_uploads():
    if not UPLOAD_MANIFEST.exists():
        return []
    try:
        return json.loads(UPLOAD_MANIFEST.read_text(encoding="utf-8"))
    except (OSError, json.JSONDecodeError):
        return []


def write_uploads(items):
    UPLOAD_DIR.mkdir(parents=True, exist_ok=True)
    UPLOAD_MANIFEST.write_text(json.dumps(items, indent=2), encoding="utf-8")


def read_content():
    if not CONTENT_MANIFEST.exists():
        return {}
    try:
        return json.loads(CONTENT_MANIFEST.read_text(encoding="utf-8"))
    except (OSError, json.JSONDecodeError):
        return {}


def write_content(content):
    UPLOAD_DIR.mkdir(parents=True, exist_ok=True)
    CONTENT_MANIFEST.write_text(json.dumps(content, indent=2), encoding="utf-8")


def save_uploaded_image(uploaded):
    if not uploaded or not uploaded.filename:
        return ""
    extension = Path(uploaded.filename).suffix.lower().lstrip(".")
    if extension not in ALLOWED_UPLOADS:
        return ""
    safe_name = secure_filename(uploaded.filename)
    stem = Path(safe_name).stem or "ricevibe-image"
    filename = f"{stem}-{secrets.token_hex(4)}.{extension}"
    UPLOAD_DIR.mkdir(parents=True, exist_ok=True)
    uploaded.save(UPLOAD_DIR / filename)
    return f"/static/uploads/{filename}"


def get_site_data():
    site = deepcopy(SITE_DATA)
    for section, value in read_content().items():
        if section in site and isinstance(value, type(site[section])):
            site[section] = value
    site["nav"] = [item for item in site["nav"] if item.get("label", "").lower() != "certificates"]
    if not any(item.get("label") == "Brochure" for item in site["nav"]):
        site["nav"].insert(5, {"label": "Brochure", "url": "/brochure"})
    uploads = read_uploads()
    banners = [item for item in uploads if item.get("type") == "Homepage banner"]
    if banners:
        site["hero"] = [
            {
                "title": item.get("title", "Ricevibe banner"),
                "subtitle": "Ricevibe natural rice straws",
                "image": item["image"],
                "button_text": "Explore products",
                "button_link": "/shop",
            }
            for item in banners
        ]
    for item in uploads:
        if item.get("type") == "Gallery":
            site["gallery"].insert(0, {"title": item["title"], "category": item.get("category", "Ricevibe Products"), "image": item["image"]})
        elif item.get("type") == "Media":
            site["media"].insert(0, {"title": item["title"], "source": "Ricevibe upload", "date": item.get("uploaded_at", "")[:10], "excerpt": "Shared by the Ricevibe content team.", "image": item["image"], "fit": "contain"})
        elif item.get("type") == "Product image" and item.get("product_slug"):
            product = next((product for product in site["products"] if product["slug"] == item["product_slug"]), None)
            if product:
                product["image"] = item["image"]
                product["gallery"].insert(0, item["image"])
    return site


def admin_required():
    return session.get("admin_authenticated") is True


@app.context_processor
def inject_globals():
    return {
        "site": get_site_data(),
        "current_year": datetime.now().year,
        "canonical_url": canonical_url,
        "admin_uploads": read_uploads(),
    }


@app.route("/")
def home():
    return render_template(
        "home.html",
        page_title="Ricevibe | Eco-Friendly Rice Straws, Wooden Stirrer & Garnish Sticks",
        meta_description="Ricevibe supplies eco-friendly rice straws, wooden stirrers, and garnish sticks for hospitality and retail in Chennai, India.",
        canonical_path="/",
        page_type="website",
    )


@app.route("/shop")
def shop():
    category = request.args.get("category", "all").strip().lower()
    search = request.args.get("q", "").strip().lower()
    sort = request.args.get("sort", "featured")

    products = get_site_data()["products"]
    if category != "all":
        products = [p for p in products if p["category_slug"] == category]
    if search:
        products = [
            p for p in products
            if search in p["name"].lower() or search in p["category"].lower() or search in p["description"].lower()
        ]

    if sort == "low-to-high":
        products = sorted(products, key=lambda p: p["price"])
    elif sort == "high-to-low":
        products = sorted(products, key=lambda p: p["price"], reverse=True)
    elif sort == "name":
        products = sorted(products, key=lambda p: p["name"].lower())

    return render_template(
        "shop.html",
        products=products,
        category=category,
        search=search,
        sort=sort,
        page_title="Shop Eco-Friendly Dining Essentials | Ricevibe",
        meta_description="Explore Ricevibe rice straws, wooden stirrers and garnish sticks for cafés, restaurants, retail and bulk hospitality needs.",
        canonical_path="/shop",
        page_type="store",
    )


@app.route("/shop/<slug>")
def product_detail(slug):
    site = get_site_data()
    product = next((p for p in site["products"] if p["slug"] == slug), None)
    if product is None:
        abort(404)
    related = [
        p for p in site["products"]
        if p["category_slug"] == product["category_slug"] and p["slug"] != product["slug"]
    ][:3]
    return render_template(
        "product.html",
        product=product,
        related=related,
        page_title=f"{product['name']} | Ricevibe",
        meta_description=product["description"],
        canonical_path=f"/shop/{slug}",
        page_type="product",
    )


@app.route("/about-us")
def about():
    return render_template(
        "about.html",
        page_title="About Ricevibe | Natural, Sustainable, Food-Grade Products",
        meta_description="Ricevibe brings natural, biodegradable beverage accessories to restaurants, cafés and eco-conscious retail customers in India.",
        canonical_path="/about-us",
        page_type="website",
    )


@app.route("/media")
def media():
    return render_template(
        "media.html",
        page_title="Ricevibe Media & Press | Stories, Articles and Videos",
        meta_description="Read press mentions, discover brand stories, and watch Ricevibe product media and hospitality applications.",
        canonical_path="/media",
        page_type="article",
    )


@app.route("/media-assets/<path:filename>")
def media_asset(filename):
    safe_path = (SOURCE_MEDIA_DIR / filename).resolve()
    if SOURCE_MEDIA_DIR not in safe_path.parents or not safe_path.is_file():
        abort(404)
    return send_file(safe_path)


@app.route("/gallery")
def gallery():
    return render_template(
        "gallery.html",
        page_title="Ricevibe Gallery | Product Shots, Hospitality Uses & Packaging",
        meta_description="Browse Ricevibe gallery moments featuring products, hospitality setups, events, packaging and behind-the-scenes stories.",
        canonical_path="/gallery",
        page_type="website",
    )


@app.route("/certificates")
def certificates():
    return render_template(
        "certificates.html",
        page_title="Certificates | Ricevibe by JP Enterprises",
        meta_description="View official Ricevibe and JP Enterprises certificates and compliance information for food-grade natural products.",
        canonical_path="/certificates",
        page_type="website",
    )


@app.route("/brochure")
def brochure():
    return render_template(
        "brochure.html",
        page_title="Ricevibe Brochure | Ricevibe Enterprises Private Limited",
        meta_description="Read the Ricevibe Enterprises Private Limited company brochure and download the official brochure PDF.",
        canonical_path="/brochure",
        page_type="website",
    )


@app.route("/brochure/download")
def brochure_download():
    brochure_path = Path(app.root_path) / "static" / "assets" / "ricevibe" / "jp_brochure.pdf"
    if not brochure_path.exists():
        abort(404)
    return send_file(brochure_path, as_attachment=True, download_name="Ricevibe-Brochure.pdf")


@app.route("/brochure/view")
def brochure_view():
    brochure_path = Path(app.root_path) / "static" / "assets" / "ricevibe" / "jp_brochure.pdf"
    if not brochure_path.exists():
        abort(404)
    return send_file(brochure_path, mimetype="application/pdf", as_attachment=False)


@app.route("/faq")
def faq():
    return render_template(
        "faq.html",
        page_title="Frequently Asked Questions | Ricevibe",
        meta_description="Learn about Ricevibe rice straws, product materials, usage and hospitality FAQs for retail and B2B buyers.",
        canonical_path="/faq",
        page_type="faq",
    )


@app.route("/contact")
def contact():
    return render_template(
        "contact.html",
        selected_product=request.args.get("product", "").strip(),
        page_title="Contact Ricevibe | Chennai, India",
        meta_description="Contact Ricevibe for retail, wholesale, hotel and café enquiries, or speak with our team in Chennai.",
        canonical_path="/contact",
        page_type="website",
    )


@app.route("/cart")
def cart():
    return redirect(url_for("contact"))


@app.route("/privacy-policy")
def privacy_policy():
    return render_template("policy.html", page_title="Privacy Policy | Ricevibe", meta_description="Ricevibe privacy policy for website visits and business enquiries.", canonical_path="/privacy-policy", policy_page="privacy")


@app.route("/terms-and-conditions")
def terms_conditions():
    return render_template("policy.html", page_title="Terms & Conditions | Ricevibe", meta_description="Our terms and conditions for orders, product usage and enquiries.", canonical_path="/terms-and-conditions", policy_page="terms")


@app.route("/shipping-policy")
def shipping_policy():
    return render_template("policy.html", page_title="Shipping Policy | Ricevibe", meta_description="Shipping information for Ricevibe natural product orders across India.", canonical_path="/shipping-policy", policy_page="shipping")


@app.route("/returns-policy")
def return_policy():
    return render_template("policy.html", page_title="Return & Refund Policy | Ricevibe", meta_description="Return and refund policy for product concerns, damaged orders, and bulk purchases.", canonical_path="/returns-policy", policy_page="returns")


@app.route("/robots.txt")
def robots():
    content = "User-agent: *\nAllow: /\nSitemap: https://ricevibe.in/sitemap.xml\n"
    return Response(content, mimetype="text/plain")


@app.route("/sitemap.xml")
def sitemap():
    urls = [
        "",
        "/shop",
        "/about-us",
        "/media",
        "/gallery",
        "/certificates",
        "/faq",
        "/contact",
        "/privacy-policy",
        "/terms-and-conditions",
        "/shipping-policy",
        "/returns-policy",
    ]
    for product in get_site_data()["products"]:
        urls.append(f"/shop/{product['slug']}")

    xml = "<?xml version='1.0' encoding='UTF-8'?>\n<urlset xmlns='http://www.sitemaps.org/schemas/sitemap/0.9'>\n"
    for path in urls:
        xml += "  <url>\n"
        xml += f"    <loc>{canonical_url(path)}</loc>\n"
        xml += "    <changefreq>weekly</changefreq>\n"
        xml += "  </url>\n"
    xml += "</urlset>\n"
    return Response(xml, mimetype="application/xml")


@app.route("/admin", methods=["GET", "POST"])
def admin_login():
    if session.get("admin_authenticated"):
        return redirect(url_for("admin_dashboard"))
    if request.method == "POST":
        password = request.form.get("password", "")
        expected = os.environ.get("RICEVIBE_ADMIN_PASSWORD", "ricevibe-admin-change-me")
        if expected and secrets.compare_digest(password, expected):
            session["admin_authenticated"] = True
            return redirect(url_for("admin_dashboard"))
        flash("Invalid admin password.", "error")
    return render_template("admin_login.html", page_title="Admin Login | Ricevibe")


@app.route("/admin/dashboard")
def admin_dashboard():
    if not admin_required():
        return redirect(url_for("admin_login"))
    return render_template("admin_dashboard.html", page_title="Admin Dashboard | Ricevibe", admin_site=get_site_data(), admin_content=read_content())


@app.route("/admin/content/save", methods=["POST"])
def admin_content_save():
    if not admin_required():
        return redirect(url_for("admin_login"))
    section = request.form.get("section", "").strip()
    allowed = {"brand", "hero", "categories", "products", "testimonials", "faq", "media", "gallery", "certificates"}
    if section not in allowed:
        flash("This content section is not editable.", "error")
        return redirect(url_for("admin_dashboard"))
    try:
        value = json.loads(request.form.get("content", ""))
    except json.JSONDecodeError:
        flash(f"{section.title()} must contain valid JSON.", "error")
        return redirect(url_for("admin_dashboard"))
    if not isinstance(value, (dict, list)):
        flash("Content must be an object or list.", "error")
        return redirect(url_for("admin_dashboard"))
    content = read_content()
    content[section] = value
    write_content(content)
    flash(f"{section.title()} content saved.", "success")
    return redirect(url_for("admin_dashboard"))


@app.route("/admin/content/delete/<section>/<int:index>", methods=["POST"])
def admin_content_delete(section, index):
    if not admin_required():
        return redirect(url_for("admin_login"))
    content = read_content()
    items = content.get(section)
    if isinstance(items, list) and 0 <= index < len(items):
        items.pop(index)
        content[section] = items
        write_content(content)
        flash(f"Item removed from {section}.", "success")
    return redirect(url_for("admin_dashboard"))


@app.route("/admin/content/item", methods=["POST"])
def admin_content_item():
    if not admin_required():
        return redirect(url_for("admin_login"))
    section = request.form.get("section", "").strip()
    allowed = {"hero", "categories", "products", "testimonials", "faq", "media", "gallery", "certificates"}
    if section not in allowed:
        flash("This section cannot be edited.", "error")
        return redirect(url_for("admin_dashboard"))
    items = deepcopy(get_site_data().get(section, []))
    index_text = request.form.get("index", "").strip()
    fields = {key: value.strip() for key, value in request.form.items() if key not in {"section", "index", "action"} and value.strip()}
    uploaded_image = save_uploaded_image(request.files.get("image_file"))
    if uploaded_image:
        fields["image"] = uploaded_image
        if section == "certificates":
            fields["thumb"] = uploaded_image
            fields["file"] = uploaded_image
    if "price" in fields:
        try:
            fields["price"] = int(fields["price"])
        except ValueError:
            fields.pop("price")
    if "specs" in fields:
        fields["specs"] = [line.strip() for line in fields["specs"].splitlines() if line.strip()]
    if request.form.get("action") == "edit" and index_text.isdigit() and int(index_text) < len(items):
        items[int(index_text)].update(fields)
    else:
        items.append(fields)
    content = read_content()
    content[section] = items
    write_content(content)
    flash(f"{section.title()} item saved.", "success")
    return redirect(url_for("admin_dashboard"))


@app.route("/admin/settings/save", methods=["POST"])
def admin_settings_save():
    if not admin_required():
        return redirect(url_for("admin_login"))
    content = read_content()
    content["brand"] = {key: request.form.get(key, "").strip() for key in ["name", "tagline", "phone", "email", "address", "whatsapp"]}
    content["social"] = {key: request.form.get(key, "").strip() for key in ["instagram", "facebook", "whatsapp"]}
    content["homepage"] = {key: request.form.get(key, "").strip() for key in ["categories_heading", "products_heading", "why_heading", "sustainability_heading", "bulk_heading", "feedback_heading"]}
    write_content(content)
    flash("Site settings saved.", "success")
    return redirect(url_for("admin_dashboard"))


@app.route("/admin/upload", methods=["POST"])
def admin_upload():
    if not admin_required():
        return redirect(url_for("admin_login"))
    uploaded_files = [uploaded for uploaded in request.files.getlist("image") if uploaded.filename]
    if not uploaded_files:
        flash("Choose an image before uploading.", "error")
        return redirect(url_for("admin_dashboard"))
    items = read_uploads()
    for uploaded in uploaded_files:
        image_url = save_uploaded_image(uploaded)
        if image_url:
            items.insert(0, {"title": request.form.get("title", "Ricevibe upload").strip() or Path(uploaded.filename).stem, "type": request.form.get("type", "Gallery"), "category": request.form.get("category", "Ricevibe Products"), "product_slug": request.form.get("product_slug", "").strip(), "image": image_url, "filename": Path(image_url).name, "uploaded_at": datetime.now().isoformat(timespec="seconds")})
    if not items:
        flash("Only PNG, JPG, JPEG, WEBP and GIF images are allowed.", "error")
        return redirect(url_for("admin_dashboard"))
    write_uploads(items)
    flash("Image uploaded successfully.", "success")
    return redirect(url_for("admin_dashboard"))


@app.route("/admin/delete/<filename>", methods=["POST"])
def admin_delete(filename):
    if not admin_required():
        return redirect(url_for("admin_login"))
    safe_name = secure_filename(filename)
    path = UPLOAD_DIR / safe_name
    if path.exists():
        path.unlink()
    write_uploads([item for item in read_uploads() if item.get("filename") != safe_name])
    return redirect(url_for("admin_dashboard"))


@app.route("/admin/logout")
def admin_logout():
    session.pop("admin_authenticated", None)
    return redirect(url_for("admin_login"))


@app.errorhandler(404)
def page_not_found(error):
    return render_template("404.html", page_title="Page Not Found | Ricevibe"), 404


if __name__ == "__main__":
    app.run(debug=os.environ.get("FLASK_DEBUG", "0") == "1", host="0.0.0.0", port=int(os.environ.get("PORT", "5000")))
