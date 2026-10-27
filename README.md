# Squaricle Group website

A small PHP website whose content lives in JSON files. PHP 8.1 or newer, no framework, no database, no build step, no Composer packages. Upload the folder to a PHP web host (Cloudways, or any Apache host with PHP) and it works.

- **Pages** are `.php` files. Each one sets its title and menu position, then pulls in the shared header and footer from `inc/`.
- **Content that repeats** is in `data/*.json`: the menu and footer (`site.json`), the client logos (`clients.json`), the project videos (`projects.json`), the team (`team.json`), the group policies (`policies.json`) and the forms (`forms.json`).
- **Settings** are in `config/config.php` (the webhook address and so on).
- **Forms** post to `submit.php`, which checks them, sends them to the webhook, and sends the visitor to `thank-you.php`.

## Pages

| File | Page |
| --- | --- |
| `index.php` | Home (video hero, brands, services, latest projects, clients, contact) |
| `ted-learning.php` | ted Learning |
| `emerging-u.php` | emerging-u |
| `our-team.php` | Our team and leadership team |
| `our-values.php` | Our values |
| `diversity-and-inclusion.php` | Diversity and inclusion |
| `people-policy.php` | People policy |
| `corporate-social-responsibility.php` | Corporate social responsibility |
| `careers.php` | Careers |
| `group-policies.php` | Group policies |
| `privacy-policy.php` | Privacy policy |
| `cookie-policy.php` | Cookie policy |
| `thank-you.php` | Thank you page shown after any form is sent |
| `404.php` | Not found page |
| `submit.php` | Not a page: receives every form (see Forms below) |

ted HuB and CurioTraining link out to their own sites (`hub.tedlearning.co.uk` and `www.curiotraining.com`).

## Menu and sub pages

The menu has drop-down sub pages, matching the old website:

- **Businesses and partners:** ted Learning, ted HuB, emerging-u, CurioTraining
- **Our people:** Leadership (a section of the Our team page), Our team
- **Our values:** Our values and mission, Diversity and inclusion, People policy, Corporate social responsibility
- **Careers** and **Talk to us**

The four Our values pages also share a row of tabs under the page heading so visitors can move between them. On phones the sub pages are listed under each heading in the menu.

## Videos

The "we deliver training that makes people more productive" play button and each of the nine project cards on the home page open their Vimeo video in a pop-up. The player is loaded only when someone clicks, with Vimeo's "do not track" option on, and is removed when the pop-up closes. Without JavaScript the links open the video on vimeo.com.

To change a video, edit the `data-vimeo="<id>"` number (and the matching `href`) on that link in `index.php`. If a video does not play on the live site, check its Vimeo privacy settings: videos limited to particular domains must have the new website's domain added in Vimeo (Settings, Privacy, "Where can this be embedded?").

## Photo montage

The montage on the home page slides slowly and endlessly, as on the old site (one full loop every 20 seconds). It starts on its own, and pauses while the pointer is over it. For visitors who ask their device for reduced motion it is a still image they can scroll sideways. The image is `assets/img/montage.jpg`; the speed is the `20s` in `css/site.css`.

## Client logos

The "you're in good company" band on the home page is two rows of client logos sliding in opposite directions (white SVGs in `assets/img/clients/` on the dark green band, listed in `data/clients.json`). They pause while the pointer is over them, and are a still, sideways-scrolling row for visitors who ask for reduced motion. The speed is the `90s` / `100s` in `css/site.css`.

## Folders

```
*.php               the pages
inc/                shared code: bootstrap.php (helpers), header.php, footer.php, head.php
data/               the JSON content (see above); closed to the web
config/config.php   settings; closed to the web
storage/            copies of enquiries the webhook did not accept, and CV files; closed to the web
css/site.css        shared: fonts, mobile menu, forms, project filter, hero video
css/<page>.css      styles for each page
js/site.js          mobile menu, hero background video, project filter, form sending
js/consent.js       cookie consent;  js/analytics.js  Google Analytics (after consent)
assets/img/         photos, logos, client logos (clients/), team photos
assets/fonts/       Montserrat (self-hosted, so no requests go to Google)
.htaccess           Apache: clean URLs, redirects from the old addresses, closes the private folders
sitemap.xml         for search engines (check the domain, see below)
```

