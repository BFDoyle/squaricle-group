<?php
/* Receives every form on the site (see data/forms.json for the fields of each one).
   It checks the entries, sends them to the webhook set in config/config.php, then sends the visitor
   to the thank-you page. With JavaScript the page posts here in the background and gets JSON back;
   without JavaScript it is an ordinary form post followed by a redirect. */
declare(strict_types=1);
require __DIR__ . '/inc/bootstrap.php';

header('Cache-Control: no-store');

$wantsJson = stripos($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json') !== false;
$forms = data('forms');
$formId = (string) ($_POST['form'] ?? '');

/* Answer the visitor and stop. */
function finish(bool $wantsJson, array $forms, string $formId, ?string $error = null): never
{
    $def = $forms[$formId] ?? null;
    if ($error === null) {
        $url = config('thank_you_page') . '?form=' . rawurlencode($formId);
    } else {
        $page = $def['page'] ?? 'index.php';
        $url = $page . '?form=' . rawurlencode($formId) . '&error=' . rawurlencode($error) . '#' . ($def['anchor'] ?? 'contact');
    }
    if ($wantsJson) {
        header('Content-Type: application/json; charset=utf-8');
        http_response_code($error === null ? 200 : ($error === 'send' ? 502 : 422));
        echo json_encode([
            'ok' => $error === null,
            'redirect' => $error === null ? $url : null,
            'error' => $error === null ? null : form_error_message($error),
        ]);
    } else {
        header('Location: ' . $url, true, 303);
    }
    exit;
}

/* Send the entries to the webhook. Returns null on success, or a short reason on failure. */
function send_to_webhook(array $payload, ?array $file): ?string
{
    $format = config('webhook_format');
    $headers = ['Accept: application/json'];
    if ($format === 'json') {
        $headers[] = 'Content-Type: application/json';
        $body = json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    } elseif ($format === 'multipart') {
        $body = $payload;
        if ($file !== null) {
            $body['cv'] = new CURLFile($file['path'], $file['mime'], $file['name']);
        }
    } else {
        $body = http_build_query($payload);
    }

    $ch = curl_init((string) config('webhook_url'));
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $body,
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CONNECTTIMEOUT => 5,
        CURLOPT_TIMEOUT => (int) config('webhook_timeout'),
        CURLOPT_FOLLOWLOCATION => false,
        CURLOPT_USERAGENT => 'SquaricleGroupWebsite/1.0',
    ]);
    $response = curl_exec($ch);
    $status = (int) curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
    $curlError = curl_error($ch);
    curl_close($ch);

    if ($response === false) {
        return 'Could not reach the webhook: ' . $curlError;
    }
    if ($status < 200 || $status >= 300) {
        return 'The webhook answered with HTTP ' . $status . ': ' . substr((string) $response, 0, 300);
    }
    return null;
}

/* A folder under storage/ (closed to the web by storage/.htaccess). */
function storage_dir(string $name): string
{
    $dir = SG_ROOT . '/storage/' . $name;
    if (!is_dir($dir)) {
        mkdir($dir, 0750, true);
    }
    return $dir;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php', true, 303);
    exit;
}
if (!isset($forms[$formId])) {
    http_response_code(400);
    exit('Unknown form.');
}

/* Only accept posts that come from this site. */
$origin = parse_url((string) ($_SERVER['HTTP_ORIGIN'] ?? ''), PHP_URL_HOST);
if ($origin && strcasecmp($origin, (string) ($_SERVER['HTTP_HOST'] ?? '')) !== 0 && strcasecmp($origin, (string) preg_replace('/:\d+$/', '', (string) ($_SERVER['HTTP_HOST'] ?? ''))) !== 0) {
    http_response_code(403);
    exit('Forbidden.');
}

/* Robots fill in the hidden "website" field; people never see it. Pretend it worked. */
if (trim((string) ($_POST['website'] ?? '')) !== '') {
    finish($wantsJson, $forms, $formId);
}

/* The signed time stamp must be genuine, at least 2 seconds old and less than a day old. */
$ts = (int) ($_POST['ts'] ?? 0);
if (!hash_equals(form_token($formId, $ts), (string) ($_POST['sig'] ?? ''))) {
    finish($wantsJson, $forms, $formId, 'expired');
}
$age = time() - $ts;
if ($age > 86400) {
    finish($wantsJson, $forms, $formId, 'expired');
}
if ($age < 2) {
    finish($wantsJson, $forms, $formId, 'fast');
}

