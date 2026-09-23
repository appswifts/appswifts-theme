# appswifts-theme

The **plugin-free WordPress theme** powering `new.appswifts.space` — the rebuilt
AppSwifts site. No page builder, no plugins, one stylesheet, CSS-only interactions.

## Design decisions

- **0 plugins, 0 Elementor.** Native WP blocks, one `style.css`, inline SVG icons.
- **CSS-only hamburger** (`:checked` + `grid-template-rows: 0fr → 1fr`) instead of a JS menu.
- **Variable fonts** — DM Sans (display) + Inter (body), self-hosted in
  `theme/assets/fonts/` so a deploy is self-contained. 4 `@font-face` rules, ~18 KB CSS
  (the previous Elementor site shipped 54 rules / 191 KB).
- **Google Sans was requested and is NOT used.** It is proprietary and not licensed for
  third-party web embedding. DM Sans is the closest open substitute. Swap is one line:
  `--font-display` in `style.css`.
- **Anti-slop rules:** headings capped at weight 600 (hierarchy comes from size, not weight),
  card hover changes the *border* to brand lime rather than lifting (a shadow is invisible
  on a near-black surface), CTA is flat `#86c13b` (a gradient muddies a single brand colour),
  content radii 12px / pills 999px and nothing between.
- **Contrast:** muted grey raised from `#7c8577` (3.83:1, fails AA) to `#6d7668` (4.73:1).
  `audit/a11y_audit.py` checks 30 pairs against the **live** stylesheet — all pass.

## Verified numbers

| | |
|---|---|
| body / hero H1 / section H2 / card H3 | 17.4px · 84px · 54.4px · 33.6px |
| homepage structure | 1 H1 / 5 H2 / 18 H3 |
| contrast | 30/30 pairs pass WCAG 2.2 AA |
| mobile nav | toggle 44×44px, panel 1px closed → 425px open, **0px** content shift |
| pages | 7 × HTTP 200 + 404 works |

## Layout

```
theme/                  drop into wp-content/themes/appswifts
  style.css             all design tokens + rules
  functions.php         theme supports, SEO meta + JSON-LD, icons, contact form handler
  header.php            CSS-only hamburger
  front-page.php        AI-first homepage
  page-*.php            template-per-page (contact / pricing / services / work / swifts-ai)
  home.php single.php archive.php page.php 404.php index.php
  inc/icons.php         inline SVG set
  assets/fonts/         DM Sans + Inter variable WOFF2
deploy.sh               tar the theme over SSH and recreate it on the host
provision.sh            create the dockerised WP + MySQL stack (generates its own secrets)
docker-compose.yml      the stack definition
audit/                  a11y contrast audit, copy-quality scan
verify/                 page status + structural checks
```

## Deploy

```bash
WP_HOST=my-ssh-host bash deploy.sh
```

Copies `theme/` to `/opt/appswifts-wp/html/wp-content/themes/appswifts`, chowns to the web
user, and flushes rewrites. Override the target with `WP_THEME_DIR` if your path differs.

## Provision from scratch

```bash
bash provision.sh          # generates MYSQL_ROOT_PASSWORD / MYSQL_PASSWORD into .env
```

Requires Docker + a TLS cert for the hostname. Secrets are generated at provision time and
written to `.env` — never committed.

## Audit

```bash
python3 audit/a11y_audit.py       # WCAG 2.2 AA against the LIVE stylesheet
python3 audit/copy_audit.py       # scans live HTML for AI-writing tells
bash verify/verify.sh             # every page returns 200
```

The audits read the **served** site, not the repo, so they catch deploy drift.

## Fonts licence

DM Sans and Inter are both **SIL Open Font License 1.1** — free to self-host and redistribute,
including commercially. Keep `LICENSE-fonts.txt` alongside them.

## Known gaps

- Contact form uses `admin-post.php` + nonce + honeypot. No spam service; if it gets abused,
  add a challenge rather than a plugin.
- The mobile menu closes on link click but **not** on outside-click — that needs JS, and
  `:checked` cannot do it.
- `0fr → 1fr` grid animation needs Chrome 107+ / Safari 16+ / Firefox 66+. Older browsers
  snap open instead of sliding.
