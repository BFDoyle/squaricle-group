/* Squaricle Group – cookie consent.
   First-party, no dependencies. Stores the visitor's choice in one cookie (sg_consent) for 12 months.

   Categories: functional (always on), preferences, statistics, marketing.

   To load a script only after consent, mark it up like this and it will be activated when that
   category is allowed:

     <script type="text/plain" data-consent="statistics" src="https://example.com/analytics.js"></script>

   Other code can listen for changes:

     document.addEventListener('sg:consent', function (e) { console.log(e.detail); });
     window.SGConsent.has('statistics');   // true or false
     window.SGConsent.open();              // opens the preferences panel
*/
(function () {
  'use strict';

  var COOKIE = 'sg_consent';
  var VERSION = 1;
  var DAYS = 365;
  var CATS = [
    { key: 'functional', title: 'Functional', fixed: true,
      text: 'The technical storage or access is strictly necessary for the legitimate purpose of enabling the use of a specific service explicitly requested by the subscriber or user, or for the sole purpose of carrying out the transmission of a communication over an electronic communications network.' },
    { key: 'preferences', title: 'Preferences',
      text: 'The technical storage or access is necessary for the legitimate purpose of storing preferences that are not requested by the subscriber or user.' },
    { key: 'statistics', title: 'Statistics',
      text: 'The technical storage or access that is used exclusively for statistical purposes. The technical storage or access that is used exclusively for anonymous statistical purposes.' },
    { key: 'marketing', title: 'Marketing',
      text: 'The technical storage or access is required to create user profiles to send advertising, or to track the user on a website or across several websites for similar marketing purposes.' }
  ];

  function readCookie() {
    var m = document.cookie.match(/(?:^|; )sg_consent=([^;]*)/);
    if (!m) { return null; }
    try {
      var o = JSON.parse(decodeURIComponent(m[1]));
      return o && o.v === VERSION ? o : null;
    } catch (e) { return null; }
  }

  function writeCookie(choice) {
    var o = { v: VERSION, t: Date.now(), preferences: !!choice.preferences, statistics: !!choice.statistics, marketing: !!choice.marketing };
    document.cookie = COOKIE + '=' + encodeURIComponent(JSON.stringify(o)) +
      '; max-age=' + (DAYS * 86400) + '; path=/; SameSite=Lax' + (location.protocol === 'https:' ? '; Secure' : '');
    return o;
  }

  var state = readCookie();

  function has(cat) {
    if (cat === 'functional') { return true; }
    return !!(state && state[cat]);
  }

  /* Activate any scripts that were held back until consent */
  function activateScripts() {
    var held = document.querySelectorAll('script[type="text/plain"][data-consent]');
    Array.prototype.forEach.call(held, function (el) {
      if (!has(el.getAttribute('data-consent')) || el.getAttribute('data-consent-done')) { return; }
      var s = document.createElement('script');
      Array.prototype.forEach.call(el.attributes, function (a) {
        if (a.name !== 'type' && a.name !== 'data-consent') { s.setAttribute(a.name, a.value); }
      });
      s.text = el.text;
      el.setAttribute('data-consent-done', 'true');
      el.parentNode.insertBefore(s, el.nextSibling);
    });
  }

  function summary() {
    if (!state) { return 'You have not made a choice yet. Only functional storage is in use.'; }
    var on = [], off = [];
    CATS.forEach(function (c) {
      if (c.fixed) { return; }
      (state[c.key] ? on : off).push(c.title.toLowerCase());
    });
    var out = 'Your current choice: ';
    out += on.length ? 'allowed: ' + on.join(', ') + '. ' : 'no optional cookies allowed. ';
    if (on.length && off.length) { out += 'Not allowed: ' + off.join(', ') + '.'; }
    return out.trim();
  }

  function announce() {
    var el = document.getElementById('consent-status');
    if (el) { el.textContent = summary(); }
    document.dispatchEvent(new CustomEvent('sg:consent', { detail: { preferences: has('preferences'), statistics: has('statistics'), marketing: has('marketing') } }));
  }

  /* Remove Google Analytics cookies (_ga, _gid, _gat, _ga_<id>) when statistics are not allowed */
  function clearAnalyticsCookies() {
    var names = document.cookie.split('; ').map(function (c) { return c.split('=')[0]; })
      .filter(function (n) { return /^(_ga|_gid|_gat)/.test(n); });
    if (!names.length) { return; }
    var parts = location.hostname.split('.');
    var domains = [''];
    for (var i = 0; i < parts.length - 1; i++) { domains.push('; domain=.' + parts.slice(i).join('.')); }
    names.forEach(function (n) {
      domains.forEach(function (d) {
        document.cookie = n + '=; expires=Thu, 01 Jan 1970 00:00:00 GMT; path=/' + d;
      });
    });
  }

  function save(choice, message) {
    state = writeCookie(choice);
    if (!state.statistics) { clearAnalyticsCookies(); }
    activateScripts();
    removeBanner();
    announce();
    if (message) {
      var el = document.getElementById('consent-status');
      if (el) { el.textContent = message + ' ' + summary(); }
    }
  }

  /* ---------- Banner ---------- */
  var banner = null;
  function removeBanner() {
    if (banner && banner.parentNode) { banner.parentNode.removeChild(banner); }
    banner = null;
  }

  function showBanner() {
    if (banner) { return; }
    banner = document.createElement('section');
    banner.className = 'sg-banner';
    banner.setAttribute('aria-label', 'Cookie consent');
    banner.innerHTML =
      '<div class="sg-banner-text">' +
      '<p class="sg-banner-title">We use cookies</p>' +
      '<p>We use cookies to make this site work. With your permission we would also like to use cookies for preferences, statistics and marketing. You can change or withdraw your consent at any time. See our <a href="cookie-policy.html">cookie policy</a>.</p>' +
      '</div>' +
      '<div class="sg-banner-actions">' +
      '<button type="button" class="sg-btn sg-btn-accept" data-act="accept">Accept all</button>' +
      '<button type="button" class="sg-btn sg-btn-reject" data-act="reject">Reject all</button>' +
      '<button type="button" class="sg-btn sg-btn-manage" data-act="manage">Manage preferences</button>' +
      '</div>';
    banner.addEventListener('click', function (e) {
      var b = e.target.closest('button[data-act]');
      if (!b) { return; }
      var act = b.getAttribute('data-act');
      if (act === 'accept') { save({ preferences: true, statistics: true, marketing: true }); }
      if (act === 'reject') { save({ preferences: false, statistics: false, marketing: false }); }
      if (act === 'manage') { openPrefs(); }
    });
    document.body.appendChild(banner);
  }

  /* ---------- Preferences dialog ---------- */
  var dlg = null;
  function buildDialog() {
    dlg = document.createElement('dialog');
    dlg.className = 'sg-dialog';
    dlg.setAttribute('aria-labelledby', 'sg-dialog-title');
    var rows = CATS.map(function (c) {
      return '<div class="sg-cat">' +
        '<div class="sg-cat-head"><label for="sg-c-' + c.key + '">' + c.title + '</label>' +
        (c.fixed
          ? '<span class="sg-always">Always active</span><input type="checkbox" id="sg-c-' + c.key + '" checked disabled>'
          : '<input type="checkbox" id="sg-c-' + c.key + '" data-cat="' + c.key + '">') +
        '</div><p>' + c.text + '</p></div>';
    }).join('');
    dlg.innerHTML =
      '<form method="dialog" class="sg-dialog-body">' +
      '<h2 id="sg-dialog-title">Manage cookie consent</h2>' +
      '<p>To provide the best experiences, we use technologies like cookies to store and/or access device information. Consenting to these technologies will allow us to process data such as browsing behaviour or unique IDs on this site. Not consenting or withdrawing consent may adversely affect certain features and functions.</p>' +
      rows +
      '<div class="sg-dialog-actions">' +
      '<button type="button" class="sg-btn sg-btn-accept" data-act="save">Save preferences</button>' +
      '<button type="button" class="sg-btn sg-btn-reject" data-act="reject">Reject all</button>' +
      '<button type="button" class="sg-btn sg-btn-accept" data-act="accept">Accept all</button>' +
      '<button type="button" class="sg-btn sg-btn-manage" data-act="close">Close</button>' +
      '</div></form>';
    dlg.addEventListener('click', function (e) {
      var b = e.target.closest('button[data-act]');
      if (!b) { return; }
      var act = b.getAttribute('data-act');
      if (act === 'close') { dlg.close(); return; }
      if (act === 'accept') { save({ preferences: true, statistics: true, marketing: true }); }
      if (act === 'reject') { save({ preferences: false, statistics: false, marketing: false }); }
      if (act === 'save') {
        var c = {};
        Array.prototype.forEach.call(dlg.querySelectorAll('input[data-cat]'), function (i) { c[i.getAttribute('data-cat')] = i.checked; });
        save(c);
      }
      dlg.close();
    });
    document.body.appendChild(dlg);
  }

  function openPrefs() {
    if (!dlg) { buildDialog(); }
    Array.prototype.forEach.call(dlg.querySelectorAll('input[data-cat]'), function (i) {
      i.checked = has(i.getAttribute('data-cat'));
    });
    if (typeof dlg.showModal === 'function') { dlg.showModal(); } else { dlg.setAttribute('open', ''); }
  }

  /* ---------- Hooks on the page ---------- */
  document.addEventListener('click', function (e) {
    var open = e.target.closest('[data-consent-open]');
    if (open) { e.preventDefault(); openPrefs(); return; }
    var wd = e.target.closest('[data-consent-withdraw]');
    if (wd) {
      e.preventDefault();
      save({ preferences: false, statistics: false, marketing: false },
        'Your consent has been withdrawn. Optional scripts will not load on your next page view, and cookies already set by other services can be removed in your browser settings.');
    }
  });

  window.SGConsent = { has: has, open: openPrefs };

  function init() {
    if (!has('statistics')) { clearAnalyticsCookies(); }
    activateScripts();
    announce();
    if (!state) { showBanner(); }
  }
  if (document.readyState === 'loading') { document.addEventListener('DOMContentLoaded', init); } else { init(); }
})();
