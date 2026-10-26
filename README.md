# Squaricle Group website

A static website: plain HTML, CSS and a little JavaScript. No build step, no framework, no dependencies. Upload the folder to any web host and it works.

## Pages

| File | Page |
| --- | --- |
| `index.html` | Home (video hero, brands, services, latest projects, clients, contact) |
| `ted-learning.html` | ted Learning |
| `emerging-u.html` | emerging-u |
| `our-team.html` | Our team and leadership team |
| `our-values.html` | Our values |
| `diversity-and-inclusion.html` | Diversity and inclusion |
| `people-policy.html` | People policy |
| `corporate-social-responsibility.html` | Corporate social responsibility |
| `careers.html` | Careers |
| `group-policies.html` | Group policies |
| `privacy-policy.html` | Privacy policy |
| `cookie-policy.html` | Cookie policy |
| `404.html` | Not found page |

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

To change a video, edit the `data-vimeo="<id>"` number (and the matching `href`) on that link in `index.html`. If a video does not play on the live site, check its Vimeo privacy settings: videos limited to particular domains must have the new website's domain added in Vimeo (Settings, Privacy, "Where can this be embedded?").

## Folders

```
css/site.css        shared: fonts, mobile menu, forms, project filter, hero video
css/<page>.css      styles for each page
js/site.js          mobile menu, hero video pause, project filter, form handling
assets/img/         photos, logos, client logos, team photos
assets/video/       hero video loop
assets/fonts/       Montserrat (self-hosted, so no requests go to Google)
.htaccess           Apache clean URLs and redirects from the old WordPress addresses
_redirects          the same redirects for Netlify and Cloudflare Pages
sitemap.xml         for search engines (check the domain, see below)
```

## Try it locally

```
python3 -m http.server 8000
```

Then open http://localhost:8000. (Opening `index.html` straight from disk also works, apart from the video on some browsers.)

## Putting it live

- **Cloudways or any Apache/Nginx host:** upload everything in this folder to the site's `public_html` using SFTP. The `.htaccess` file must come with it. Back up the existing WordPress site first.
- **Netlify, Cloudflare Pages or GitHub Pages:** point the service at this repository, or drag the folder in. No build command is needed, and the publish directory is the repository root.

## Before launch: things to finish

1. **Hero video.** `assets/video/hero-loop.mp4` is a stand-in made from your project photographs. Replace it with real footage: silent, 8 to 12 seconds, around 1280 x 720, under 3MB. Keep the same file name, or change it in `index.html`.
2. **Forms.** There are five: the contact form on the home page, the contact form on the Our team page, the ted Learning guide form, the emerging-u brochure form and the careers application. None of them send anything yet. Pick a form service (Formspree, Netlify Forms, or your own endpoint) and add its address to each form: `<form data-form data-endpoint="https://formspree.io/f/xxxx">`. Until then each form shows a notice saying it is not connected. The careers CV upload needs a service that accepts file uploads.
3. **Cookie consent.** The site has its own consent tool (`js/consent.js` and `css/consent.css`): a banner on first visit with Accept all, Reject all and Manage preferences, a preferences panel (Functional is always on; Preferences, Statistics and Marketing are off until allowed), and a "Cookie settings" link in every footer. The "Change your consent" and "Withdraw your consent" buttons on the cookie policy page are connected to it. The choice is kept in one cookie, `sg_consent`, for 12 months. Google Analytics is loaded through this tool: `js/analytics.js` holds the measurement ID and only runs once a visitor allows Statistics. Rejecting or withdrawing removes the `_ga`, `_gid` and `_ga_*` cookies and switches tracking off. **The ID currently in the file, `UA-163660789-1`, is a Universal Analytics ID. Google stopped processing those in 2023, so it will record nothing. Create a Google Analytics 4 property and put its `G-XXXXXXXXXX` ID in `js/analytics.js` (one line).** The site sets no other cookies and loads no other third-party scripts yet, apart from Vimeo when someone plays a video. To add analytics, advertising or similar later, mark the script so it only loads after consent: `<script type="text/plain" data-consent="statistics" src="..."></script>` (use `statistics`, `marketing` or `preferences`). Have your legal contact review the banner wording. The cookie page lists `www.squariclegroup.com` as the domain the consent applies to, and its text is copied from the ted Learning site (including its opening paragraph about ted Learning and the "ted Learning use technologies" line), so review it and the cookie categories against what the live site really sets.
4. **Privacy notice.** This is the complete September 2026 notice supplied by the client. The only change is the opening line, which now names `www.squariclegroup.com` instead of listing the group's other sites. Have the document owner confirm that is what they want.
5. **Group policies.** The seven policy PDFs are in `assets/policies/` and linked from `group-policies.html`. The privacy notice PDF there is version 1.3, which still lists all the group sites in its opening line; the web page names only www.squariclegroup.com. Check that the two should match.
6. **Mission statement.** `our-values.html` has a placeholder under "our mission". The current website refers to a mission statement but doesn't show one.
6. **Vacancies.** `careers.html` has a placeholder where current vacancies should be listed.
7. **Domain.** `sitemap.xml` and `robots.txt` assume `https://www.squariclegroup.com/`. Change them if the domain differs.
8. **Old addresses.** The redirects in `.htaccess` and `_redirects` send the old WordPress paths (for example `/our-team/` and `/site-notice/`) to the new pages, so existing links and search rankings keep working. Check them against Google Search Console once it is live.
9. **Photographs and logos.** The client logos, project images and team photos were taken from the current website. Confirm you have permission to keep using each of them.
10. **emerging-u page.** Its text is still the old copy from squariclegroup.com. emerging-u.com has newer content (POP, case studies, results) you may want to bring across.

## Editing

Each page is a self-contained HTML file. The header and footer are repeated in every file, so a change to the menu or the footer has to be made in all of them (search for `id="site-nav"` and `<footer`). Shared styling lives in `css/site.css`, and each page's own layout is in `css/<page-name>.css`.

## Accessibility

Real buttons and links throughout, a skip link, visible focus, labelled form fields, alt text on images, and text colours that pass WCAG AA contrast. The hero video starts muted, has a pause button, and does not play for visitors who ask their device for reduced motion.
