/**
 * MobileHub — Main JavaScript
 * Clean E-Commerce Animations & Interactions
 */

// ── Navbar Scroll Effect ──
const navbar = document.getElementById('navbar');
if (navbar) {
  window.addEventListener('scroll', () => {
    navbar.classList.toggle('scrolled', window.scrollY > 20);
  }, { passive: true });
}

// ── Scroll Reveal (IntersectionObserver) ──
const reveals = document.querySelectorAll('.reveal');
if (reveals.length) {
  // Add class to body to enable reveal animations (elements visible by default without JS)
  document.body.classList.add('reveal-ready');

  const revealObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('visible');
        revealObserver.unobserve(entry.target);
      }
    });
  }, { threshold: 0.01, rootMargin: '0px 0px -20px 0px' });

  reveals.forEach(el => revealObserver.observe(el));
}

// ── GSAP Animations (subtle, professional) ──
document.addEventListener('DOMContentLoaded', () => {
  if (typeof gsap !== 'undefined') {
    // Hero entrance
    const heroBanner = document.querySelector('.hero-banner');
    if (heroBanner) {
      const tl = gsap.timeline({ defaults: { duration: 0.7, ease: 'power2.out' } });
      tl.from('.hero-badge', { opacity: 0, y: 20, duration: 0.5 })
        .from('.hero-title', { opacity: 0, y: 30 }, '-=0.3')
        .from('.hero-desc', { opacity: 0, y: 20 }, '-=0.3')
        .from('.hero-actions', { opacity: 0, y: 15 }, '-=0.2')
        .from('.hero-phone-img', { opacity: 0, scale: 0.85, duration: 0.8 }, '-=0.4');
    }

    // Promo cards entrance
    gsap.from('.promo-card', {
      opacity: 0,
      y: 20,
      stagger: 0.1,
      duration: 0.5,
      delay: 0.5,
      ease: 'power2.out'
    });

    // Category cards with ScrollTrigger
    if (typeof ScrollTrigger !== 'undefined') {
      gsap.registerPlugin(ScrollTrigger);

      gsap.utils.toArray('.product-card').forEach((card, i) => {
        gsap.from(card, {
          scrollTrigger: { trigger: card, start: 'top 88%', once: true },
          opacity: 0,
          y: 30,
          duration: 0.5,
          delay: (i % 4) * 0.08
        });
      });

      gsap.utils.toArray('.service-card').forEach((card, i) => {
        gsap.from(card, {
          scrollTrigger: { trigger: card, start: 'top 88%', once: true },
          opacity: 0,
          y: 24,
          duration: 0.45,
          delay: (i % 3) * 0.1
        });
      });
    }
  }
});

// ── Toast Notification ──
function showToast(message, type = 'success') {
  const toast = document.getElementById('toast');
  if (!toast) return;

  const icons = { success: '✓', error: '✕', info: 'ℹ' };
  toast.textContent = (icons[type] || '✓') + '  ' + message;
  toast.className = 'toast-notification ' + type + ' show';

  setTimeout(() => {
    toast.className = 'toast-notification';
  }, 3500);
}

// ── Smooth Scroll ──
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
  anchor.addEventListener('click', function(e) {
    const target = document.querySelector(this.getAttribute('href'));
    if (target) {
      e.preventDefault();
      target.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
  });
});

// ── Mobile menu close on click ──
const offcanvas = document.getElementById('mobileMenu');
if (offcanvas) {
  offcanvas.querySelectorAll('.nav-link').forEach(link => {
    link.addEventListener('click', () => {
      const bsOffcanvas = bootstrap.Offcanvas.getInstance(offcanvas);
      if (bsOffcanvas) bsOffcanvas.hide();
    });
  });
}
