<?php
declare(strict_types=1);
/* Search engine and AI answer-engine helpers: canonical addresses, social sharing tags and schema.org structured data.
   Page titles and descriptions live in data/pages.json, the organisation and brands in data/organization.json. */

function site_url(string $path = ''): string
{
    return rtrim((string) config('site_url'), '/') . '/' . ltrim($path, '/');
}

/* The public address of a page file, e.g. careers.php. The home page is just the site address. */
function page_url(string $file): string
{
    return $file === 'index.php' ? site_url() : site_url($file);
}

function page_by_id(string $id): ?array
{
    foreach (data('pages') as $p) {
        if ($p['id'] === $id) {
            return $p;
        }
    }
    return null;
}

function org_id(): string
{
    return site_url('#organization');
}

function ref(string $id): array
{
    return ['@id' => $id];
}

function image_node(string $path, int $w, int $h, string $caption = ''): array
{
    $n = ['@type' => 'ImageObject', 'url' => site_url($path), 'contentUrl' => site_url($path), 'width' => $w, 'height' => $h];
    if ($caption !== '') {
        $n['caption'] = $caption;
    }
    return $n;
}

/* The Group itself. $full adds the long description, address and brands (used on the home and team pages). */
function org_node(bool $full): array
{
    $o = data('organization');
    $n = [
        '@type' => 'Organization',
        '@id' => org_id(),
        'name' => $o['name'],
        'legalName' => $o['legalName'],
        'url' => site_url(),
    ];
    $n['logo'] = ['@type' => 'ImageObject', '@id' => site_url('#logo'), 'url' => site_url($o['logo']), 'contentUrl' => site_url($o['logo']), 'width' => 520, 'height' => 520, 'caption' => $o['name'] . ' logo'];
    $n['image'] = ref(site_url('#logo'));
    if (!$full) {
        return $n;
    }
    $n['alternateName'] = $o['alternateName'];
    $n['description'] = $o['description'];
    $n['foundingDate'] = $o['foundingDate'];
    $n['email'] = $o['email'];
    $n['address'] = ['@type' => 'PostalAddress'] + $o['address'];
    $n['contactPoint'] = [[
        '@type' => 'ContactPoint',
        'contactType' => 'customer service',
        'email' => $o['email'],
        'areaServed' => 'GB',
        'availableLanguage' => 'en',
    ]];
    $n['identifier'] = ['@type' => 'PropertyValue', 'propertyID' => 'Companies House company number', 'value' => $o['companyNumber']];
    $n['knowsAbout'] = $o['knowsAbout'];
    $n['founder'] = ref(person_id($o['founder']));
    $n['brand'] = array_map(fn($b) => ref(brand_id($b)), $o['brands']);
    $n['subOrganization'] = array_map(fn($b) => ref(brand_id($b)), $o['brands']);
    return $n;
}

function brand_id(array $b): string
{
    return site_url('#brand-' . $b['id']);
}

function person_id(string $slug): string
{
    return site_url('our-team.php#person-' . $slug);
}

function brand_node(array $b): array
{
    $n = [
        '@type' => 'Organization',
        '@id' => brand_id($b),
        'name' => $b['name'],
        'url' => $b['url'],
        'description' => $b['description'],
        'parentOrganization' => ref(org_id()),
        'logo' => image_node($b['logo'], 500, 500, $b['name'] . ' logo'),
    ];
    if (!empty($b['legalName'])) {
        $n['legalName'] = $b['legalName'];
    }
    if (!empty($b['foundingDate'])) {
        $n['foundingDate'] = $b['foundingDate'];
    }
    if (!empty($b['sameAs'])) {
        $n['sameAs'] = $b['sameAs'];
    }
    if (!empty($b['page'])) {
        $n['mainEntityOfPage'] = page_url($b['page'] . '.php');
    }
    return $n;
}

function slugify(string $s): string
{
    return trim((string) preg_replace('/[^a-z0-9]+/', '-', strtolower($s)), '-');
}

