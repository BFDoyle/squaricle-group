<?php if (!isset($site)) { http_response_code(403); exit; } ?>
</main>

<footer class="dk" style="background:#283d35;padding:96px 0 48px;color:#ffffff">
<div class="wrap">
<div class="foot-brand">
<img src="assets/img/sg-footer-logo.png" alt="" width="370" height="480" loading="lazy">
<p style="margin:0;font-weight:800;font-size:clamp(52px,9.4vw,136px);line-height:0.95;letter-spacing:-0.045em;color:#ffffff"><?= e(strtolower($site['name'])) ?></p>
</div>
<div class="three" style="display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:48px;margin-top:80px">
<?php foreach ($site['footer']['columns'] as $col): ?>
<div class="fl" style="display:flex;flex-direction:column">
<p style="margin:0 0 8px;font-size:20px;font-weight:700;color:#efa897"><?= e($col['title']) ?></p>
<?php foreach ($col['links'] as $l): ?><a href="<?= e($l['href']) ?>"<?= !empty($l['consent_open']) ? ' data-consent-open' : '' ?>><?= e($l['label']) ?></a><?php endforeach; ?>

</div>
<?php endforeach; ?>
<div>
<p style="margin:0 0 8px;font-size:20px;font-weight:700;color:#efa897"><?= e($site['footer']['registered_office']['title']) ?></p>
<p style="margin:0;font-size:16px;line-height:1.7;color:#ffffff"><?= implode('<br>', array_map('e', $site['footer']['registered_office']['lines'])) ?></p>
</div>
</div>
<p style="margin:72px 0 0;font-size:15px;color:#ffffff"><?= e($site['footer']['copyright']) ?></p>
<p style="margin:16px 0 0;max-width:900px;font-size:12px;line-height:1.6;color:#ffffff"><?= e($site['footer']['notice']) ?></p>
</div>
</footer>

</div>
<script type="text/plain" data-consent="statistics" src="js/analytics.js"></script>
<script src="js/consent.js" defer></script>
<script src="js/site.js" defer></script>
</body>
</html>