$def = $forms[$formId];
$values = [];
$file = null;
$valid = true;

foreach ($def['fields'] as $f) {
    $name = $f['name'];
    $required = !empty($f['required']);

    if ($f['type'] === 'checkbox') {
        $ticked = !empty($_POST[$name]);
        if ($required && !$ticked) {
            $valid = false;
        }
        $values[$name] = $ticked ? 'yes' : 'no';
        continue;
    }

    if ($f['type'] === 'file') {
        $up = $_FILES[$name] ?? null;
        $has = $up && ($up['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE;
        if (!$has) {
            if ($required) {
                $valid = false;
            }
            continue;
        }
        $ext = strtolower(pathinfo((string) $up['name'], PATHINFO_EXTENSION));
        $magic = ($up['error'] === UPLOAD_ERR_OK && is_uploaded_file($up['tmp_name'])) ? (string) file_get_contents($up['tmp_name'], false, null, 0, 8) : '';
        $looksRight = str_starts_with($magic, '%PDF') || str_starts_with($magic, 'PK') || str_starts_with($magic, "\xD0\xCF\x11\xE0");
        if ($up['error'] !== UPLOAD_ERR_OK || !in_array($ext, $f['ext'], true) || !$looksRight || $up['size'] > $f['max_mb'] * 1048576) {
            $valid = false;
            continue;
        }
        $stored = storage_dir('cv') . '/' . date('Ymd-His') . '-' . bin2hex(random_bytes(6)) . '.' . $ext;
        if (!move_uploaded_file($up['tmp_name'], $stored)) {
            finish($wantsJson, $forms, $formId, 'send');
        }
        $mimes = ['pdf' => 'application/pdf', 'doc' => 'application/msword', 'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
        $safeName = preg_replace('/[^A-Za-z0-9._ -]+/', '_', basename((string) $up['name']));
        $file = ['path' => $stored, 'name' => $safeName, 'mime' => $mimes[$ext]];
        $values[$name . '_filename'] = $safeName;
        continue;
    }

    $v = trim((string) ($_POST[$name] ?? ''));
    $v = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $v) ?? '';
    if ($required && $v === '') {
        $valid = false;
    }
    if ($v !== '') {
        if (mb_strlen($v) > ($f['max'] ?? 200)) {
            $valid = false;
        }
        if ($f['type'] === 'email' && !filter_var($v, FILTER_VALIDATE_EMAIL)) {
            $valid = false;
        }
        if ($f['type'] === 'tel' && !preg_match('/^[0-9+()\-.\s]{6,40}$/', $v)) {
            $valid = false;
        }
    }
    $values[$name] = $v;
}

if (!$valid) {
    if ($file !== null) {
        unlink($file['path']);
    }
    finish($wantsJson, $forms, $formId, 'invalid');
}

if (!isset($values['consent']) && isset($def['implied_consent'])) {
    $values['consent'] = $def['implied_consent'];
}

$host = preg_replace('/[^A-Za-z0-9.:-]/', '', (string) ($_SERVER['HTTP_HOST'] ?? data('site')['domain']));
$payload = [
    'form' => $formId,
    'form_label' => $def['label'],
    'site' => $host,
    'page_url' => ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http') . '://' . $host . '/' . $def['page'],
    'submitted_at' => gmdate('c'),
] + $values;

$failure = send_to_webhook($payload, $file);

if ($failure !== null) {
    /* Keep a copy so the enquiry is not lost, and tell the visitor. */
    file_put_contents(
        storage_dir('failed') . '/' . date('Ymd-His') . '-' . $formId . '-' . bin2hex(random_bytes(4)) . '.json',
        json_encode(['payload' => $payload, 'cv_file' => $file['path'] ?? null, 'error' => $failure], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
    );
    error_log('Squaricle form "' . $formId . '" not delivered: ' . $failure);
    finish($wantsJson, $forms, $formId, 'send');
}

/* Delivered. A CV that went with the webhook does not need to stay on this server. */
if ($file !== null && config('webhook_format') === 'multipart') {
    unlink($file['path']);
}
finish($wantsJson, $forms, $formId);
