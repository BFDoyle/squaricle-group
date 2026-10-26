<?php
declare(strict_types=1);

define('SG_ROOT', dirname(__DIR__));

/* Read a file from data/ (data/site.json is data('site')). */
function data(string $name): array
{
    static $cache = [];
    if (!isset($cache[$name])) {
        $cache[$name] = json_decode(
            (string) file_get_contents(SG_ROOT . '/data/' . $name . '.json'),
            true,
            512,
            JSON_THROW_ON_ERROR
        );
    }
    return $cache[$name];
}

function config(string $key)
{
    static $cfg = null;
    $cfg ??= require SG_ROOT . '/config/config.php';
    return $cfg[$key] ?? null;
}

/* Escape text for HTML. */
function e(?string $s): string
{
    return htmlspecialchars($s ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/* Signed token that goes in every form: it proves the form came from this site and was not posted instantly. */
function form_secret(): string
{
    return config('secret') ?: hash('sha256', SG_ROOT . '|squaricle-group');
}

function form_token(string $form, int $ts): string
{
    return hash_hmac('sha256', $form . '|' . $ts, form_secret());
}

/* Hidden fields every form needs: which form it is, a signed time stamp, and a trap field for spam robots. */
function form_fields(string $form): string
{
    $ts = time();
    return '<input type="hidden" name="form" value="' . e($form) . '">'
        . '<input type="hidden" name="ts" value="' . $ts . '">'
        . '<input type="hidden" name="sig" value="' . e(form_token($form, $ts)) . '">'
        . '<div class="hp" aria-hidden="true"><label>Leave this empty <input type="text" name="website" tabindex="-1" autocomplete="off"></label></div>';
}

/* Wording for each kind of form error. */
function form_error_message(string $code): string
{
    $msgs = [
        'invalid' => 'Please check the form and try again. Every required field must be filled in, and the consent box ticked.',
        'send' => 'Sorry, we could not send your message just now. Please try again, or email ' . config('fallback_email') . '.',
        'fast' => 'That was very quick. Please wait a moment and press the button again.',
        'expired' => 'This form has expired. Please reload the page and try again.',
    ];
    return $msgs[$code] ?? $msgs['send'];
}

/* A message above the form when it was sent without JavaScript and something went wrong. */
function form_notice(string $form): string
{
    if (($_GET['form'] ?? '') !== $form || empty($_GET['error'])) {
        return '';
    }
    return '<p class="form-status error" role="alert">' . e(form_error_message((string) $_GET['error'])) . '</p>';
}

/* The client logo slider: one row of the list, then a hidden second copy so it loops without a jump. */
function logo_row(array $items): string
{
    $out = '';
    foreach ([false, true] as $hidden) {
        foreach ($items as $c) {
            $out .= '<img src="assets/img/clients/' . e($c['file']) . '" alt="' . ($hidden ? '' : e($c['name']))
                . '"' . ($hidden ? ' aria-hidden="true"' : '') . ' width="150" height="150" loading="lazy">';
        }
        $out .= "\n";
    }
    return $out;
}

/* A team photo, with the optional style and lazy-loading settings from data/team.json. */
function person_img(array $m): string
{
    return '<img class="ph" src="' . e($m['photo']) . '" alt="' . e($m['alt']) . '"'
        . (!empty($m['img_style']) ? ' style="' . e($m['img_style']) . '"' : '')
        . (!empty($m['lazy']) ? ' width="560" height="560" loading="lazy"' : '')
        . '>';
}
