document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('[data-gallery-browser]').forEach((browser) => {
    const filters = browser.querySelectorAll('[data-gallery-filter]');
    const items = browser.querySelectorAll('[data-gallery-category]');
    filters.forEach((filter) => filter.addEventListener('click', () => {
      const category = filter.dataset.galleryFilter;
      filters.forEach((button) => button.classList.toggle('active', button === filter));
      items.forEach((item) => { item.hidden = category !== 'all' && item.dataset.galleryCategory !== category; });
    }));
  });

  document.querySelectorAll('.admin-add-toggle').forEach((button) => {
    button.addEventListener('click', () => {
      const form = document.getElementById(button.dataset.target);
      if (form) form.hidden = !form.hidden;
    });
  });

  const menuToggle = document.querySelector('.menu-toggle');
  const mainNav = document.querySelector('.main-nav');
  const menuBackdrop = document.querySelector('.menu-backdrop');
  const navClose = document.querySelector('.nav-close');
  if (menuToggle && mainNav) {
    const setMenuState = (open) => {
      menuToggle.setAttribute('aria-expanded', String(open));
      mainNav.classList.toggle('open', open);
      document.body.classList.toggle('menu-open', open);
    };
    menuToggle.addEventListener('click', () => setMenuState(menuToggle.getAttribute('aria-expanded') !== 'true'));
    navClose?.addEventListener('click', () => setMenuState(false));
    menuBackdrop?.addEventListener('click', () => setMenuState(false));
    mainNav.querySelectorAll('a').forEach((link) => link.addEventListener('click', () => setMenuState(false)));
    document.addEventListener('keydown', (event) => { if (event.key === 'Escape') setMenuState(false); });
  }
  const header = document.querySelector('.site-header');
  const updateHeader = () => header?.classList.toggle('is-scrolled', window.scrollY > 8);
  updateHeader();
  window.addEventListener('scroll', updateHeader, { passive: true });

  const chatButton = document.querySelector('.chat-float');
  const chatPanel = document.querySelector('.chat-panel');
  if (chatButton && chatPanel) {
    chatButton.addEventListener('click', () => {
      const isOpen = chatButton.getAttribute('aria-expanded') === 'true';
      chatButton.setAttribute('aria-expanded', String(!isOpen));
      chatPanel.hidden = isOpen;
    });
  }

  const testimonials = [...document.querySelectorAll('.testimonial-card')];
  const previousTestimonial = document.querySelector('.testimonial-prev');
  const nextTestimonial = document.querySelector('.testimonial-next');
  let testimonialIndex = testimonials.findIndex((item) => item.classList.contains('active'));
  const showTestimonial = (index) => {
    if (!testimonials.length) return;
    testimonialIndex = (index + testimonials.length) % testimonials.length;
    testimonials.forEach((item, itemIndex) => item.classList.toggle('active', itemIndex === testimonialIndex));
  };
  previousTestimonial?.addEventListener('click', () => showTestimonial(testimonialIndex - 1));
  nextTestimonial?.addEventListener('click', () => showTestimonial(testimonialIndex + 1));
  if (testimonials.length > 1) window.setInterval(() => showTestimonial(testimonialIndex + 1), 2000);

  const certificates = [...document.querySelectorAll('.cert-carousel .cert-card')];
  const certificateDots = [...document.querySelectorAll('.cert-carousel .cert-dot')];
  if (certificates.length > 1) {
    let certificateIndex = 0;
    const showCertificate = (index) => {
      certificateIndex = (index + certificates.length) % certificates.length;
      certificates.forEach((card, cardIndex) => card.classList.toggle('active', cardIndex === certificateIndex));
      certificateDots.forEach((dot, dotIndex) => dot.classList.toggle('active', dotIndex === certificateIndex));
    };
    window.setInterval(() => showCertificate(certificateIndex + 1), 2000);
    certificateDots.forEach((dot, index) => dot.addEventListener('click', () => showCertificate(index)));
  }

  document.querySelectorAll('[data-carousel]').forEach((carousel) => {
    const slides = [...carousel.querySelectorAll('.hero-slide')];
    const dots = [...carousel.querySelectorAll('.dot')];
    let activeIndex = 0;
    let timer;
    let startX = 0;
    const showSlide = (index) => {
      activeIndex = (index + slides.length) % slides.length;
      slides.forEach((slide, slideIndex) => slide.classList.toggle('active', slideIndex === activeIndex));
      dots.forEach((dot, dotIndex) => dot.classList.toggle('active', dotIndex === activeIndex));
    };
    const startTimer = () => { window.clearInterval(timer); timer = window.setInterval(() => showSlide(activeIndex + 1), 2000); };
    dots.forEach((dot, index) => dot.addEventListener('click', () => { showSlide(index); startTimer(); }));
    carousel.addEventListener('mouseenter', () => window.clearInterval(timer));
    carousel.addEventListener('mouseleave', startTimer);
    carousel.addEventListener('touchstart', (event) => { startX = event.changedTouches[0].screenX; }, { passive: true });
    carousel.addEventListener('touchend', (event) => {
      const distance = event.changedTouches[0].screenX - startX;
      if (Math.abs(distance) > 45) showSlide(activeIndex + (distance < 0 ? 1 : -1));
      startTimer();
    }, { passive: true });
    startTimer();
  });

  const revealItems = document.querySelectorAll('.reveal-on-scroll');
  if ('IntersectionObserver' in window) {
    const revealObserver = new IntersectionObserver((entries, observer) => {
      entries.forEach((entry) => { if (entry.isIntersecting) { entry.target.classList.add('is-visible'); observer.unobserve(entry.target); } });
    }, { threshold: 0.12 });
    revealItems.forEach((item) => revealObserver.observe(item));
  } else revealItems.forEach((item) => item.classList.add('is-visible'));

  const faqItems = document.querySelectorAll('.faq-item');
  faqItems.forEach((item) => {
    const button = item.querySelector('.faq-question');
    if (!button) return;
    button.addEventListener('click', () => {
      const expanded = button.getAttribute('aria-expanded') === 'true';
      button.setAttribute('aria-expanded', String(!expanded));
      item.classList.toggle('open', !expanded);
    });
  });

  const storageKey = 'ricevibe-cart';
  const getCart = () => {
    try {
      return JSON.parse(localStorage.getItem(storageKey) || '[]');
    } catch (error) {
      return [];
    }
  };

  const setCart = (cart) => localStorage.setItem(storageKey, JSON.stringify(cart));
  const updateCartCount = () => {
    const count = getCart().reduce((total, item) => total + item.qty, 0);
    const badges = document.querySelectorAll('.mini-cart-count');
    badges.forEach((badge) => { badge.textContent = String(count); });
  };

  const addToCart = (product, qty = 1) => {
    const cart = getCart();
    const existing = cart.find((item) => item.id === product.id);
    if (existing) {
      existing.qty += qty;
    } else {
      cart.push({ ...product, qty });
    }
    setCart(cart);
    updateCartCount();
  };

  document.body.addEventListener('click', (event) => {
    const button = event.target.closest('.add-to-cart');
    if (!button) return;
    const product = JSON.parse(button.dataset.product || '{}');
    const qtyInput = document.getElementById(button.dataset.qtyInput || '');
    const qty = qtyInput ? Math.max(1, Number(qtyInput.value || 1)) : 1;
    addToCart(product, qty);
    if (window.location.pathname !== '/cart') {
      window.location.href = '/cart';
    }
  });

  const renderCart = () => {
    const cartItemsNode = document.getElementById('cartItems');
    if (!cartItemsNode) return;
    const cart = getCart();
    if (!cart.length) {
      cartItemsNode.innerHTML = '<div class="empty-state"><h2>Your cart is empty</h2><p>Add eco-friendly essentials to begin.</p><a class="btn primary" href="/shop">Shop now</a></div>';
      document.getElementById('cartSubtotal').textContent = '₹0';
      document.getElementById('cartTotal').textContent = '₹0';
      return;
    }
    let subtotal = 0;
    cartItemsNode.innerHTML = cart.map((item) => {
      subtotal += item.price * item.qty;
      return `
        <div class="cart-item" data-id="${item.id}">
          <img src="${item.image}" alt="${item.name}" />
          <div class="cart-item-details">
            <h3>${item.name}</h3>
            <p>${item.pack}</p>
            <strong>₹${item.price}</strong>
          </div>
          <div class="cart-item-qty">
            <button type="button" data-action="decrease" data-id="${item.id}">-</button>
            <span>${item.qty}</span>
            <button type="button" data-action="increase" data-id="${item.id}">+</button>
            <button type="button" data-action="remove" data-id="${item.id}" style="width:auto;padding:0.4rem 0.7rem;">Remove</button>
          </div>
        </div>
      `;
    }).join('');
    document.getElementById('cartSubtotal').textContent = `₹${subtotal}`;
    document.getElementById('cartTotal').textContent = `₹${subtotal}`;
  };

  document.body.addEventListener('click', (event) => {
    const button = event.target.closest('[data-action]');
    if (!button) return;
    const cart = getCart();
    const id = Number(button.dataset.id);
    const action = button.dataset.action;
    const item = cart.find((entry) => entry.id === id);
    if (!item) return;
    if (action === 'increase') item.qty += 1;
    if (action === 'decrease') item.qty = Math.max(0, item.qty - 1);
    if (action === 'remove' || item.qty === 0) {
      const filtered = cart.filter((entry) => entry.id !== id);
      setCart(filtered);
      renderCart();
      updateCartCount();
      return;
    }
    setCart(cart);
    renderCart();
    updateCartCount();
  });

  const contactForm = document.querySelector('.contact-form');
  if (contactForm) {
    contactForm.addEventListener('submit', (event) => {
      event.preventDefault();
      const formData = new FormData(contactForm);
      const status = contactForm.querySelector('.form-status');
      const name = formData.get('name')?.toString().trim();
      const phone = formData.get('phone')?.toString().trim();
      const email = formData.get('email')?.toString().trim();
      const message = formData.get('message')?.toString().trim();
      if (!name || !phone || !email || !message) {
        status.textContent = 'Please fill in the required fields.';
        return;
      }
      status.textContent = 'Thanks! Your enquiry has been prepared for Ricevibe.';
      contactForm.reset();
    });
  }

  updateCartCount();
  renderCart();
});
