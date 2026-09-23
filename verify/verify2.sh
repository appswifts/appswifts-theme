#!/usr/bin/env bash
cd /tmp || exit 1
UA="curl/8.5.0"

echo "=== SITE ==="
for p in "" "swifts-ai/" "services/" "pricing/" "work/" "contact/" "blog/"; do
  code=$(curl -s -o /tmp/pg.html -w "%{http_code}" -m 30 -A "$UA" "https://new.appswifts.space/$p")
  printf "  %-14s %s  %7s bytes\n" "/$p" "$code" "$(wc -c < /tmp/pg.html)"
done

echo
echo "=== FONTS ACTUALLY SERVED ==="
curl -s -m 25 -A "$UA" https://new.appswifts.space/wp-content/themes/appswifts/style.css -o /tmp/t.css
grep -oE 'font-family:[^;]*' /tmp/t.css | grep -iE 'DM Sans|Inter' | sort -u | sed 's/^/  /'
echo "  declared families: $(grep -oE '@font-face' /tmp/t.css | wc -l) @font-face rules"
for f in dmsans-var.woff2 inter-var.woff2 inter-var-italic.woff2; do
  code=$(curl -s -o /dev/null -w "%{http_code}" -m 20 -A "$UA" "https://new.appswifts.space/wp-content/themes/appswifts/assets/fonts/$f")
  size=$(curl -s -o /dev/null -w "%{size_download}" -m 20 -A "$UA" "https://new.appswifts.space/wp-content/themes/appswifts/assets/fonts/$f")
  printf "  %-24s HTTP %s  %s bytes\n" "$f" "$code" "$size"
done
echo "  preload tags: $(grep -c 'rel="preload"' /tmp/hp.html 2>/dev/null || echo '-')"

echo
echo "=== TYPE SCALE (computed for 1440px viewport) ==="
python3 - <<'PY'
import re
css = open('/tmp/t.css', encoding='utf-8', errors='replace').read()
VW = 1440

def resolve(clamp):
    lo, pref, hi = [p.strip() for p in clamp.split(',')]
    lo = float(re.search(r'([\d.]+)rem', lo).group(1)) * 16
    hi = float(re.search(r'([\d.]+)rem', hi).group(1)) * 16
    m = re.search(r'([\d.]+)rem\s*\+\s*([\d.]+)vw', pref)
    if m:
        val = float(m.group(1)) * 16 + float(m.group(2)) / 100 * VW
    else:
        val = lo
    return min(max(val, lo), hi)

for name in ['--t-base', '--t-lg', '--t-xl', '--t-2xl', '--t-3xl', '--t-4xl']:
    m = re.search(re.escape(name) + r':\s*(clamp\([^;]+\))', css)
    if m:
        px = resolve(m.group(1))
        print(f"  {name:<9} {px:6.1f}px")
PY

echo
echo "=== HERO H1 ==="
curl -s -m 25 -A "$UA" https://new.appswifts.space/ -o /tmp/hp.html
python3 - <<'PY'
import re
h = open('/tmp/hp.html', encoding='utf-8', errors='replace').read()
m = re.search(r'<h1[^>]*class="hero__title"[^>]*>(.*?)</h1>', h, re.S)
print("  H1 text :", re.sub(r'\s+', ' ', m.group(1)).strip() if m else "NOT FOUND")
m2 = re.search(r'<h1', h)
print("  H1 class:", re.search(r'<h1[^>]*class="([^"]+)"', h).group(1) if re.search(r'<h1[^>]*class="([^"]+)"', h) else re.search(r'<h1[^>]*>', h).group(0))
print("  H1 count:", len(re.findall(r'<h1', h)))
print("  H2 count:", len(re.findall(r'<h2', h)))
print("  H3 count:", len(re.findall(r'<h3', h)))
print("  'Swifts AI' mentions on homepage:", h.count('Swifts AI'))
print("  AI mentions (word):", len(re.findall(r'\bAI\b', h)))
pre = re.findall(r'<link rel="preload"[^>]*>', h)
print("  font preloads:", len(pre))
for p in pre: print("    ", re.search(r'href="[^"]*/([^/"]+)"', p).group(1))
PY
