<?php
$page = [
    'id' => 'careers',
    'css' => "careers",
    'nav' => 'careers',
];
require __DIR__ . '/inc/header.php';
?>

<section class="dk" style="background:#283d35;padding:40px 0 112px">
<div class="wrap">
<p style="margin:0 0 40px;font-size:16px;color:#ffffff"><a href="index.php" style="color:#ffffff;font-weight:600">Home</a> / careers</p>
<h1 style="margin:0;font-weight:800;font-size:clamp(64px,13vw,190px);line-height:0.9;letter-spacing:-0.045em;color:#ffffff">careers</h1>
<div class="two" style="display:grid;grid-template-columns:5fr 7fr;gap:64px;margin-top:64px;align-items:start">
<p style="margin:0;font-weight:800;font-size:clamp(28px,3vw,44px);line-height:1.1;letter-spacing:-0.025em;color:#ffffff">Why join our family?</p>
<div style="display:flex;flex-direction:column;gap:20px">
<p style="margin:0;font-size:20px;line-height:1.65;color:#ffffff">Squaricle Group is all about our people. We want a team of talented and empowered people that can help us deliver our vision.</p>
<p style="margin:0;font-size:20px;line-height:1.65;color:#ffffff">Our team are passionate about working at Squaricle Group. We love bringing in new talent, to challenge our ways of working, bring new skills and perspectives and add real value to our organisation.</p>
</div>
</div>
</div>
</section>

<section style="padding:128px 0">
<div class="wrap two" style="display:grid;grid-template-columns:5fr 7fr;gap:72px;align-items:start">
<h2 class="h2">The benefits of working at the Squaricle Group</h2>
<div style="display:flex;flex-direction:column;gap:22px">
<p class="p">To do this we want to attract, engage and develop a team of diverse, creative and empowered individuals that reflects the whole UK, our clients and our learners.</p>
<p class="p">We recognise that we currently don’t represent our Black and Asian communities and so we’d particularly welcome applications from people from these groups. We’re based in a variety of different locations across the country, yet we work exceptionally well together, so wherever you’re based in the UK, we’d love to hear from you.</p>
<p class="p">As well as being part of a creative and innovative team, we are also pleased to offer all of these employee benefits if you join our team.</p>
</div>
</div>
</section>

<section class="three" style="display:grid;grid-template-columns:repeat(3,minmax(0,1fr))">
<div class="ben" style="background:#5fad91"><p>Flexible working hours</p></div>
<div class="ben" style="background:#cbbba0"><p>25 days starting holiday (FT)</p></div>
<div class="ben" style="background:#efa897"><p>Social events</p></div>
<div class="ben" style="background:#efa897"><p>Contributory pension</p></div>
<div class="ben" style="background:#5fad91"><p>Cycle to work scheme</p></div>
<div class="ben" style="background:#cbbba0"><p>Fun working environment</p></div>
</section>

<section style="padding:128px 0">
<div class="wrap two" style="display:grid;grid-template-columns:5fr 7fr;gap:72px;align-items:start">
<div>
<h2 class="h2">Current opportunities at Squaricle Group</h2>
</div>
<div style="display:flex;flex-direction:column;gap:22px">
<p class="p">We’re really excited that you’re interested in joining our work family! Any specific vacancies we have at Squaricle Group are detailed below.</p>
<div style="background:#eef2ef;padding:32px 36px"><p class="p">[Current vacancies to be listed here. The live careers page lists none at the moment.]</p></div>
<p class="p">If you’re a trainer or actor interested in freelance work with us, please send a copy of your CV and/or Showreel to <a href="mailto:hello@squariclegroup.com">hello@squariclegroup.com</a>, letting us know why you want to work with us. We can’t wait to hear from you! We would particularly welcome applications from under-represented groups.</p>
</div>
</div>
</section>

<section class="dk" style="background:#283d35;padding:128px 0">
<div class="wrap">
<h2 class="h2" style="color:#ffffff">Where you will find us!</h2>
<p style="margin:16px 0 0;max-width:760px;font-size:18px;line-height:1.65;color:#ffffff">Our team are based in various locations around England. So wherever you are based, we are interested in hearing from you and joining us on the map below!</p>
<img width="1703" height="1457" src="assets/img/team-map-sept-26.svg" alt="Map showing where the Squaricle Group family are based, in the United Kingdom and the Philippines" style="display:block;width:100%;max-width:1100px;height:auto;margin:56px auto 0">
</div>
</section>

<section id="apply" style="background:#5fad91;padding:128px 0">
<div class="wrap two" style="display:grid;grid-template-columns:5fr 7fr;gap:80px;align-items:start">
<div>
<h2 style="margin:0;font-weight:800;font-size:clamp(36px,4vw,60px);line-height:1.02;letter-spacing:-0.03em;color:#1b2a24">Apply to join our team</h2>
<p style="margin:24px 0 0;font-size:20px;line-height:1.6;color:#1b2a24;max-width:460px">We would love to hear from you.</p>
</div>
<form data-form id="apply-form" method="post" action="submit.php" enctype="multipart/form-data" style="display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:20px">
<?= form_notice('careers') ?>
<?= form_fields('careers') ?>
<div><label class="lbl" for="cfn">First name</label><input class="fld" id="cfn" type="text" autocomplete="given-name" name="first_name" required maxlength="100"></div>
<div><label class="lbl" for="cln">Last name</label><input class="fld" id="cln" type="text" autocomplete="family-name" name="last_name" required maxlength="100"></div>
<div><label class="lbl" for="cem">Email</label><input class="fld" id="cem" type="email" autocomplete="email" name="email" required maxlength="200"></div>
<div><label class="lbl" for="cph">Phone</label><input class="fld" id="cph" type="tel" autocomplete="tel" name="phone" maxlength="40"></div>
<div style="grid-column:1 / span 2"><label class="lbl" for="ccv">Upload your CV as a pdf or word document only</label><input class="fld" id="ccv" type="file" accept=".pdf,.doc,.docx" style="padding:12px 16px" name="cv" required></div>
<div style="grid-column:1 / span 2;display:flex;gap:12px;align-items:flex-start"><input id="cco" type="checkbox" style="width:24px;height:24px;margin:0;flex:none" name="consent" required><label for="cco" style="font-size:16px;line-height:1.5;color:#1b2a24">By submitting this form you consent to us getting in touch in response to your application.</label></div>
<div style="grid-column:1 / span 2"><button type="submit" class="btn btn-i">Send your application now</button></div>
</form>
</div>
</section>

<?php require __DIR__ . '/inc/footer.php'; ?>
