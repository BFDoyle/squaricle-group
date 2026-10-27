<?php
$page = [
    'id' => 'emerging-u',
    'css' => "emerging-u",
    'nav' => 'brands',
    'sub' => 'emerging-u.php',
];
require __DIR__ . '/inc/header.php';
?>

<section class="dk" style="background:#1a1030;padding:40px 0 120px">
<div class="wrap">
<p style="margin:0 0 40px;font-size:16px;color:#f4efe7"><a href="index.php#brands" style="color:#f4efe7;font-weight:600">Businesses and partners</a> / emerging-u</p>
<h1 style="margin:0;font-weight:800;font-size:clamp(64px,13vw,190px);line-height:0.9;letter-spacing:-0.045em;color:#f4efe7">emerging-u</h1>
<p style="margin:32px 0 0;font-weight:700;font-size:clamp(20px,2vw,28px);color:#e7a6d2">Leadership Learning &amp; Development Agency</p>
<div class="two" style="display:grid;grid-template-columns:5fr 7fr;gap:64px;margin-top:64px;align-items:start">
<div>
<p style="margin:0;font-weight:800;font-size:clamp(28px,3vw,44px);line-height:1.1;letter-spacing:-0.025em;color:#f4efe7">We create the conditions for change.</p>
<div style="margin-top:32px"><a class="btn" style="background:#f4efe7;color:#1a1030" href="https://www.emerging-u.com" target="_blank" rel="noopener">Visit emerging-u.com</a></div>
</div>
<div style="display:flex;flex-direction:column;gap:20px">
<p style="margin:0;font-size:20px;line-height:1.65;color:#f4efe7">We believe in a world where people are able to connect through shared purpose and meaning.</p>
<p style="margin:0;font-size:20px;line-height:1.65;color:#f4efe7">A world where individuals and teams are empowered to be their best and flourish in any situation.</p>
<p style="margin:0;font-size:20px;line-height:1.65;color:#f4efe7">A world where human connection is the key energy source for businesses and individuals to thrive.</p>
</div>
</div>
</div>
</section>

<section class="pillar" style="background:#2a1b4a">
<img class="pic" src="assets/img/emerging-u-leadership.jpg" alt="A leader laughing">
<div class="pt dk">
<h2 style="margin:0;font-weight:800;font-size:clamp(40px,5vw,72px);line-height:1;letter-spacing:-0.035em;color:#ffffff">leadership</h2>
<blockquote style="color:#ffffff">“leadership is about being able to listen to the whole better than anyone else can”<cite style="color:#ffffff">Otto Scharmer</cite></blockquote>
<p style="margin:0;font-size:18px;line-height:1.65;color:#ffffff">How leaders show up for the people they serve directly impacts how those people serve their leaders, each other and your customers.</p>
<p style="margin:0;font-size:18px;line-height:1.65;color:#ffffff">We create the conditions for leaders to release their best selves, inspire their people and shape the future.</p>
<div><a class="btn btn-s" href="#brochure">Learn more</a></div>
</div>
</section>

<section class="pillar rev" style="background:#f4efe7">
<img class="pic" src="assets/img/emerging-u-teams.jpg" alt="A large team raising a timber barn frame together">
<div class="pt">
<h2 style="margin:0;font-weight:800;font-size:clamp(40px,5vw,72px);line-height:1;letter-spacing:-0.035em;color:#1b2a24">teams</h2>
<blockquote>“culture eats strategy for breakfast”<cite>Peter Drucker</cite></blockquote>
<p class="p">High performing teams hold a shared vision, understand the pathways to achieving that vision and are deeply committed to the realisation of that vision.</p>
<p class="p">We create the conditions for the teams to connect deeply, collaborate effectively and innovate collectively.</p>
<div><a class="btn btn-i" href="#brochure">Learn more</a></div>
</div>
</section>

<section class="pillar" style="background:#d9cfea">
<img class="pic" src="assets/img/emerging-u-cx.jpg" alt="A confident colleague standing in a laboratory">
<div class="pt">
<h2 style="margin:0;font-weight:800;font-size:clamp(40px,5vw,72px);line-height:1;letter-spacing:-0.035em;color:#1b2a24">CX</h2>
<blockquote>“people will never forget how you made them feel”<cite>Maya Angelou</cite></blockquote>
<p class="p">In business, human connection is the critical element of success. How we engage with our customers determines our tomorrows.</p>
<p class="p">We create the condition for your people to connect to your brand’s values, embody them, own them and deliver exceptional customer experience every time.</p>
<div><a class="btn btn-i" href="#brochure">Learn more</a></div>
</div>
</section>

<section id="brochure" style="padding:128px 0;background:#eef2ef">
<div class="wrap two" style="display:grid;grid-template-columns:5fr 7fr;gap:80px;align-items:start">
<div>
<h2 class="h2">Get a copy of our emerging-u Brochure.</h2>
<p class="p" style="margin-top:20px">We value your privacy and your contact details are processed in accordance with our <a href="privacy-policy.php" style="color:#1b2a24;font-weight:700">Data protection policy</a>.</p>
</div>
<form data-form id="brochure-form" method="post" action="submit.php" style="display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:20px">
<?= form_notice('emerging-u-brochure') ?>
<?= form_fields('emerging-u-brochure') ?>
<div><label class="lbl" for="en">Name</label><input class="fld" id="en" type="text" autocomplete="name" name="name" required maxlength="200"></div>
<div><label class="lbl" for="ee">Email</label><input class="fld" id="ee" type="email" autocomplete="email" name="email" required maxlength="200"></div>
<div style="grid-column:1 / span 2;display:flex;gap:12px;align-items:flex-start"><input id="ec" type="checkbox" style="width:24px;height:24px;margin:0;flex:none" name="consent" required><label for="ec" style="font-size:16px;line-height:1.5;color:#1b2a24">I consent to my submitted data being collected and stored.</label></div>
<div style="grid-column:1 / span 2"><button type="submit" class="btn btn-i">Download our Corporate Brochure</button></div>
</form>
</div>
</section>

<?php require __DIR__ . '/inc/footer.php'; ?>
