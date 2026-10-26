/* Google Analytics (gtag.js).
   This file is only loaded after a visitor allows "Statistics" cookies (see js/consent.js).
   Every page includes it as:
     <script type="text/plain" data-consent="statistics" src="js/analytics.js"></script>

   NOTE: UA-... IDs are Universal Analytics, which Google stopped processing in 2023.
   For a current Google Analytics 4 property, replace the ID below with its G-XXXXXXXXXX
   measurement ID. Nothing else needs to change. */
(function () {
  'use strict';

  var GA_ID = 'UA-163660789-1';

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
