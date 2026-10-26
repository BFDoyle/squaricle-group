<?php
require __DIR__ . '/inc/bootstrap.php';
$forms = data('forms');
$thanks = $forms[$_GET['form'] ?? '']['thanks'] ?? [
    'heading' => 'thank you',
    'lead' => 'Thanks for getting in touch.',
    'body' => 'A member of the Squaricle Group team will be in contact shortly.',
];
$page = [
    'title' => 'Thank you | Squaricle Group',
    'description' => 'Thank you for getting in touch with Squaricle Group.',
    'css' => 'thank-you',
    'noindex' => true,
];
require __DIR__ . '/inc/header.php';
?>

<section class="dk" style="background:#283d35;padding:40px 0 112px">
<div class="wrap">
<p style="margin:0 0 40px;font-size:16px;color:#ffffff"><a href="index.php" style="color:#ffffff;font-weight:600">Home</a> / thank you</p>
<h1 style="margin:0;font-weight:800;font-size:clamp(56px,11vw,160px);line-height:0.92;letter-spacing:-0.045em;color:#ffffff"><?= e($thanks['heading']) ?></h1>
</div>
</section>

<section style="background:#5fad91;padding:112px 0">
<div class="wrap two" style="display:grid;grid-template-columns:7fr 5fr;gap:64px;align-items:end">
<div>
<h2 style="margin:0;font-weight:800;font-size:clamp(30px,3.4vw,52px);line-height:1.05;letter-spacing:-0.03em;color:#1b2a24"><?= e($thanks['lead']) ?></h2>
<p style="margin:24px 0 0;font-size:20px;line-height:1.6;color:#1b2a24;max-width:560px"><?= e($thanks['body']) ?></p>
</div>
<div><a class="btn btn-i" href="index.php">Back to the home page</a></div>
</div>
</section>

<?php require __DIR__ . '/inc/footer.php'; ?>
