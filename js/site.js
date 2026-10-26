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

  /* Vimeo pop-up. Any link with data-vimeo="<id>" opens its video in a pop-up.
     The player is only loaded when someone clicks, and is removed again when the pop-up closes.
     Without JavaScript the link simply opens the video on Vimeo. */
  var triggers = document.querySelectorAll('[data-vimeo]');
  if (triggers.length && typeof HTMLDialogElement === 'function') {
    var dlg = document.createElement('dialog');
    dlg.className = 'sg-video';
    dlg.innerHTML =
      '<div class="sg-video-bar"><p class="sg-video-title"></p>' +
      '<button type="button" class="sg-video-close" aria-label="Close video">Close</button></div>' +
      '<div class="sg-video-frame"></div>' +
      '<p class="sg-video-note">This video is hosted on Vimeo.</p>';
    document.body.appendChild(dlg);
    var frame = dlg.querySelector('.sg-video-frame');
    var heading = dlg.querySelector('.sg-video-title');

    document.addEventListener('click', function (e) {
      var t = e.target.closest('[data-vimeo]');
      if (!t || e.metaKey || e.ctrlKey || e.shiftKey || e.button === 1) { return; }
      e.preventDefault();
      var title = (t.getAttribute('data-title') || 'Video').replace(/&amp;/g, '&');
      var f = document.createElement('iframe');
      f.src = 'https://player.vimeo.com/video/' + encodeURIComponent(t.getAttribute('data-vimeo')) +
        '?autoplay=1&dnt=1&title=0&byline=0&portrait=0';
      f.title = title;
      f.allow = 'autoplay; fullscreen; picture-in-picture';
      f.setAttribute('allowfullscreen', '');
      frame.innerHTML = '';
      frame.appendChild(f);
      heading.textContent = title;
      dlg.setAttribute('aria-label', title);
      dlg.showModal();
    });
    dlg.querySelector('.sg-video-close').addEventListener('click', function () { dlg.close(); });
    dlg.addEventListener('click', function (e) { if (e.target === dlg) { dlg.close(); } });
    dlg.addEventListener('close', function () { frame.innerHTML = ''; });
  }

  /* Forms.
     Every form posts to submit.php, which checks it, sends it to the webhook and answers with JSON.
     On success the visitor goes to the thank-you page; on a problem the message shows above the button
     and what they typed is kept. Without JavaScript the form is a normal post and submit.php redirects. */
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
      if (!window.fetch || !window.FormData) { return; }
      e.preventDefault();
      if (!form.reportValidity()) { return; }
      var btn = form.querySelector('button[type="submit"]');
      if (btn) { btn.disabled = true; }
      show('Sending…', false);
      fetch(form.getAttribute('action'), { method: 'POST', body: new FormData(form), headers: { Accept: 'application/json' } })
        .then(function (r) {
          return r.json().then(function (j) { return j; }, function () { return { ok: false }; });
        })
        .then(function (j) {
          if (j.ok && j.redirect) {
            window.location.href = j.redirect;
            return;
          }
          show(j.error || 'Sorry, something went wrong. Please email hello@squariclegroup.com instead.', true);
          if (btn) { btn.disabled = false; }
        })
        .catch(function () {
          show('Sorry, something went wrong. Please email hello@squariclegroup.com instead.', true);
          if (btn) { btn.disabled = false; }
        });
    });
  });
})();
