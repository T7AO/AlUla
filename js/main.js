// ============================================
// Main JS — language toggle, nav, scroll reveal
// ============================================

// ----- Bilingual toggle (Arabic RTL / English LTR) -----
function setLang(lang) {
  const body = document.body;
  if (lang === 'ar') {
    body.setAttribute('dir', 'rtl');
    body.setAttribute('lang', 'ar');
  } else {
    body.setAttribute('dir', 'ltr');
    body.setAttribute('lang', 'en');
  }
  try { localStorage.setItem('alula_lang', lang); } catch (e) {}
  const btn = document.getElementById('langBtn');
  if (btn) btn.textContent = (lang === 'ar') ? 'English' : 'العربية';
}

function toggleLang() {
  const current = document.body.getAttribute('dir') === 'rtl' ? 'ar' : 'en';
  setLang(current === 'ar' ? 'en' : 'ar');
}

// Apply saved language on load (default: Arabic)
(function () {
  let saved = 'ar';
  try { saved = localStorage.getItem('alula_lang') || 'ar'; } catch (e) {}
  document.addEventListener('DOMContentLoaded', function () { setLang(saved); });
})();

// ----- Highlight active nav link -----
document.addEventListener('DOMContentLoaded', function () {
  const page = window.location.pathname.split('/').pop() || 'index.html';
  document.querySelectorAll('nav.main-nav a').forEach(function (a) {
    if (a.getAttribute('href') === page) a.classList.add('active');
  });
});

// ----- Scroll reveal -----
document.addEventListener('DOMContentLoaded', function () {
  document.body.classList.add('js-ready');
  const reveals = document.querySelectorAll('.reveal');
  if (!('IntersectionObserver' in window)) {
    reveals.forEach(function (r) { r.classList.add('in'); });
    return;
  }
  const obs = new IntersectionObserver(function (entries) {
    entries.forEach(function (e) {
      if (e.isIntersecting) { e.target.classList.add('in'); obs.unobserve(e.target); }
    });
  }, { threshold: 0.12 });
  reveals.forEach(function (r) { obs.observe(r); });
});

// ----- Gallery lightbox -----
function openLightbox(src) {
  let lb = document.getElementById('lightbox');
  if (lb) {
    document.getElementById('lightbox-img').src = src;
    lb.style.display = 'flex';
  }
}
function closeLightbox() {
  const lb = document.getElementById('lightbox');
  if (lb) lb.style.display = 'none';
}
