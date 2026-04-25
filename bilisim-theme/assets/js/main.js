/* ===== CUSTOM CURSOR ===== */
const cursor = document.getElementById('cursor');
const ring   = document.getElementById('cursor-ring');
let mx = 0, my = 0, rx = 0, ry = 0;

document.addEventListener('mousemove', e => { mx = e.clientX; my = e.clientY; });

function animateCursor() {
  if (cursor) { cursor.style.left = mx + 'px'; cursor.style.top = my + 'px'; }
  rx += (mx - rx) * .12;
  ry += (my - ry) * .12;
  if (ring) { ring.style.left = rx + 'px'; ring.style.top = ry + 'px'; }
  requestAnimationFrame(animateCursor);
}
animateCursor();

document.querySelectorAll('a, button, .bento-card, .glass-card, .campus-card, .news-card').forEach(el => {
  el.addEventListener('mouseenter', () => document.body.classList.add('hovering'));
  el.addEventListener('mouseleave', () => document.body.classList.remove('hovering'));
});

/* ===== PARTICLE CANVAS ===== */
const canvas = document.getElementById('particle-canvas');
if (canvas) {
  const ctx = canvas.getContext('2d');
  let W, H, particles = [];

  function resize() { W = canvas.width = window.innerWidth; H = canvas.height = window.innerHeight; }
  resize();
  window.addEventListener('resize', resize);

  class Particle {
    constructor() { this.reset(); }
    reset() {
      this.x = Math.random() * W;
      this.y = Math.random() * H;
      this.vx = (Math.random() - .5) * .4;
      this.vy = (Math.random() - .5) * .4;
      this.r = Math.random() * 1.5 + .3;
      this.a = Math.random();
    }
    update() {
      this.x += this.vx; this.y += this.vy;
      if (this.x < 0 || this.x > W || this.y < 0 || this.y > H) this.reset();
    }
    draw() {
      ctx.beginPath();
      ctx.arc(this.x, this.y, this.r, 0, Math.PI * 2);
      ctx.fillStyle = `rgba(0,212,255,${this.a * .6})`;
      ctx.fill();
    }
  }

  for (let i = 0; i < 120; i++) particles.push(new Particle());

  function drawLines() {
    for (let i = 0; i < particles.length; i++) {
      for (let j = i + 1; j < particles.length; j++) {
        const dx = particles[i].x - particles[j].x;
        const dy = particles[i].y - particles[j].y;
        const dist = Math.sqrt(dx * dx + dy * dy);
        if (dist < 100) {
          ctx.beginPath();
          ctx.moveTo(particles[i].x, particles[i].y);
          ctx.lineTo(particles[j].x, particles[j].y);
          ctx.strokeStyle = `rgba(0,212,255,${(1 - dist / 100) * .12})`;
          ctx.lineWidth = .5;
          ctx.stroke();
        }
      }
    }
  }

  function loop() {
    ctx.clearRect(0, 0, W, H);
    particles.forEach(p => { p.update(); p.draw(); });
    drawLines();
    requestAnimationFrame(loop);
  }
  loop();
}

/* ===== STICKY HEADER ===== */
const header = document.querySelector('.site-header');
window.addEventListener('scroll', () => {
  header?.classList.toggle('scrolled', window.scrollY > 60);
}, { passive: true });

/* ===== MOBILE DRAWER ===== */
const hamburger = document.querySelector('.hamburger');
const drawer    = document.querySelector('.mobile-drawer');
const mOverlay  = document.querySelector('.mobile-overlay');
const drawerClose = document.querySelector('.drawer-close');

const openDrawer  = () => { drawer?.classList.add('open'); mOverlay?.classList.add('show'); document.body.style.overflow = 'hidden'; hamburger?.classList.add('open'); };
const closeDrawer = () => { drawer?.classList.remove('open'); mOverlay?.classList.remove('show'); document.body.style.overflow = ''; hamburger?.classList.remove('open'); };

hamburger?.addEventListener('click', openDrawer);
drawerClose?.addEventListener('click', closeDrawer);
mOverlay?.addEventListener('click', closeDrawer);
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeDrawer(); });

