/* Squaricle Group – site behaviour. No dependencies. */
(function () {
  'use strict';

  /* Mobile menu */
  var menuBtn = document.querySelector('.menu-btn');
  var nav = document.getElementById('site-nav');
  if (menuBtn && nav) {
    menuBtn.addEventListener('click', function () {
      var open = nav.classList.toggle('open');
      menuBtn.setAttribute('aria-expanded', open ? 'true' : 'false');
      menuBtn.setAttribute('aria-label', open ? 'Close menu' : 'Open menu');
    });
    nav.addEventListener('click', function (e) {
      if (e.target.closest('a')) {
        nav.classList.remove('open');
        menuBtn.setAttribute('aria-expanded', 'false');
        menuBtn.setAttribute('aria-label', 'Open menu');
      }
    });
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && nav.classList.contains('open')) {
        nav.classList.remove('open');
        menuBtn.setAttribute('aria-expanded', 'false');
        menuBtn.setAttribute('aria-label', 'Open menu');
        menuBtn.focus();
      }
    });
  }

  /* Hero video: pause / play, and respect reduced motion */
  var video = document.getElementById('hero-video');
  var toggle = document.getElementById('video-toggle');
  if (video && toggle) {
    var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    var setState = function (playing) {
      if (playing) {
        video.hidden = false;
        var p = video.play();
        if (p && p.catch) { p.catch(function () {}); }
      } else {
        video.pause();
        video.hidden = true;
      }
      toggle.setAttribute('aria-pressed', playing ? 'false' : 'true');
      toggle.textContent = playing ? 'Pause video' : 'Play video';
    };
    if (reduce) { setState(false); }
    toggle.addEventListener('click', function () { setState(video.hidden); });
  }

  /* Project filter */
  var chips = document.querySelectorAll('[data-filter]');
  var cards = document.querySelectorAll('[data-category]');
  if (chips.length && cards.length) {
    chips.forEach(function (chip) {
      chip.addEventListener('click', function () {
        var f = chip.getAttribute('data-filter');
        chips.forEach(function (c) { c.setAttribute('aria-pressed', c === chip ? 'true' : 'false'); });
        cards.forEach(function (card) {
          card.hidden = !(f === 'all' || card.getAttribute('data-category') === f);
        });
      });
    });
  }

  /* Forms.
     Set data-endpoint="https://..." on a <form> (for example a Formspree or Netlify endpoint)
     and it posts there with fetch. Without an endpoint it shows a notice and sends nothing. */
  document.querySelectorAll('form[data-form]').forEach(function (form) {
    var status = document.createElement('p');
    status.className = 'form-status';
    status.setAttribute('role', 'status');
    status.hidden = true;
    form.appendChild(status);

    var show = function (msg, isError) {
      status.textContent = msg;
      status.className = 'form-status' + (isError ? ' error' : '');
      status.hidden = false;
    };

    form.addEventListener('submit', function (e) {
      e.preventDefault();
      var consent = form.querySelector('input[type="checkbox"]');
      if (consent && !consent.checked) {
        show('Please tick the consent box so we can get in touch.', true);
        consent.focus();
        return;
      }
      var endpoint = form.getAttribute('data-endpoint');
      if (!endpoint) {
        show('This form is not connected yet, so nothing has been sent. See the README to connect a form service.', true);
        return;
      }
      var btn = form.querySelector('button[type="submit"]');
      if (btn) { btn.disabled = true; }
      fetch(endpoint, { method: 'POST', body: new FormData(form), headers: { Accept: 'application/json' } })
        .then(function (r) {
          if (!r.ok) { throw new Error('Request failed'); }
          form.reset();
          show('Thank you. We have received your message and will be in touch.', false);
        })
        .catch(function () {
          show('Sorry, something went wrong. Please email hello@squariclegroup.com instead.', true);
        })
        .then(function () { if (btn) { btn.disabled = false; } });
    });
  });
})();
