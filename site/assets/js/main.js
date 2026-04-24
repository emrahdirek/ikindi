/* =====================
   MOBILE NAV
===================== */
const hamburger = document.querySelector('.hamburger');
const mobileNav = document.querySelector('.mobile-nav');
const overlay   = document.querySelector('.overlay');
const closeBtn  = document.querySelector('.mobile-nav-close');

const openNav  = () => { mobileNav.classList.add('open');  overlay.classList.add('show');  document.body.style.overflow = 'hidden'; };
const closeNav = () => { mobileNav.classList.remove('open'); overlay.classList.remove('show'); document.body.style.overflow = ''; };

hamburger?.addEventListener('click', openNav);
closeBtn?.addEventListener('click', closeNav);
overlay?.addEventListener('click', closeNav);
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeNav(); });

/* =====================
   STICKY HEADER
===================== */
const header = document.querySelector('.header');
window.addEventListener('scroll', () => {
  header.classList.toggle('scrolled', window.scrollY > 60);
}, { passive: true });

/* =====================
   HAMBURGER MORPH
===================== */
hamburger?.addEventListener('click', () => hamburger.classList.toggle('active'));
closeBtn?.addEventListener('click', () => hamburger?.classList.remove('active'));
overlay?.addEventListener('click', () => hamburger?.classList.remove('active'));

/* =====================
   SMOOTH SCROLL
===================== */
document.querySelectorAll('a[href^="#"]').forEach(a => {
  a.addEventListener('click', e => {
    const target = document.querySelector(a.getAttribute('href'));
    if (target) { e.preventDefault(); target.scrollIntoView({ behavior: 'smooth', block: 'start' }); }
  });
});

/* =====================
   COUNTER ANIMATION
===================== */
function animateCounter(el) {
  const target   = parseFloat(el.dataset.target);
  const prefix   = el.dataset.prefix || '';
  const suffix   = el.dataset.suffix || '';
  const duration = 2200;
  const fps      = 60;
  const steps    = Math.round(duration / (1000 / fps));
  let   current  = 0;
  let   frame    = 0;

  const tick = () => {
    frame++;
    const progress = frame / steps;
    // Ease-out cubic
    const eased = 1 - Math.pow(1 - progress, 3);
    current = target * eased;

    if (frame >= steps) {
      el.textContent = prefix + formatNum(target, suffix) + suffix;
      return;
    }
    el.textContent = prefix + formatNum(current, suffix) + suffix;
    requestAnimationFrame(tick);
  };
  requestAnimationFrame(tick);
}

function formatNum(n, suffix) {
  if (suffix === ':1') return Math.round(n);
  if (n % 1 !== 0) return n.toFixed(1);
  return Math.floor(n).toLocaleString();
}

/* =====================
   INTERSECTION OBSERVER — COUNTERS
===================== */
const counterObserver = new IntersectionObserver(entries => {
  entries.forEach(entry => {
    if (!entry.isIntersecting) return;
    entry.target.querySelectorAll('[data-target]').forEach(el => {
      el.textContent = (el.dataset.prefix || '') + '0' + (el.dataset.suffix || '');
      animateCounter(el);
    });
    counterObserver.unobserve(entry.target);
  });
}, { threshold: 0.25 });

document.querySelectorAll('.stats, .hero').forEach(el => counterObserver.observe(el));

/* =====================
   REVEAL ON SCROLL (with stagger)
===================== */
const revealEls = document.querySelectorAll(
  '.level-card, .value-card, .story-card, .news-card, .event-item, .pillar, .section-label, .welcome-text h2, .welcome-text .lead'
);

revealEls.forEach((el, i) => {
  el.style.opacity = '0';
  el.style.transform = 'translateY(28px)';
  el.style.transition = `opacity .6s ease ${(i % 4) * 0.1}s, transform .6s ease ${(i % 4) * 0.1}s`;
});

const revealObserver = new IntersectionObserver(entries => {
  entries.forEach(entry => {
    if (!entry.isIntersecting) return;
    entry.target.style.opacity = '1';
    entry.target.style.transform = 'translateY(0)';
    revealObserver.unobserve(entry.target);
  });
}, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });

revealEls.forEach(el => revealObserver.observe(el));