## Try it locally

```
php -S localhost:8000
```

Then open http://localhost:8000 (PHP 8.1 or newer). To test the forms without sending anything to ted Learning, point them at a test address first: `SG_WEBHOOK_URL=http://localhost:9000/hook php -S localhost:8000`.

## Putting it live

- **Cloudways or any Apache host with PHP 8.1+:** upload everything in this folder to the site's `public_html` using SFTP. The `.htaccess` files (the main one and the ones inside `inc/`, `data/`, `config/` and `storage/`) must come with it. Back up the existing WordPress site first. The web server must be able to write to `storage/` (it only does so when a form could not be delivered, or a CV is received).
- **The site no longer works on static-only hosts** (Netlify, GitHub Pages) because the forms and page templates need PHP.
- Addresses work both as `/careers.php` and `/careers`. The first test build used `.html` addresses; those redirect to the `.php` pages.

## Forms and the webhook

Five forms: the contact form on the home page, the call-back form on Our team, the ted Learning guide form, the emerging-u brochure form and the careers application. All of them work the same way:

1. The visitor presses the button. With JavaScript the form is sent in the background; without it, as an ordinary form post.
2. `submit.php` checks the entries (fields and rules are in `data/forms.json`), ignores spam robots (a hidden trap field and a signed time stamp), and sends the details to **https://www.tedlearning.co.uk/wp-json/uap/v2/uap-60975-60977** (set in `config/config.php`).
3. If the webhook accepts it, the visitor goes to `thank-you.php`. The wording there is per form, in `data/forms.json`.
4. If the webhook is down or refuses it, the visitor sees an error asking them to try again or email, **and a copy is saved in `storage/failed/`** so the enquiry is not lost. Check that folder now and then (or ask your host to alert on the PHP error log, which also gets a line).

What the webhook receives (as a JSON body by default): `form` (which form), `form_label`, `site`, `page_url`, `submitted_at` (UTC), then the form's own fields: `first_name`, `last_name`, `email`, `phone`, `position`, `organisation`, `message`, `name`, `consent` (`yes`, or a sentence for the call-back form, which has no tick box). The careers form also sends `cv_filename`.

**To finish connecting it** (needs someone with access to the ted Learning WordPress site, which uses Uncanny Automator): put the recipe in listening mode, submit each form once from the live site, and let it pick up the fields. If it expects a normal form post rather than JSON, change `webhook_format` in `config/config.php` to `form`.

**CV files.** A webhook cannot normally carry a file. By default the CV is saved in `storage/cv/` and only its file name goes in the webhook. Set `webhook_format` to `multipart` if the receiving end can accept an uploaded file; the CV is then sent with the form and deleted from this server. Either way, decide who clears `storage/cv/` and how long CVs are kept, and make sure the privacy notice covers it. The host's PHP upload limit must be at least 5MB.

## Before launch: things to finish

