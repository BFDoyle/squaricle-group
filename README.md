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
3. **Cookie consent.** The site sets no cookies and loads no third-party scripts at the moment. If you add analytics or a chat bot, add a consent banner and update `cookie-policy.html`. The cookie page's domain line is marked "to be confirmed".
4. **Privacy notice.** The text was copied from the current site, which has about 20 sentences that stop mid-way. They are marked with [...]. Have the owner of the document complete them, and confirm the version date (the current page reads "1 September 20226").
5. **Group policies.** The "View policy" links on `group-policies.html` go nowhere yet. Link each to its PDF or page.
6. **Vacancies.** `careers.html` has a placeholder where current vacancies should be listed.
7. **Domain.** `sitemap.xml` and `robots.txt` assume `https://www.squariclegroup.com/`. Change them if the domain differs.
8. **Old addresses.** The redirects in `.htaccess` and `_redirects` send the old WordPress paths (for example `/our-team/` and `/site-notice/`) to the new pages, so existing links and search rankings keep working. Check them against Google Search Console once it is live.
9. **Photographs and logos.** The client logos, project images and team photos were taken from the current website. Confirm you have permission to keep using each of them.
10. **emerging-u page.** Its text is still the old copy from squariclegroup.com. emerging-u.com has newer content (POP, case studies, results) you may want to bring across.

## Editing

Each page is a self-contained HTML file. The header and footer are repeated in every file, so a change to the menu or the footer has to be made in all of them (search for `id="site-nav"` and `<footer`). Shared styling lives in `css/site.css`, and each page's own layout is in `css/<page-name>.css`.

## Accessibility

Real buttons and links throughout, a skip link, visible focus, labelled form fields, alt text on images, and text colours that pass WCAG AA contrast. The hero video starts muted, has a pause button, and does not play for visitors who ask their device for reduced motion.
