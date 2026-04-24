/* ===== MOBILE NAV ===== */
const hamburger  = document.querySelector('.hamburger');
const mobileNav  = document.querySelector('.mobile-nav');
const overlay    = document.querySelector('.overlay');
const closeBtn   = document.querySelector('.mobile-nav-close');

const openNav  = () => { mobileNav.classList.add('open');  overlay.classList.add('show');  document.body.style.overflow = 'hidden'; hamburger.classList.add('open'); };
const closeNav = () => { mobileNav.classList.remove('open'); overlay.classList.remove('show'); document.body.style.overflow = ''; hamburger.classList.remove('open'); };

hamburger?.addEventListener('click', openNav);
closeBtn?.addEventListener('click', closeNav);
overlay?.addEventListener('click', closeNav);
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeNav(); });

/* ===== STICKY HEADER ===== */
const header = document.querySelector('.header');
window.addEventListener('scroll', () => {
  header?.classList.toggle('scrolled', window.scrollY > 60);
}, { passive: true });

/* ===== ANNOUNCEMENT CLOSE ===== */
const announceClose = document.querySelector('.announce-close');
const announce = document.querySelector('.announce');
announceClose?.addEventListener('click', () => {
  if (!announce) return;
  announce.style.transition = 'max-height .4s ease, opacity .4s ease, padding .4s ease';
  announce.style.maxHeight = announce.offsetHeight + 'px';
  requestAnimationFrame(() => {
    announce.style.maxHeight = '0';
    announce.style.opacity = '0';
    announce.style.padding = '0';
  });
  setTimeout(() => announce.remove(), 430);
});

/* ===== COUNTER (ease-out cubic) ===== */
function animateCounter(el) {
  const target = parseFloat(el.dataset.target);
  const prefix = el.dataset.prefix || '';
  const suffix = el.dataset.suffix || '';
  const dur    = 2000;
  let   start  = null;

  const step = ts => {
    if (!start) start = ts;
    const prog   = Math.min((ts - start) / dur, 1);
    const eased  = 1 - Math.pow(1 - prog, 3);
    const val    = target * eased;
    el.textContent = prefix + fmt(val, target) + suffix;
    if (prog < 1) requestAnimationFrame(step);
    else el.textContent = prefix + fmt(target, target) + suffix;
  };
  requestAnimationFrame(step);
}
function fmt(v, t) {
  if (String(t).includes('.')) return v.toFixed(1);
  return Math.round(v).toLocaleString('tr-TR');
}

const counterObs = new IntersectionObserver(entries => {
  entries.forEach(e => {
    if (!e.isIntersecting) return;
    e.target.querySelectorAll('[data-target]').forEach(el => animateCounter(el));
    counterObs.unobserve(e.target);
  });
}, { threshold: 0.3 });
document.querySelectorAll('.stats, .hero').forEach(el => counterObs.observe(el));

/* ===== REVEAL ON SCROLL (stagger per group) ===== */
const revealTargets = document.querySelectorAll(
  '.level-card, .model-card, .campus-card, .news-card, .kaizen-feat, .why-item, .partner-logo, .stat-item'
);
revealTargets.forEach((el, i) => {
  el.style.cssText += `opacity:0; transform:translateY(26px); transition:opacity .55s ease ${(i % 5) * 0.08}s, transform .55s ease ${(i % 5) * 0.08}s;`;
});

const revealObs = new IntersectionObserver(entries => {
  entries.forEach(e => {
    if (!e.isIntersecting) return;
    e.target.style.opacity = '1';
    e.target.style.transform = 'translateY(0)';
    revealObs.unobserve(e.target);
  });
}, { threshold: 0.1, rootMargin: '0px 0px -30px 0px' });
revealTargets.forEach(el => revealObs.observe(el));

/* ===== SECTION HEADING REVEAL ===== */
document.querySelectorAll('h2, .section-label').forEach(el => {
  el.style.cssText += 'opacity:0; transform:translateY(20px); transition:opacity .6s ease, transform .6s ease;';
  new IntersectionObserver(([e], obs) => {
    if (!e.isIntersecting) return;
    e.target.style.opacity = '1';
    e.target.style.transform = 'translateY(0)';
    obs.disconnect();
  }, { threshold: 0.2 }).observe(el);
});

/* ===== HERO PARALLAX ===== */
const heroEl = document.querySelector('.hero');
window.addEventListener('scroll', () => {
  if (!heroEl) return;
  const s = window.scrollY;
  if (s < window.innerHeight)
    heroEl.style.backgroundPositionY = `calc(50% + ${s * 0.3}px)`;
}, { passive: true });

/* ===== LAZY IMAGE FADE ===== */
document.querySelectorAll('img[loading="lazy"]').forEach(img => {
  img.style.cssText += 'opacity:0; transition:opacity .5s ease;';
  const show = () => { img.style.opacity = '1'; };
  img.complete ? show() : img.addEventListener('load', show);
});

/* ===== SMOOTH SCROLL ===== */
document.querySelectorAll('a[href^="#"]').forEach(a => {
  a.addEventListener('click', e => {
    const t = document.querySelector(a.getAttribute('href'));
    if (t) { e.preventDefault(); t.scrollIntoView({ behavior: 'smooth' }); }
  });
});
