<?php if (!isset($site)) { http_response_code(403); exit; } ?><!doctype html>
<html lang="en-GB">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($page['title']) ?></title>
<meta name="description" content="<?= e($page['description']) ?>">
<meta name="theme-color" content="#283d35">
<?php if (!empty($page['noindex'])): ?>
<meta name="robots" content="noindex, follow">
<?php else:
    $canonical = page_url($page['file']);
    $ogImage = site_url(data('organization')['image']);
    $ogAlt = 'Squaricle Group: training that makes great people'; ?>
<meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
<link rel="canonical" href="<?= e($canonical) ?>">
<link rel="alternate" hreflang="en-GB" href="<?= e($canonical) ?>">
<link rel="alternate" hreflang="x-default" href="<?= e($canonical) ?>">
<meta property="og:type" content="website">
<meta property="og:site_name" content="<?= e($site['name']) ?>">
<meta property="og:locale" content="en_GB">
<meta property="og:url" content="<?= e($canonical) ?>">
<meta property="og:title" content="<?= e($page['title']) ?>">
<meta property="og:description" content="<?= e($page['description']) ?>">
<meta property="og:image" content="<?= e($ogImage) ?>">
<meta property="og:image:secure_url" content="<?= e($ogImage) ?>">
<meta property="og:image:type" content="image/jpeg">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:image:alt" content="<?= e($ogAlt) ?>">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="<?= e($page['title']) ?>">
<meta name="twitter:description" content="<?= e($page['description']) ?>">
<meta name="twitter:image" content="<?= e($ogImage) ?>">
<meta name="twitter:image:alt" content="<?= e($ogAlt) ?>">
<?php endif; ?>
<?php if (config('verify_google')): ?>
<meta name="google-site-verification" content="<?= e(config('verify_google')) ?>">
<?php endif; ?>
<?php if (config('verify_bing')): ?>
<meta name="msvalidate.01" content="<?= e(config('verify_bing')) ?>">
<?php endif; ?>
<link rel="icon" href="assets/img/favicon-32.png" sizes="32x32" type="image/png">
<link rel="apple-touch-icon" href="assets/img/apple-touch-icon.png">
<link rel="preload" href="assets/fonts/montserrat-latin.woff2" as="font" type="font/woff2" crossorigin>
<link rel="stylesheet" href="css/site.css">
<link rel="stylesheet" href="css/consent.css">
<link rel="stylesheet" href="css/<?= e($page['css']) ?>.css">
<?php if (empty($page['noindex'])): ?>
<?= json_ld(schema_graph($page)) ?>

<?php endif; ?>
</head>
