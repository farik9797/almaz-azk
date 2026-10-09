(function () {
  document.addEventListener('DOMContentLoaded', function () {
    if (window.lucide) lucide.createIcons();

    /* Mobile menu */
    var menuBtn = document.getElementById('menu-btn');
    var mobileMenu = document.getElementById('mobile-menu');
    if (menuBtn && mobileMenu) {
      menuBtn.addEventListener('click', function () { mobileMenu.classList.toggle('hidden'); });
      document.querySelectorAll('.mobile-link').forEach(function (a) {
        a.addEventListener('click', function () { mobileMenu.classList.add('hidden'); });
      });
    }

    /* About gallery tabs */
    document.querySelectorAll('#gallery-tabs .tab-btn').forEach(function (btn) {
      btn.addEventListener('click', function () {
        document.querySelectorAll('#gallery-tabs .tab-btn').forEach(function (b) {
          b.classList.remove('active'); b.classList.add('text-slate-600');
        });
        btn.classList.add('active'); btn.classList.remove('text-slate-600');
        var img = document.getElementById('gallery-img');
        if (img) img.src = btn.dataset.image;
        var badge = document.getElementById('gallery-badge'); if (badge) badge.textContent = btn.dataset.badge;
        var title = document.getElementById('gallery-title'); if (title) title.textContent = btn.dataset.title;
        var subtitle = document.getElementById('gallery-subtitle'); if (subtitle) subtitle.textContent = btn.dataset.subtitle;
      });
    });

    /* AZS stations */
    var stationsDataEl = document.getElementById('azk-stations-data');
    var stations = {};
    if (stationsDataEl) { try { stations = JSON.parse(stationsDataEl.textContent || '{}'); } catch (e) { stations = {}; } }

    function selectStation(id) {
      var s = stations[id];
      if (!s) return;
      var nameEl = document.getElementById('station-name'); if (nameEl) nameEl.textContent = s.name;
      var addrEl = document.getElementById('station-address'); if (addrEl) addrEl.textContent = s.address;
      var dirEl = document.getElementById('station-direction'); if (dirEl) dirEl.textContent = s.direction;
      var routeEl = document.getElementById('station-route');
      if (routeEl) routeEl.href = 'https://yandex.ru/maps/?text=' + encodeURIComponent(s.address);
      var amenitiesEl = document.getElementById('station-amenities');
      if (amenitiesEl) {
        amenitiesEl.innerHTML = (s.amenities || []).map(function (a) {
          return '<span class="bg-navy/90 text-slate-200 px-2 py-1 rounded border border-slate-700">' + a + '</span>';
        }).join('');
      }
      document.querySelectorAll('.marker span').forEach(function (m) {
        m.classList.remove('bg-accent', 'text-navy', 'border-white');
        m.classList.add('bg-navy', 'text-white', 'border-accent');
      });
      document.querySelectorAll('.marker').forEach(function (m) {
        if (m.dataset.station === id) {
          var span = m.querySelector('span');
          span.classList.remove('bg-navy', 'text-white', 'border-accent');
          span.classList.add('bg-accent', 'text-navy', 'border-white');
        }
      });
      document.querySelectorAll('.station-card').forEach(function (c) {
        if (c.dataset.station === id) { c.classList.add('active', 'border-navy'); c.classList.remove('border-slate-200'); }
        else { c.classList.remove('active', 'border-navy'); c.classList.add('border-slate-200'); }
      });
    }
    document.querySelectorAll('.marker, .station-card').forEach(function (el) {
      el.addEventListener('click', function () { selectStation(el.dataset.station); });
    });

    document.querySelectorAll('.azs-filter-btn').forEach(function (btn) {
      btn.addEventListener('click', function () {
        document.querySelectorAll('.azs-filter-btn').forEach(function (b) {
          b.classList.remove('bg-navy', 'text-white'); b.classList.add('text-slate-600');
        });
        btn.classList.add('bg-navy', 'text-white'); btn.classList.remove('text-slate-600');
        var filter = btn.dataset.filter;
        document.querySelectorAll('.station-card').forEach(function (c) {
          c.style.display = (filter === 'all' || c.dataset.region === filter) ? '' : 'none';
        });
      });
    });

    /* FAQ chevron rotation
       Lucide swaps the original <i data-lucide="chevron-down"> for an <svg> on init,
       so the icon must be looked up as svg (or i, before Lucide has run) — not just i. */
    document.querySelectorAll('#faq details').forEach(function (d) {
      d.addEventListener('toggle', function () {
        var icon = d.querySelector('summary svg, summary i');
        if (icon) icon.style.transform = d.open ? 'rotate(180deg)' : 'none';
      });
    });

    /* Policy modal */
    var policyDataEl = document.getElementById('azk-policy-data');
    var policyContent = {};
    if (policyDataEl) { try { policyContent = JSON.parse(policyDataEl.textContent || '{}'); } catch (e) { policyContent = {}; } }
    var policyModal = document.getElementById('policy-modal');
    if (policyModal) {
      document.querySelectorAll('.policy-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
          var c = policyContent[btn.dataset.policy];
          if (!c) return;
          document.getElementById('policy-title').textContent = c.title;
          document.getElementById('policy-body').innerHTML = c.body;
          policyModal.classList.remove('hidden'); policyModal.classList.add('flex');
        });
      });
      function closePolicy() { policyModal.classList.add('hidden'); policyModal.classList.remove('flex'); }
      var closeBtn1 = document.getElementById('policy-close'); if (closeBtn1) closeBtn1.addEventListener('click', closePolicy);
      var closeBtn2 = document.getElementById('policy-close-2'); if (closeBtn2) closeBtn2.addEventListener('click', closePolicy);
      policyModal.addEventListener('click', function (e) { if (e.target === policyModal) closePolicy(); });
    }

    /* Active nav link highlight on scroll */
    var sections = ['about', 'services', 'products', 'azs', 'faq', 'contacts'];
    var navLinks = document.querySelectorAll('nav a[href^="#"]');
    window.addEventListener('scroll', function () {
      var pos = window.scrollY + 140;
      var current = '';
      sections.forEach(function (id) {
        var el = document.getElementById(id);
        if (el && pos >= el.offsetTop && pos < el.offsetTop + el.offsetHeight) current = id;
      });
      navLinks.forEach(function (a) {
        if (a.getAttribute('href') === '#' + current) a.classList.add('text-accent');
        else a.classList.remove('text-accent');
      });
    });
  });
})();
