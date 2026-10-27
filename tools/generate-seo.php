<?php
/* Builds sitemap.xml, llms.txt and llms-full.txt from data/pages.json and data/organization.json.
   Run it after you change a page, its title or description, or add a page:   php tools/generate-seo.php
   It also records each page's last-changed date in data/pages.json (taken from git when available). */
declare(strict_types=1);
$root = dirname(__DIR__);
chdir($root);
require $root . '/inc/bootstrap.php';
require $root . '/inc/seo.php';

$pages = data('pages');
$org = data('organization');
$site = data('site');
$today = gmdate('Y-m-d');

/* Last-changed dates: the newest git commit touching the page or the data files it uses. */
function last_changed(array $files, string $fallback): string
{
    $existing = array_filter($files, 'file_exists');
    if (!$existing) {
        return $fallback;
    }
    $cmd = 'git log -1 --format=%cs -- ' . implode(' ', array_map('escapeshellarg', $existing)) . ' 2>/dev/null';
    $out = trim((string) @shell_exec($cmd));
    if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $out)) {
        return $out;
    }
    return gmdate('Y-m-d', max(array_map('filemtime', $existing)));
}

foreach ($pages as &$p) {
    $deps = [$p['file'], 'inc/header.php', 'inc/footer.php'];
    foreach ($p['deps'] ?? [] as $d) {
        $deps[] = 'data/' . $d . '.json';
    }
    $deps[] = 'data/pages.json';
    /* Template-wide files change every page's look, but not its content, so only the page and its own data count. */
    $p['lastmod'] = last_changed([$p['file'], ...array_map(fn($d) => 'data/' . $d . '.json', $p['deps'] ?? [])], $today);
}
unset($p);
file_put_contents($root . '/data/pages.json', json_encode($pages, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . "\n");

/* ---- sitemap.xml ---- */
$xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n" . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">' . "\n";
foreach ($pages as $p) {
    $xml .= "<url>\n<loc>" . htmlspecialchars(page_url($p['file']), ENT_XML1) . "</loc>\n<lastmod>{$p['lastmod']}</lastmod>\n<changefreq>{$p['changefreq']}</changefreq>\n<priority>{$p['priority']}</priority>\n";
    if ($p['id'] === 'home') {
        $xml .= '<image:image><image:loc>' . htmlspecialchars(site_url($org['image']), ENT_XML1) . "</image:loc></image:image>\n";
    }
    $xml .= "</url>\n";
}
$xml .= "</urlset>\n";
file_put_contents($root . '/sitemap.xml', $xml);

/* ---- llms.txt ---- */
$o = $org['address'];
$lines = [];
$lines[] = '# ' . $org['name'];
$lines[] = '';
$lines[] = '> ' . $org['description'];
$lines[] = '';
$lines[] = $org['legalName'] . ' (company number ' . $org['companyNumber'] . ') is based in London. Registered office: ' . $o['streetAddress'] . ', ' . $o['addressLocality'] . ', ' . $o['postalCode'] . '. Email: ' . $org['email'] . '.';
$lines[] = '';
$lines[] = 'The Group\'s brands are ' . implode(', ', array_map(fn($b) => $b['name'], $org['brands'])) . '. Each brand\'s own website is linked below.';
$lines[] = '';

$sections = ['Brands' => [], 'About' => [], 'Policies' => []];
foreach ($pages as $p) {
    $label = $p['id'] === 'home' ? 'Squaricle Group home' : ($p['crumb'] ?? $p['title']);
    if ($p['llms']['section'] === 'Brands') {
        $label .= ' (page on this site)';
    }
    $sections[$p['llms']['section']][] = '- [' . $label . '](' . page_url($p['file']) . '): ' . $p['llms']['blurb'];
}
$brandLines = [];
foreach ($org['brands'] as $b) {
    $brandLines[] = '- [' . $b['name'] . ' (own website)](' . $b['url'] . '): ' . $b['description'];
}
$lines[] = '## Brands';
$lines[] = '';
array_push($lines, ...$sections['Brands'], ...$brandLines);
$lines[] = '';
foreach (['About', 'Policies'] as $sec) {
    $lines[] = '## ' . $sec;
    $lines[] = '';
    array_push($lines, ...$sections[$sec]);
    $lines[] = '';
}
$lines[] = '## Optional';
$lines[] = '';
$lines[] = '- [Full text of the main pages](' . site_url('llms-full.txt') . '): The readable text of every public page in one file.';
$lines[] = '- [Sitemap](' . site_url('sitemap.xml') . '): All public pages with their last-changed dates.';
file_put_contents($root . '/llms.txt', implode("\n", $lines) . "\n");

/* ---- llms-full.txt ---- */
function node_text(DOMNode $n, array &$out, int $depth = 0): void
{
    foreach ($n->childNodes as $c) {
        if (!$c instanceof DOMElement) {
            continue;
        }
        $tag = strtolower($c->tagName);
        $class = ' ' . $c->getAttribute('class') . ' ';
        if (in_array($tag, ['script', 'style', 'form', 'svg', 'iframe', 'video', 'button', 'nav', 'img', 'noscript'], true)
            || str_contains($class, ' marquee ') || str_contains($class, ' logos ') || str_contains($class, ' hp ') || str_contains($class, ' vh ')) {
            continue;
        }
        $text = trim((string) preg_replace('/\s+/u', ' ', $c->textContent));
        if (in_array($tag, ['h1', 'h2', 'h3', 'h4'], true)) {
            if ($text !== '') {
                $out[] = '';
                $out[] = str_repeat('#', (int) $tag[1]) . ' ' . $text;
                $out[] = '';
            }
        } elseif ($tag === 'p' || $tag === 'summary') {
            if ($text !== '') {
                $out[] = $text;
                $out[] = '';
            }
        } elseif ($tag === 'li') {
            if ($text !== '') {
                $out[] = '- ' . $text;
            }
        } elseif ($tag === 'a' && $c->parentNode && in_array($c->parentNode->nodeName, ['div', 'section'], true) && $c->getAttribute('class') !== '' && str_contains($c->getAttribute('class'), 'btn')) {
            continue;
        } else {
            node_text($c, $out, $depth + 1);
        }
    }
}

$full = ['# ' . $org['name'] . ' (full text of main pages)', '', '> ' . $org['description'], ''];
foreach ($pages as $p) {
    if (in_array($p['id'], ['privacy-policy', 'cookie-policy'], true)) {
        $full[] = '---';
        $full[] = '';
        $full[] = '## ' . $p['title'];
        $full[] = '';
        $full[] = 'Source: ' . page_url($p['file']);
        $full[] = '';
        $full[] = $p['description'];
        $full[] = '';
        continue;
    }
    $html = (string) shell_exec(escapeshellarg(PHP_BINARY) . ' ' . escapeshellarg($root . '/tools/render.php') . ' ' . escapeshellarg($p['file']) . ' 2>/dev/null');
    $dom = new DOMDocument();
    @$dom->loadHTML('<?xml encoding="utf-8"?>' . $html);
    $main = $dom->getElementsByTagName('main')->item(0);
    if (!$main) {
        continue;
    }
    $out = [];
    node_text($main, $out);
    $full[] = '---';
    $full[] = '';
    $full[] = '## Page: ' . ($p['id'] === 'home' ? 'Home' : ($p['crumb'] ?? $p['title']));
    $full[] = '';
    $full[] = 'Source: ' . page_url($p['file']);
    array_push($full, ...$out);
    if ($p['id'] === 'home') {
        $full[] = '### Some of our clients';
        $full[] = '';
        $full[] = implode(', ', array_map(fn($c) => $c['name'], data('clients'))) . '.';
        $full[] = '';
    }
}
$txt = implode("\n", $full);
$txt = (string) preg_replace("/\n{3,}/", "\n\n", $txt);
file_put_contents($root . '/llms-full.txt', trim($txt) . "\n");

echo "sitemap.xml: " . count($pages) . " pages\nllms.txt, llms-full.txt written\n";