1. **Hero video.** The home page hero plays https://vimeo.com/482661426 as a muted, looping background (Vimeo's "background" player, with Vimeo's do-not-track option on). It loads once the page has finished loading and fades in when it starts; until then, and for visitors who ask their device for reduced motion, the hero shows a still photo. There is no pause button. To change the video, edit the number in the `data-src` of the `hero-video` iframe in `index.php`. The video must be allowed to be embedded on this domain in Vimeo (Settings, Privacy, "Where can this be embedded?"), or it will not play. Vimeo is a third party, so the privacy and cookie notices should mention it.
2. **Forms.** Done, apart from connecting the receiving end (see "Forms and the webhook"). The privacy notice should say that enquiries are passed to ted Learning Limited.
3. **Cookie consent.** The site has its own consent tool (`js/consent.js` and `css/consent.css`): a banner on first visit with Accept all, Reject all and Manage preferences, a preferences panel (Functional is always on; Preferences, Statistics and Marketing are off until allowed), and a "Cookie settings" link in every footer. The "Change your consent" and "Withdraw your consent" buttons on the cookie policy page are connected to it. The choice is kept in one cookie, `sg_consent`, for 12 months. Google Analytics is loaded through this tool: `js/analytics.js` holds the measurement ID and only runs once a visitor allows Statistics. Rejecting or withdrawing removes the `_ga`, `_gid` and `_ga_*` cookies and switches tracking off. **The ID currently in the file, `UA-163660789-1`, is a Universal Analytics ID. Google stopped processing those in 2023, so it will record nothing. Create a Google Analytics 4 property and put its `G-XXXXXXXXXX` ID in `js/analytics.js` (one line).** The site sets no other cookies and loads no other third-party scripts yet, apart from Vimeo when someone plays a video. To add analytics, advertising or similar later, mark the script so it only loads after consent: `<script type="text/plain" data-consent="statistics" src="..."></script>` (use `statistics`, `marketing` or `preferences`). Have your legal contact review the banner wording. The cookie page lists `www.squariclegroup.com` as the domain the consent applies to, and its text is copied from the ted Learning site (including its opening paragraph about ted Learning and the "ted Learning use technologies" line), so review it and the cookie categories against what the live site really sets.
4. **Privacy notice.** This is the complete September 2026 notice supplied by the client. The only change is the opening line, which now names `www.squariclegroup.com` instead of listing the group's other sites. Have the document owner confirm that is what they want.
5. **Group policies.** The seven policy PDFs are in `assets/policies/` and linked from `group-policies.php`. The privacy notice PDF there is version 1.3, which still lists all the group sites in its opening line; the web page names only www.squariclegroup.com. Check that the two should match.
6. **Mission statement.** `our-values.php` has a placeholder under "our mission". The current website refers to a mission statement but doesn't show one.
6. **Vacancies.** `careers.php` has a placeholder where current vacancies should be listed.
7. **Domain.** `sitemap.xml` and `robots.txt` assume `https://www.squariclegroup.com/`. Change them if the domain differs.
8. **Old addresses.** The redirects in `.htaccess` send the old WordPress paths (for example `/our-team/` and `/site-notice/`) to the new pages, so existing links and search rankings keep working. Check them against Google Search Console once it is live.
9. **Photographs and logos.** The client logos, project images and team photos were taken from the current website. Confirm you have permission to keep using each of them.
10. **emerging-u page.** Its text is still the old copy from squariclegroup.com. emerging-u.com has newer content (POP, case studies, results) you may want to bring across.

## Editing

- **Wording on a page:** edit that page's `.php` file. It is plain HTML below a few lines of PHP at the top.
- **Menu and footer** (links, address, the small print): `data/site.json`. They change on every page at once.
- **Client logos:** add a white SVG to `assets/img/clients/` and a line to `data/clients.json`. The two rows share the list: odd entries go in the second row.
- **Project videos:** `data/projects.json` (the Vimeo number, title, picture and label).
- **Team:** `data/team.json`. `group` is `leadership` or `team`; `bg` is the colour band, `rev` puts the photo on the right.
- **Group policies:** `data/policies.json`.
- **Forms and thank-you wording:** `data/forms.json`.
- A JSON file must stay valid: if the site shows a blank page after an edit, check for a missing comma or quote (a JSON checker such as jsonlint.com helps).
- Shared styling lives in `css/site.css`, and each page's own layout is in `css/<page-name>.css`.

## Accessibility

Real buttons and links throughout, a skip link, visible focus, labelled form fields, alt text on images, and text colours that pass WCAG AA contrast. The hero background video is muted, decorative, hidden from screen readers, and does not load for visitors who ask their device for reduced motion.
