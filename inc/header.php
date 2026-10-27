<?php
/* Top of every page. Each page sets $page before including this file:
   title, description, css (the page's own stylesheet name),
   nav (which menu item is current), sub (the current sub page), overlay (true on the home page: transparent header over the hero). */
if (!isset($page)) {
    http_response_code(403);
    exit;
}
require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/seo.php';
if (!empty($page['id'])) {
    $entry = page_by_id($page['id']);
    if ($entry) {
        $page = $entry + $page;
    }
}
$site = data('site');
$navKey = $page['nav'] ?? '';
$subHref = $page['sub'] ?? '';
require __DIR__ . '/head.php';
?>
<body>
<div style="position:relative;width:100%;background:#ffffff;font-family:'Montserrat',system-ui,sans-serif;color:#1b2a24;overflow:hidden">

<a class="skip" href="#top">Skip to content</a>
<?php if (!empty($page['overlay'])): ?>
<header class="dk" style="position:absolute;top:0;left:0;right:0;z-index:3;background:transparent">
<?php else: ?>
<header class="dk" style="background:#283d35">
<?php endif; ?>
<div class="wrap" style="display:flex;align-items:center;justify-content:space-between;gap:24px;height:104px">
<a href="<?= !empty($page['overlay']) ? '#top' : 'index.php' ?>" aria-label="<?= e($site['name']) ?> home" style="display:block;line-height:0"><img src="assets/img/sg-symbol.png" alt="<?= e($site['name']) ?>" style="height:64px;width:64px;display:block"></a>
<nav class="nav" id="site-nav" aria-label="Main" style="display:flex;align-items:center;gap:36px">
<?php foreach ($site['nav'] as $item):
    $isCurrent = $item['key'] === $navKey;
    if (empty($item['children'])):
        if ($item['key'] === 'careers' && $isCurrent): ?>
<a href="<?= e($item['href']) ?>" aria-current="page" style="color:#efa897"><?= e($item['label']) ?></a>
<?php   else: ?>
<a href="<?= e($item['href']) ?>"><?= e($item['label']) ?></a>
<?php   endif;
    else: ?>
<div class="has-sub"><a href="<?= e($item['href']) ?>"<?= $isCurrent ? ' aria-current="true" style="color:#efa897"' : '' ?>><?= e($item['label']) ?></a><ul class="sub" aria-label="<?= e($item['label']) ?>"><?php foreach ($item['children'] as $child): ?><li><a href="<?= e($child['href']) ?>"<?= !empty($child['external']) ? ' target="_blank" rel="noopener"' : '' ?><?= ($isCurrent && $child['href'] === $subHref) ? ' aria-current="page"' : '' ?>><?= e($child['label']) ?></a></li><?php endforeach; ?></ul></div>
<?php endif;
endforeach; ?>
<a href="<?= e($site['cta']['href']) ?>" class="btn btn-s" style="min-height:44px;padding:0 22px;color:#1b2a24"><?= e($site['cta']['label']) ?></a>
</nav>
<button type="button" class="menu-btn" aria-expanded="false" aria-controls="site-nav" aria-label="Open menu"><span></span><span></span><span></span></button>
</div>
</header>

<main id="top">
