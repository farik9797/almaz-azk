/*
 * Переключение языка сайта ТОО «АЗК Алмаз».
 *
 * Русский текст живёт прямо в index.html (это источник истины — правки клиента вносятся туда).
 * Переводы лежат в assets/i18n/<lang>.js и подгружаются по требованию.
 * Ключи проставлены атрибутами data-i18n / data-i18n-<attr>, см. tools/i18n-extract.py.
 */
(function () {
  'use strict';

  // numberLocale — только для группировки разрядов в числах (toLocaleString).
  // У казахского намеренно ru-RU: в kk-KZ браузер ставит запятую, а принятый разделитель — пробел.
  var LANGS = {
    ru: { label: 'Русский',  short: 'RU',   numberLocale: 'ru-RU', htmlLang: 'ru' },
    en: { label: 'English',  short: 'EN',   numberLocale: 'en-US', htmlLang: 'en' },
    kk: { label: 'Қазақша',  short: 'KZ',   numberLocale: 'ru-RU', htmlLang: 'kk' },
    zh: { label: '中文',      short: '中文', numberLocale: 'zh-CN', htmlLang: 'zh-Hans' }
  };
  var DEFAULT = 'ru';
  var STORE_KEY = 'azk_lang';
  var ATTRS = ['alt', 'title', 'aria-label', 'placeholder', 'content'];

  window.AZK_I18N = window.AZK_I18N || {};

  // Версию берём из собственного тега <script src="...i18n.js?v=N"> и вешаем на словари,
  // чтобы после деплоя браузер не подсунул старый перевод к новой разметке.
  var VER = (function () {
    var el = document.currentScript;
    var m = el && /\?v=([^&]+)/.exec(el.src || '');
    return m ? '?v=' + m[1] : '';
  })();

  function normalize(l) {
    if (!l) return null;
    l = String(l).toLowerCase().split('-')[0];
    if (l === 'kz') l = 'kk';
    return LANGS[l] ? l : null;
  }

  function stored() {
    try { return normalize(localStorage.getItem(STORE_KEY)); } catch (e) { return null; }
  }

  function initialLang() {
    var m = /[?&]lang=([a-zA-Z-]+)/.exec(location.search);
    // Язык браузера намеренно не учитываем: у большинства местных посетителей
    // системный язык английский, а сайт для них должен открываться по-русски.
    return (m && normalize(m[1])) || stored() || DEFAULT;
  }

  var current = initialLang();
  var missing = {};
  var booted = false;

  /* ---------- словари ---------- */

  function dict(lang) { return window.AZK_I18N[lang] || null; }

  function load(lang, cb) {
    if (lang === DEFAULT || dict(lang)) return cb(true);
    var s = document.createElement('script');
    s.src = 'assets/i18n/' + lang + '.js' + VER;
    s.onload = function () { cb(!!dict(lang)); };
    s.onerror = function () { cb(false); };
    (document.head || document.documentElement).appendChild(s);
  }

  /* ---------- перевод значений ---------- */

  function t(key, fallback) {
    if (current === DEFAULT) return fallback;
    var d = dict(current);
    if (d && d[key] != null) return d[key];
    missing[key] = true;
    return fallback;
  }

  function tList(key, fallback) {
    var v = t(key, null);
    return Array.isArray(v) ? v : fallback;
  }

  /* ---------- применение к DOM ---------- */

  function original(el, attr) {
    var prop = '__azk_' + (attr || 'text');
    if (el[prop] === undefined) {
      el[prop] = attr ? (el.getAttribute(attr) || '') : el.textContent;
    }
    return el[prop];
  }

  function applyTo(root) {
    root.querySelectorAll('[data-i18n]').forEach(function (el) {
      var raw = original(el);
      var lead = raw.match(/^\s*/)[0];
      var trail = raw.match(/\s*$/)[0];
      el.textContent = lead + t(el.dataset.i18n, raw.trim()) + trail;
    });
    ATTRS.forEach(function (attr) {
      root.querySelectorAll('[data-i18n-' + attr + ']').forEach(function (el) {
        var key = el.getAttribute('data-i18n-' + attr);
        el.setAttribute(attr, t(key, original(el, attr)));
      });
    });
  }

  function paintSwitcher() {
    var cur = document.getElementById('lang-current');
    if (cur) cur.textContent = LANGS[current].short;
    document.querySelectorAll('.lang-opt').forEach(function (b) {
      var on = b.dataset.lang === current;
      b.setAttribute('aria-selected', on ? 'true' : 'false');
      b.classList.toggle('text-navy', on);
      b.classList.toggle('font-bold', on);
      b.classList.toggle('bg-slate-50', on);
      b.classList.toggle('text-slate-600', !on);
    });
  }

  function reveal() { document.documentElement.classList.remove('i18n-pending'); }

  function render() {
    document.documentElement.lang = LANGS[current].htmlLang;
    applyTo(document);
    paintSwitcher();
    document.dispatchEvent(new CustomEvent('azk:langchange', { detail: { lang: current, initial: !booted } }));
    booted = true;
    reveal();
  }

  function setLang(lang, opts) {
    lang = normalize(lang) || DEFAULT;
    current = lang;
    try { localStorage.setItem(STORE_KEY, lang); } catch (e) {}
    if (!(opts && opts.keepUrl)) {
      var url = new URL(location.href);
      if (lang === DEFAULT) url.searchParams.delete('lang');
      else url.searchParams.set('lang', lang);
      history.replaceState(null, '', url);
    }
    load(lang, function () { render(); });
  }

  /* ---------- переключатель ---------- */

  function wireSwitcher() {
    var btn = document.getElementById('lang-btn');
    var menu = document.getElementById('lang-menu');
    if (!btn || !menu) return;
    function close() { menu.classList.add('hidden'); btn.setAttribute('aria-expanded', 'false'); }
    function toggle() {
      var open = menu.classList.toggle('hidden');
      btn.setAttribute('aria-expanded', open ? 'false' : 'true');
    }
    btn.addEventListener('click', function (e) { e.stopPropagation(); toggle(); });
    document.addEventListener('click', function (e) {
      if (!menu.contains(e.target) && e.target !== btn) close();
    });
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape') close(); });
    document.querySelectorAll('.lang-opt').forEach(function (b) {
      b.addEventListener('click', function () { close(); setLang(b.dataset.lang); });
    });
  }

  /* ---------- старт ---------- */

  function boot() {
    wireSwitcher();
    load(current, function (ok) {
      if (!ok && current !== DEFAULT) current = DEFAULT;  // словарь не загрузился — показываем русский
      render();
    });
  }

  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', boot);
  else boot();

  // страховка: если словарь завис, страницу всё равно надо показать
  setTimeout(reveal, 2500);

  window.AZK = {
    t: t,
    tList: tList,
    langs: LANGS,
    setLang: setLang,
    get lang() { return current; },
    get locale() { return LANGS[current].numberLocale; },
    missingKeys: function () { return Object.keys(missing).sort(); }
  };
})();
