document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('form[action*="/admin/products"][enctype="multipart/form-data"]').forEach((form) => {
    if (form.querySelector('input[name="gallery_files[]"]')) return;

    const submitButton = form.querySelector('button[type="submit"]');
    const field = document.createElement('label');
    field.className = 'admin-gallery-upload';
    field.innerHTML = `
      <span>Gallery images</span>
      <small>Upload up to 8 product photos. These images also appear automatically on the public Gallery page.</small>
      <input name="gallery_files[]" type="file" accept="image/*" multiple />
    `;

    if (!form.action.endsWith('/admin/products')) {
      const replace = document.createElement('label');
      replace.className = 'admin-gallery-replace';
      replace.innerHTML = '<input name="replace_gallery" type="checkbox" value="1" /> Replace existing gallery images';
      field.appendChild(replace);
    }

    if (submitButton) {
      submitButton.parentNode.insertBefore(field, submitButton);
    } else {
      form.appendChild(field);
    }
  });

  const adminNav = document.querySelector('.admin-navigation');
  if (adminNav && !adminNav.querySelector('a[href="/admin/brochure"]')) {
    const brochureLink = document.createElement('a');
    brochureLink.href = '/admin/brochure';
    brochureLink.innerHTML = '<span>Brochure</span>';
    const contentLink = adminNav.querySelector('a[href="#content"]');
    if (contentLink) {
      adminNav.insertBefore(brochureLink, contentLink);
    } else {
      adminNav.appendChild(brochureLink);
    }
  }
});
