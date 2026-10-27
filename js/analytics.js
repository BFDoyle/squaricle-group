/* Google Analytics 4 (gtag.js), property G-JKTF88N1V7.
   This file is only loaded after a visitor allows "Statistics" cookies (see js/consent.js), so nothing is sent to
   Google before consent. Every page includes it as:
     <script type="text/plain" data-consent="statistics" src="js/analytics.js"></script>
   It is the same as Google's standard tag, but loaded on consent. To use a different property, change the ID below. */
(function () {
  'use strict';

  var GA_ID = 'G-JKTF88N1V7';

  /* Honour a later withdrawal of consent during the same visit */
  document.addEventListener('sg:consent', function (e) {
    window['ga-disable-' + GA_ID] = !(e.detail && e.detail.statistics);
  });
  window['ga-disable-' + GA_ID] = false;

  var s = document.createElement('script');
  s.async = true;
  s.src = 'https://www.googletagmanager.com/gtag/js?id=' + encodeURIComponent(GA_ID);
  document.head.appendChild(s);

  window.dataLayer = window.dataLayer || [];
  function gtag() { window.dataLayer.push(arguments); }
  window.gtag = gtag;
  gtag('js', new Date());
  gtag('config', GA_ID);
})();