/* Breadcrumb trail from the parent chain in data/pages.json. */
function breadcrumb_node(array $page): ?array
{
    if (($page['id'] ?? '') === 'home') {
        return null;
    }
    $chain = [];
    $cur = $page;
    while ($cur) {
        array_unshift($chain, $cur);
        $cur = !empty($cur['parent']) ? page_by_id($cur['parent']) : null;
    }
    $items = [];
    foreach ($chain as $i => $p) {
        $items[] = [
            '@type' => 'ListItem',
            'position' => $i + 1,
            'name' => $p['id'] === 'home' ? 'Home' : ($p['crumb'] ?? $p['title']),
            'item' => page_url($p['file']),
        ];
    }
    return ['@type' => 'BreadcrumbList', '@id' => page_url($page['file']) . '#breadcrumb', 'itemListElement' => $items];
}

/* Everything about the current page as one schema.org graph. */
function schema_graph(array $page): array
{
    $o = data('organization');
    $url = page_url($page['file']);
    $id = $page['id'];
    $full = in_array($id, ['home', 'our-team'], true);
    $graph = [org_node($full)];

    $graph[] = [
        '@type' => 'WebSite', '@id' => site_url('#website'), 'url' => site_url(), 'name' => $o['name'],
        'description' => $o['description'], 'inLanguage' => 'en-GB', 'publisher' => ref(org_id()),
    ];
    if ($full) {
        foreach ($o['brands'] as $b) {
            $graph[] = brand_node($b);
        }
        if ($id !== 'our-team') {
            foreach (data('team') as $m) {
                if (slugify($m['name']) === $o['founder']) {
                    $graph[] = person_node($m, page_url('our-team.php'));
                }
            }
        }
    }

    $web = [
        '@type' => $page['type'],
        '@id' => $url . '#webpage',
        'url' => $url,
        'name' => $page['title'],
        'description' => $page['description'],
        'inLanguage' => 'en-GB',
        'isPartOf' => ref(site_url('#website')),
        'about' => ref(org_id()),
        'publisher' => ref(org_id()),
        'primaryImageOfPage' => image_node($o['image'], 1200, 630),
        'image' => site_url($o['image']),
    ];
    if (!empty($page['lastmod'])) {
        $web['dateModified'] = $page['lastmod'];
    }
    $crumbs = breadcrumb_node($page);
    if ($crumbs) {
        $web['breadcrumb'] = ref($crumbs['@id']);
    }
    $pageBrand = null;
    foreach ($o['brands'] as $b) {
        if (($b['page'] ?? '') === $id && !$full) {
            $pageBrand = $b;
            $web['mainEntity'] = ref(brand_id($b));
        }
    }
    $graph[] = $web;
    if ($crumbs) {
        $graph[] = $crumbs;
    }
    if ($pageBrand) {
        $graph[] = brand_node($pageBrand);
    }

    if ($id === 'ted-learning') {
        $ted = array_values(array_filter($o['brands'], fn($b) => $b['id'] === 'ted-learning'))[0];
        $graph[] = [
            '@type' => 'Service',
            '@id' => $url . '#service',
            'name' => 'Theatre based learning',
            'serviceType' => 'Theatre based learning and blended learning',
            'description' => $page['description'],
            'provider' => ref(brand_id($ted)),
            'url' => $url,
        ];
        $faq = array_map(fn($q) => [
            '@type' => 'Question',
            'name' => $q['q'],
            'acceptedAnswer' => ['@type' => 'Answer', 'text' => $q['a']],
        ], data('ted-faq'));
        $graph[] = ['@type' => 'FAQPage', '@id' => $url . '#faq', 'url' => $url . '#faq', 'mainEntity' => $faq, 'isPartOf' => ref($url . '#webpage')];
    }

    if ($id === 'our-team') {
        foreach (data('team') as $m) {
            $graph[] = person_node($m, $url);
        }
    }

    return ['@context' => 'https://schema.org', '@graph' => $graph];
}

function person_node(array $m, string $pageUrl): array
{
    return [
        '@type' => 'Person',
        '@id' => person_id(slugify($m['name'])),
        'name' => $m['name'],
        'jobTitle' => $m['role'],
        'image' => site_url($m['photo']),
        'description' => $m['paras'][0] ?? $m['role'],
        'worksFor' => ref(org_id()),
        'url' => $pageUrl,
    ];
}

function json_ld(array $data): string
{
    return '<script type="application/ld+json">' . json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP) . '</script>';
}
