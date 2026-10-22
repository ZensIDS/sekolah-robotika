// Animasi muncul saat scroll
const io = new IntersectionObserver((entries) => {
  entries.forEach((e) => {
    if (!e.isIntersecting) return;
    e.target.classList.add('show');
    if (e.target.dataset.count) countUp(e.target);
    io.unobserve(e.target);
  });
}, { threshold: 0.2 });

document.querySelectorAll('.reveal, [data-count]').forEach((el) => io.observe(el));

function countUp(el) {
  const target = parseInt(el.dataset.count, 10);
  const start = performance.now();
  const tick = (now) => {
    const p = Math.min((now - start) / 1500, 1);
    el.textContent = Math.floor(target * (1 - Math.pow(1 - p, 3))).toLocaleString('id-ID');
    if (p < 1) requestAnimationFrame(tick);
  };
  requestAnimationFrame(tick);
}

// Menu mobile
const btn = document.getElementById('menu-btn');
const menu = document.getElementById('menu');
btn?.addEventListener('click', () => menu.classList.toggle('hidden'));
menu?.querySelectorAll('a').forEach((a) => a.addEventListener('click', () => menu.classList.add('hidden')));