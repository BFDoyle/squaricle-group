<?php
$page = [
    'id' => 'group-policies',
    'css' => "group-policies",
];
require __DIR__ . '/inc/header.php';
?>

<section class="dk" style="background:#283d35;padding:40px 0 112px">
<div class="wrap">
<p style="margin:0 0 40px;font-size:16px;color:#ffffff"><a href="index.php" style="color:#ffffff;font-weight:600">Home</a> / group policies</p>
<h1 style="margin:0;font-weight:800;font-size:clamp(56px,11vw,160px);line-height:0.92;letter-spacing:-0.045em;color:#ffffff">group policies</h1>
</div>
</section>

<section style="padding:128px 0">
<div class="wrap two" style="display:grid;grid-template-columns:4fr 8fr;gap:64px;align-items:start">
<h2 class="h2">Our policies</h2>
<div style="display:flex;flex-direction:column;gap:12px">
<?php foreach (data('policies') as $p): ?>
<a class="row" href="<?= e($p['href']) ?>"<?= $p['pdf'] ? ' target="_blank" rel="noopener"' : '' ?> style="text-decoration:none"><p class="t"><?= e($p['title']) ?></p><span class="go"><?= $p['pdf'] ? 'View policy (PDF)<span class="vh"> opens in a new tab</span>' : 'Read online' ?></span></a>
<?php endforeach; ?>
</div>
</div>
</section>

<section style="background:#5fad91;padding:112px 0">
<div class="wrap two" style="display:grid;grid-template-columns:7fr 5fr;gap:64px;align-items:end">
<div>
<h2 style="margin:0;font-weight:800;font-size:clamp(36px,4vw,60px);line-height:1.02;letter-spacing:-0.03em;color:#1b2a24">talk to Squaricle Group</h2>
<p style="margin:24px 0 0;font-size:20px;line-height:1.6;color:#1b2a24;max-width:560px">To find out how we can inspire your leaders and managers to achieve their potential for themselves and for your business, please get in touch.</p>
</div>
<div><a class="btn btn-i" href="index.php#contact">Get in touch</a></div>
</div>
</section>

<?php require __DIR__ . '/inc/footer.php'; ?>
