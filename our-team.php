<?php
$page = [
    'id' => 'our-team',
    'css' => "our-team",
    'nav' => 'people',
    'sub' => 'our-team.php',
];
require __DIR__ . '/inc/header.php';
?>

<section class="dk" style="background:#283d35;padding:40px 0 112px">
<div class="wrap">
<p style="margin:0 0 40px;font-size:16px;color:#ffffff"><a href="index.php" style="color:#ffffff;font-weight:600">Our people</a> / our team</p>
<h1 style="margin:0;font-weight:800;font-size:clamp(64px,13vw,190px);line-height:0.9;letter-spacing:-0.045em;color:#ffffff">our team</h1>
<div class="two" style="display:grid;grid-template-columns:1fr 1fr;gap:64px;margin-top:64px">
<p style="margin:0;font-size:22px;line-height:1.55;color:#ffffff">Our team are a group of hugely talented, passionate and dedicated individuals, some of whom have worked together for years, others who are more recent additions to the Squaricle Group family.</p>
<div style="display:flex;flex-direction:column;gap:20px">
<p style="margin:0;font-size:18px;line-height:1.65;color:#ffffff">Some of our people work across the Group, others work specifically for one of our fantastic brands.</p>
<p style="margin:0;font-size:18px;line-height:1.65;color:#ffffff">All of us work collaboratively, the embodiment of our One Team value, to deliver exceptional results for our clients.</p>
</div>
</div>
</div>
</section>

<section id="leadership" class="lb" style="background:#efa897;padding-bottom:32px">
<div class="wrap" style="padding-top:128px">
<h2 class="h2" style="color:#ffffff">leadership team</h2>
<?php foreach (array_filter(data('team'), fn($m) => $m['group'] === 'leadership') as $m): ?>
<article class="person<?= $m['rev'] ? ' rev' : '' ?>"<?= $m['article_style'] ? ' style="' . e($m['article_style']) . '"' : '' ?>>
<?= person_img($m) ?>

<div style="display:flex;flex-direction:column;gap:20px">
<h3 style="margin:0;font-weight:800;font-size:clamp(36px,4vw,56px);line-height:1;letter-spacing:-0.03em;color:#ffffff"><?= e($m['name']) ?></h3>
<p style="margin:0;font-weight:700;font-size:20px;line-height:1.3;color:#efa897"><?= e($m['role']) ?></p>
<?php foreach ($m['paras'] as $para): ?>
<p style="margin:0;font-size:18px;line-height:1.65;color:#ffffff"><?= e($para) ?></p>
<?php endforeach; ?>
</div>
</article>
<?php endforeach; ?>
</div>
</section>

<?php foreach (array_filter(data('team'), fn($m) => $m['group'] === 'team') as $m): ?>
<section style="background:<?= e($m['bg']) ?>">
<div class="wrap">
<article class="person<?= $m['rev'] ? ' rev' : '' ?>">
<?= person_img($m) ?>

<div style="display:flex;flex-direction:column;gap:20px">
<h2 style="margin:0;font-weight:800;font-size:clamp(36px,4vw,56px);line-height:1;letter-spacing:-0.03em;color:<?= e($m['name_color']) ?>"><?= e($m['name']) ?></h2>
<h3 class="h3"><?= e($m['role']) ?></h3>
<?php foreach ($m['paras'] as $para): ?>
<p class="p"><?= e($para) ?></p>
<?php endforeach; ?>
</div>
</article>
</div>
</section>
<?php endforeach; ?>

<section id="careers" class="dk" style="background:#283d35;padding:128px 0">
<div class="wrap two" style="display:grid;grid-template-columns:7fr 5fr;gap:64px;align-items:end">
<div>
<h2 class="h2" style="color:#ffffff">Could this be you?</h2>
<p style="margin:24px 0 0;font-size:22px;line-height:1.55;color:#ffffff;max-width:640px">Future roles. Check out our latest vacancies and perhaps one day you could be featured here!</p>
</div>
<div><a class="btn btn-s" href="careers.php">See our latest vacancies</a></div>
</div>
</section>

<section id="contact" style="background:#5fad91;padding:128px 0">
<div class="wrap two" style="display:grid;grid-template-columns:5fr 7fr;gap:80px;align-items:start">
<div>
<h2 style="margin:0;font-weight:800;font-size:clamp(36px,4vw,60px);line-height:1.02;letter-spacing:-0.03em;color:#1b2a24">talk to Squaricle Group</h2>
<p style="margin:24px 0 0;font-size:20px;line-height:1.6;color:#1b2a24;max-width:460px">To find out how we might support your business, please get in touch.</p>
</div>
<form data-form id="callback-form" method="post" action="submit.php" style="display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:20px">
<?= form_notice('callback') ?>
<?= form_fields('callback') ?>
<div><label class="lbl" for="ofn">First name</label><input class="fld" id="ofn" type="text" autocomplete="given-name" name="first_name" required maxlength="100"></div>
<div><label class="lbl" for="oln">Last name</label><input class="fld" id="oln" type="text" autocomplete="family-name" name="last_name" required maxlength="100"></div>
<div><label class="lbl" for="oem">Email</label><input class="fld" id="oem" type="email" autocomplete="email" name="email" required maxlength="200"></div>
<div><label class="lbl" for="oph">Phone</label><input class="fld" id="oph" type="tel" autocomplete="tel" name="phone" required maxlength="40"></div>
<p style="grid-column:1 / span 2;margin:0;font-size:16px;line-height:1.5;color:#1b2a24">By submitting this form you consent to us getting in touch in response to your enquiry.</p>
<div style="grid-column:1 / span 2"><button type="submit" class="btn btn-i">Call me back</button></div>
</form>
</div>
</section>

<?php require __DIR__ . '/inc/footer.php'; ?>