/* =====================
   HERO PARALLAX
===================== */
const hero = document.querySelector('.hero');
window.addEventListener('scroll', () => {
  if (!hero) return;
  const scrolled = window.scrollY;
  if (scrolled < window.innerHeight) {
    hero.style.backgroundPositionY = `calc(50% + ${scrolled * 0.35}px)`;
  }
}, { passive: true });

/* =====================
   STORY TABS (with fade)
===================== */
document.querySelectorAll('.tab-btn').forEach(btn => {
  btn.addEventListener('click', () => {
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');

    // Animate stories grid
    const grid = document.querySelector('.stories-grid');
    if (grid) {
      grid.style.opacity = '0';
      grid.style.transform = 'translateY(10px)';
      setTimeout(() => {
        grid.style.transition = 'opacity .4s ease, transform .4s ease';
        grid.style.opacity = '1';
        grid.style.transform = 'translateY(0)';
      }, 50);
    }
  });
});

/* =====================
   ACTIVE NAV ON SCROLL
===================== */
const sections = document.querySelectorAll('section[id]');
const navItems = document.querySelectorAll('.nav-links .nav-item > a');

window.addEventListener('scroll', () => {
  let current = '';
  sections.forEach(sec => {
    if (window.scrollY >= sec.offsetTop - 200) current = sec.id;
  });
  navItems.forEach(a => {
    a.style.color = a.getAttribute('href') === `#${current}` ? 'var(--gold)' : '';
  });
}, { passive: true });

/* =====================
   ANNOUNCEMENT BAR CLOSE
===================== */
const announce = document.querySelector('.announce');
if (announce) {
  const closeAnnounce = document.createElement('button');
  closeAnnounce.innerHTML = '✕';
  closeAnnounce.style.cssText = 'position:absolute;right:1rem;top:50%;transform:translateY(-50%);background:none;border:none;color:white;cursor:pointer;font-size:1rem;opacity:.8;';
  announce.style.position = 'relative';
  announce.appendChild(closeAnnounce);
  closeAnnounce.addEventListener('click', () => {
    announce.style.transition = 'max-height .4s ease, opacity .4s ease, padding .4s ease';
    announce.style.maxHeight = announce.offsetHeight + 'px';
    requestAnimationFrame(() => {
      announce.style.maxHeight = '0';
      announce.style.opacity = '0';
      announce.style.padding = '0';
    });
    setTimeout(() => announce.remove(), 420);
  });
}

/* =====================
   IMAGE LAZY LOAD FADE
===================== */
document.querySelectorAll('img[loading="lazy"]').forEach(img => {
  img.style.opacity = '0';
  img.style.transition = 'opacity .5s ease';
  if (img.complete) {
    img.style.opacity = '1';
  } else {
    img.addEventListener('load', () => { img.style.opacity = '1'; });
  }
});

/* =====================
   INJECT DYNAMIC STYLES
===================== */
const dynStyle = document.createElement('style');
dynStyle.textContent = `
  .header { transition: box-shadow .3s ease, background .3s ease; }
  .header.scrolled { box-shadow: 0 4px 24px rgba(0,0,0,.18); }

  .hamburger.active span:nth-child(1) { transform: translateY(7px) rotate(45deg); }
  .hamburger.active span:nth-child(2) { opacity: 0; transform: scaleX(0); }
  .hamburger.active span:nth-child(3) { transform: translateY(-7px) rotate(-45deg); }
  .hamburger span { transition: transform .3s ease, opacity .3s ease; }

  .mobile-nav { transition: right .35s cubic-bezier(.4,0,.2,1); }

  .level-card, .value-card, .story-card, .news-card {
    will-change: transform, opacity;
  }

  .hero-content { animation: heroFadeUp .9s cubic-bezier(.4,0,.2,1) both; }
  @keyframes heroFadeUp {
    from { opacity:0; transform:translateY(40px); }
    to   { opacity:1; transform:translateY(0); }
  }

  .hero-badge { animation: heroBadge .7s ease .2s both; }
  @keyframes heroBadge {
    from { opacity:0; transform:scale(.85); }
    to   { opacity:1; transform:scale(1); }
  }

  .hero-btns { animation: heroFadeUp .8s ease .4s both; }
  .hero-stats { animation: heroFadeUp .8s ease .55s both; }

  .announce { overflow: hidden; }

  @media (prefers-reduced-motion: reduce) {
    *, *::before, *::after { animation-duration: 0.01ms !important; transition-duration: 0.01ms !important; }
  }
`;
document.head.appendChild(dynStyle);
