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

document.querySelectorAll('a, button, .bento-card, .model-card, .campus-card, .news-card').forEach(el => {
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
      this.vx = (Math.random() - .5) * .35;
      this.vy = (Math.random() - .5) * .35;
      this.r = Math.random() * 1.4 + .3;
      this.a = Math.random() * .7 + .1;
    }
    update() {
      this.x += this.vx; this.y += this.vy;
      if (this.x < 0 || this.x > W || this.y < 0 || this.y > H) this.reset();
    }
    draw() {
      ctx.beginPath();
      ctx.arc(this.x, this.y, this.r, 0, Math.PI * 2);
      ctx.fillStyle = `rgba(91,141,238,${this.a * .55})`;
      ctx.fill();
    }
  }

  for (let i = 0; i < 110; i++) particles.push(new Particle());

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
          ctx.strokeStyle = `rgba(91,141,238,${(1 - dist / 100) * .1})`;
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
const hamburger   = document.querySelector('.hamburger');
const drawer      = document.querySelector('.mobile-drawer');
const mOverlay    = document.querySelector('.mobile-overlay');
const drawerClose = document.querySelector('.drawer-close');

const openDrawer  = () => { drawer?.classList.add('open'); mOverlay?.classList.add('open'); document.body.style.overflow = 'hidden'; };
const closeDrawer = () => { drawer?.classList.remove('open'); mOverlay?.classList.remove('open'); document.body.style.overflow = ''; };

hamburger?.addEventListener('click', openDrawer);
drawerClose?.addEventListener('click', closeDrawer);
mOverlay?.addEventListener('click', closeDrawer);
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeDrawer(); });

/* ===== COUNTER ANIMATION ===== */
function animateCounter(el) {
  const target = parseFloat(el.dataset.target);
  const suffix = el.dataset.suffix || '';
  const dur = 2200;
  let start = null;
  const step = ts => {
    if (!start) start = ts;
    const prog  = Math.min((ts - start) / dur, 1);
    const eased = 1 - Math.pow(1 - prog, 3);
    const val   = target * eased;
    el.textContent = (Number.isInteger(target) ? Math.round(val) : val.toFixed(1)) + suffix;
    if (prog < 1) requestAnimationFrame(step);
  };
  requestAnimationFrame(step);
}

/* Stats section counters */
const statsSection = document.querySelector('.stats-section');
if (statsSection) {
  new IntersectionObserver((entries, obs) => {
    entries.forEach(e => {
      if (!e.isIntersecting) return;
      e.target.querySelectorAll('[data-target]').forEach(animateCounter);
      obs.unobserve(e.target);
    });
  }, { threshold: .3 }).observe(statsSection);
}

/* Hero panel counters — start after short delay */
setTimeout(() => {
  document.querySelectorAll('.hero-panel-card .num[data-target]').forEach(animateCounter);
}, 800);

/* ===== SCROLL REVEAL ===== */
const revealEls = document.querySelectorAll('[data-reveal]');
const revealObs = new IntersectionObserver(entries => {
  entries.forEach(e => {
    if (e.isIntersecting) {
      e.target.classList.add('visible');
      revealObs.unobserve(e.target);
    }
  });
}, { threshold: .08, rootMargin: '0px 0px -20px 0px' });

revealEls.forEach((el, i) => {
  el.style.transitionDelay = (i % 4 * 0.1) + 's';
  revealObs.observe(el);
});

/* ===== ANNOUNCEMENT CLOSE ===== */
document.querySelector('.announce-close')?.addEventListener('click', function() {
  const bar = this.closest('.announce-bar');
  if (!bar) return;
  bar.style.transition = 'max-height .4s ease, opacity .35s ease, padding .4s ease';
  bar.style.overflow = 'hidden';
  bar.style.maxHeight = bar.offsetHeight + 'px';
  requestAnimationFrame(() => {
    bar.style.maxHeight = '0';
    bar.style.opacity = '0';
    bar.style.padding = '0';
  });
  setTimeout(() => bar.remove(), 430);
});

/* ===== LAZY IMAGE FADE ===== */
document.querySelectorAll('img[loading="lazy"]').forEach(img => {
  img.style.cssText += 'opacity:0;transition:opacity .55s ease';
  const show = () => { img.style.opacity = '1'; };
  img.complete ? show() : img.addEventListener('load', show);
});

/* ===== MAGNETIC BUTTONS ===== */
document.querySelectorAll('.btn-glow').forEach(btn => {
  btn.addEventListener('mousemove', e => {
    const r = btn.getBoundingClientRect();
    const x = e.clientX - r.left - r.width / 2;
    const y = e.clientY - r.top  - r.height / 2;
    btn.style.transform = `translateY(-2px) translate(${x * .1}px, ${y * .1}px)`;
  });
  btn.addEventListener('mouseleave', () => { btn.style.transform = ''; });
});