/* ===== COUNTER (ease-out) ===== */
function animateCounter(el) {
  const target = parseFloat(el.dataset.target);
  const prefix = el.dataset.prefix || '';
  const suffix = el.dataset.suffix || '';
  const dur = 2400;
  let start = null;
  const step = ts => {
    if (!start) start = ts;
    const prog  = Math.min((ts - start) / dur, 1);
    const eased = 1 - Math.pow(1 - prog, 3);
    const val   = target * eased;
    el.textContent = prefix + (Number.isInteger(target) ? Math.round(val).toLocaleString('tr') : val.toFixed(1)) + suffix;
    if (prog < 1) requestAnimationFrame(step);
  };
  requestAnimationFrame(step);
}

new IntersectionObserver((entries, obs) => {
  entries.forEach(e => {
    if (!e.isIntersecting) return;
    e.target.querySelectorAll('[data-target]').forEach(animateCounter);
    obs.unobserve(e.target);
  });
}, { threshold: .3 }).observe(document.querySelector('.stats-section') || document.body);

/* ===== HERO STAT COUNTERS ===== */
document.querySelectorAll('.hero-stat .n[data-target]').forEach(el => setTimeout(() => animateCounter(el), 1000));

/* ===== SCROLL REVEAL (stagger) ===== */
const revealEls = document.querySelectorAll(
  '.bento-card, .glass-card, .campus-card, .news-card, .feat-item, .partner-item, .stat-block, .sec-head, .kaizen-left > *'
);
revealEls.forEach((el, i) => {
  el.classList.add('reveal');
  el.style.transitionDelay = (i % 6 * 0.07) + 's';
});
new IntersectionObserver((entries) => {
  entries.forEach(e => {
    if (e.isIntersecting) { e.target.classList.add('visible'); }
  });
}, { threshold: .08, rootMargin: '0px 0px -20px 0px' }).observe = (() => {
  const obs = new IntersectionObserver(entries => {
    entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('visible'); } });
  }, { threshold: .08 });
  revealEls.forEach(el => obs.observe(el));
  return obs.observe.bind(obs);
})();

/* ===== HERO PARALLAX ===== */
window.addEventListener('scroll', () => {
  const hero = document.querySelector('.hero');
  if (!hero) return;
  const s = window.scrollY;
  if (s < window.innerHeight) {
    hero.querySelector('.hero-bg').style.transform = `translateY(${s * .25}px)`;
    hero.querySelector('.hero-grid').style.transform = `translateY(${s * .15}px)`;
  }
}, { passive: true });

/* ===== ANNOUNCEMENT CLOSE ===== */
document.querySelector('.announce-close')?.addEventListener('click', function() {
  const bar = this.closest('.announce-bar');
  if (!bar) return;
  bar.style.transition = 'max-height .4s ease, opacity .4s ease, padding .4s ease';
  bar.style.maxHeight = bar.offsetHeight + 'px';
  requestAnimationFrame(() => { bar.style.maxHeight = '0'; bar.style.opacity = '0'; bar.style.padding = '0'; });
  setTimeout(() => bar.remove(), 430);
});

/* ===== LAZY IMAGES ===== */
document.querySelectorAll('img[loading="lazy"]').forEach(img => {
  img.style.cssText += 'opacity:0;transition:opacity .6s ease';
  const show = () => { img.style.opacity = '1'; };
  img.complete ? show() : img.addEventListener('load', show);
});

/* ===== MAGNETIC BUTTONS ===== */
document.querySelectorAll('.btn-glow').forEach(btn => {
  btn.addEventListener('mousemove', e => {
    const r = btn.getBoundingClientRect();
    const x = e.clientX - r.left - r.width / 2;
    const y = e.clientY - r.top  - r.height / 2;
    btn.style.transform = `translateY(-3px) translate(${x * .12}px, ${y * .12}px)`;
  });
  btn.addEventListener('mouseleave', () => { btn.style.transform = ''; });
});
