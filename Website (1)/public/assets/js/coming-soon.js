// ── CANVAS PARTICLE BACKGROUND ──
const canvas = document.getElementById('mk-cs-bg-canvas');
const ctx = canvas.getContext('2d');
let W, H, particles = [];

function resize() {
  W = canvas.width  = window.innerWidth;
  H = canvas.height = window.innerHeight;
}
resize();
window.addEventListener('resize', () => { resize(); initParticles(); });

function initParticles() {
  particles = [];
  const count = Math.min(60, Math.floor((W * H) / 20000));
  for (let i = 0; i < count; i++) {
    particles.push({
      x: Math.random() * W,
      y: Math.random() * H,
      r: Math.random() * 1.5 + 0.3,
      vx: (Math.random() - 0.5) * 0.18,
      vy: (Math.random() - 0.5) * 0.18,
      a: Math.random() * 0.55 + 0.1
    });
  }
}
initParticles();

function drawBg() {
  // radial gradient bg
  const grad = ctx.createRadialGradient(W * 0.5, H * 0.3, 0, W * 0.5, H * 0.5, W * 0.9);
  grad.addColorStop(0,   '#ffffff');
  grad.addColorStop(0.4, '#f8fafc');
  grad.addColorStop(1,   '#e2e8f0');
  ctx.fillStyle = grad;
  ctx.fillRect(0, 0, W, H);

  // accent glow top-left
  const g2 = ctx.createRadialGradient(W * 0.15, H * 0.15, 0, W * 0.15, H * 0.15, W * 0.5);
  g2.addColorStop(0, 'rgba(187,215,0,0.06)');
  g2.addColorStop(1, 'rgba(187,215,0,0)');
  ctx.fillStyle = g2;
  ctx.fillRect(0, 0, W, H);

  // accent glow bottom-right
  const g3 = ctx.createRadialGradient(W * 0.85, H * 0.85, 0, W * 0.85, H * 0.85, W * 0.6);
  g3.addColorStop(0, 'rgba(21,57,121,0.05)');
  g3.addColorStop(1, 'rgba(21,57,121,0)');
  ctx.fillStyle = g3;
  ctx.fillRect(0, 0, W, H);
}

function drawParticles() {
  particles.forEach(p => {
    ctx.beginPath();
    ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
    ctx.fillStyle = `rgba(187,215,0,${p.a * 1.5})`;
    ctx.fill();
    p.x += p.vx; p.y += p.vy;
    if (p.x < 0) p.x = W; if (p.x > W) p.x = 0;
    if (p.y < 0) p.y = H; if (p.y > H) p.y = 0;
  });
}

function loop() {
  drawBg();
  drawParticles();
  requestAnimationFrame(loop);
}
loop();

// ── FLOATING LEAF PARTICLES ──
const leafSVGs = [
  `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M17 8C8 10 5.9 16.17 3.82 21.34L5.71 22l1-2.3A4.49 4.49 0 0 0 8 20C19 20 22 3 22 3c-1 2-8 5-8 5" fill="rgba(187,215,0,0.5)"/></svg>`,
  `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><ellipse cx="12" cy="14" rx="6" ry="8" fill="rgba(187,215,0,0.3)" transform="rotate(-20,12,14)"/></svg>`
];

function createLeaf() {
  const el = document.createElement('div');
  el.className = 'mk-cs-leaf-particle';
  const svg = leafSVGs[Math.floor(Math.random() * leafSVGs.length)];
  el.innerHTML = svg;
  const size = 14 + Math.random() * 18;
  el.style.cssText = `
    width:${size}px; height:${size}px;
    left:${Math.random() * 100}vw;
    animation-duration:${9 + Math.random() * 12}s;
    animation-delay:${Math.random() * 8}s;
  `;
  document.body.appendChild(el);
  setTimeout(() => el.remove(), 22000);
}
setInterval(createLeaf, 1400);
for (let i = 0; i < 5; i++) setTimeout(createLeaf, i * 500);

// ── COUNTDOWN ──
// Set a fixed launch date (e.g., July 10, 2026)
const launchDate = new Date('2026-07-10T00:00:00');

let prevVals = { days: -1, hours: -1, mins: -1, secs: -1 };

function updateCountdown() {
  const now  = new Date();
  const diff = launchDate - now;
  if (diff <= 0) { return; }

  const days  = Math.floor(diff / 86400000);
  const hours = Math.floor((diff % 86400000) / 3600000);
  const mins  = Math.floor((diff % 3600000)  / 60000);
  const secs  = Math.floor((diff % 60000)    / 1000);

  function set(id, val, prev) {
    const el = document.getElementById(id);
    if (!el) return;
    const str = String(val).padStart(2, '0');
    if (val !== prev) {
      el.classList.remove('mk-cs-flip');
      void el.offsetWidth;
      el.classList.add('mk-cs-flip');
    }
    el.textContent = str;
  }

  set('mk-cs-cd-days',  days,  prevVals.days);
  set('mk-cs-cd-hours', hours, prevVals.hours);
  set('mk-cs-cd-mins',  mins,  prevVals.mins);
  set('mk-cs-cd-secs',  secs,  prevVals.secs);
  prevVals = { days, hours, mins, secs };
}
updateCountdown();
setInterval(updateCountdown, 1000);

// ── PROGRESS BAR ──
window.addEventListener('load', () => {
  setTimeout(() => {
    const el = document.getElementById('mk-cs-progress-fill');
    if (el) el.style.width = '78%';
  }, 300);
});

// ── MOUSE PARALLAX (desktop only) ──
if (window.innerWidth > 768) {
  document.addEventListener('mousemove', e => {
    const mx = (e.clientX / window.innerWidth  - 0.5) * 18;
    const my = (e.clientY / window.innerHeight - 0.5) * 18;
    document.querySelectorAll('.mk-cs-ring').forEach((r, i) => {
      r.style.transform = `translate(calc(-50% + ${mx * (i + 1) * 0.5}px), calc(-50% + ${my * (i + 1) * 0.5}px))`;
    });
  });
}
