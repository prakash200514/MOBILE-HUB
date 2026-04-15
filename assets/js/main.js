/**
 * MobileHub — Main JavaScript
 * Animations, interactions, and UI enhancements
 */

// ── Cursor Glow ──
const cursorGlow = document.getElementById('cursorGlow');
if (cursorGlow) {
  document.addEventListener('mousemove', (e) => {
    cursorGlow.style.left = e.clientX + 'px';
    cursorGlow.style.top = e.clientY + 'px';
  });
}

// ── Navbar Scroll Effect ──
const navbar = document.getElementById('navbar');
if (navbar) {
  window.addEventListener('scroll', () => {
    navbar.classList.toggle('scrolled', window.scrollY > 40);
  }, { passive: true });
}

// ── Scroll Reveal (IntersectionObserver) ──
const reveals = document.querySelectorAll('.reveal');
if (reveals.length) {
  const revealObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('visible');
        revealObserver.unobserve(entry.target);
      }
    });
  }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });

  reveals.forEach(el => revealObserver.observe(el));
}

// ── GSAP Animations ──
document.addEventListener('DOMContentLoaded', () => {
  // Hero animation timeline
  if (typeof gsap !== 'undefined') {
    const heroSection = document.querySelector('.hero-section');
    if (heroSection) {
      const tl = gsap.timeline({ defaults: { duration: 0.8, ease: 'power3.out' } });

      tl.from('.hero-badge', { opacity: 0, y: 30, duration: 0.6 })
        .from('.hero-title', { opacity: 0, y: 40 }, '-=0.3')
        .from('.hero-desc', { opacity: 0, y: 30 }, '-=0.4')
        .from('.hero-actions', { opacity: 0, y: 20 }, '-=0.3')
        .from('.hero-stats', { opacity: 0, y: 20 }, '-=0.2')
        .from('.hero-phone-img', { opacity: 0, scale: 0.8, duration: 1 }, '-=0.6')
        .from('.hero-float-card', { opacity: 0, scale: 0.5, stagger: 0.15 }, '-=0.4');
    }

    // Product cards stagger
    if (typeof ScrollTrigger !== 'undefined') {
      gsap.registerPlugin(ScrollTrigger);

      gsap.utils.toArray('.product-card').forEach((card, i) => {
        gsap.from(card, {
          scrollTrigger: {
            trigger: card,
            start: 'top 85%',
            once: true
          },
          opacity: 0,
          y: 40,
          duration: 0.6,
          delay: (i % 4) * 0.1
        });
      });

      // Service cards
      gsap.utils.toArray('.service-card').forEach((card, i) => {
        gsap.from(card, {
          scrollTrigger: {
            trigger: card,
            start: 'top 85%',
            once: true
          },
          opacity: 0,
          y: 30,
          duration: 0.5,
          delay: (i % 3) * 0.12
        });
      });

      // Category cards
      gsap.utils.toArray('.category-card').forEach((card, i) => {
        gsap.from(card, {
          scrollTrigger: {
            trigger: card,
            start: 'top 90%',
            once: true
          },
          opacity: 0,
          y: 20,
          scale: 0.9,
          duration: 0.5,
          delay: i * 0.08
        });
      });

      // Glass cards
      gsap.utils.toArray('.glass-card').forEach((card) => {
        gsap.from(card, {
          scrollTrigger: {
            trigger: card,
            start: 'top 85%',
            once: true
          },
          opacity: 0,
          y: 30,
          duration: 0.6
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
  }, 4000);
}

// ── Counter Animation ──
function animateCounter(element, target, duration = 2000) {
  let start = 0;
  const step = target / (duration / 16);
  const timer = setInterval(() => {
    start += step;
    if (start >= target) {
      element.textContent = target;
      clearInterval(timer);
    } else {
      element.textContent = Math.floor(start);
    }
  }, 16);
}

// Animate hero stats
const statNums = document.querySelectorAll('.hero-stat-num');
statNums.forEach(stat => {
  const text = stat.textContent;
  const num = parseInt(text);
  if (!isNaN(num) && num > 0) {
    const suffix = text.replace(num.toString(), '');
    stat.textContent = '0' + suffix;
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          let current = 0;
          const step = num / 60;
          const timer = setInterval(() => {
            current += step;
            if (current >= num) {
              stat.textContent = text;
              clearInterval(timer);
            } else {
              stat.textContent = Math.floor(current) + suffix;
            }
          }, 25);
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.5 });
    observer.observe(stat);
  }
});

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
