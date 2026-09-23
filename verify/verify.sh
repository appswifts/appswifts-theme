#!/usr/bin/env bash
cd /tmp || exit 1

echo "=== PAGE STATUS ==="
for p in "" "services/" "pricing/" "work/" "contact/" "blog/" "swifts-ai/" "nonexistent-page/"; do
  code=$(curl -s -o /tmp/pg.html -w "%{http_code}" -m 30 "https://new.appswifts.space/$p")
  size=$(wc -c < /tmp/pg.html)
  printf "  %-22s %s  %7s bytes\n" "/$p" "$code" "$size"
done

echo
echo "=== HOMEPAGE SEO ==="
curl -s -m 25 https://new.appswifts.space/ -o /tmp/hp.html
echo "  meta description : $(grep -o 'name="description" content="[^"]*"' /tmp/hp.html | head -c 130)"
echo "  og:title         : $(grep -o 'og:title" content="[^"]*"' /tmp/hp.html)"
echo "  og:image present : $(grep -c 'og:image' /tmp/hp.html)"
echo "  JSON-LD blocks   : $(grep -c 'application/ld+json' /tmp/hp.html)"
echo "  twitter card     : $(grep -o 'twitter:card" content="[^"]*"' /tmp/hp.html)"
echo "  H1/H2/H3         : $(grep -c '<h1' /tmp/hp.html)/$(grep -c '<h2' /tmp/hp.html)/$(grep -c '<h3' /tmp/hp.html)"
echo "  font preloads    : $(grep -c 'rel="preload"' /tmp/hp.html)"
echo "  css links        : $(grep -o '<link rel="stylesheet"' /tmp/hp.html | wc -l)"
echo "  script tags      : $(grep -o '<script' /tmp/hp.html | wc -l)"
echo "  html bytes       : $(wc -c < /tmp/hp.html)"

echo
echo "=== CSS SIZE vs OLD SITE ==="
echo "  new theme css : $(curl -s -m 20 -o /dev/null -w '%{size_download}' https://new.appswifts.space/wp-content/themes/appswifts/style.css) bytes"
echo "  old site css  : 191644 bytes (Elementor aggregate)"
