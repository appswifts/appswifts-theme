#!/usr/bin/env bash
set -euo pipefail
HOST=appswifts-vps

echo "== 1. deploy theme =="
cd /opt/data/scripts/appswifts-wp
tar czf /tmp/theme.tgz theme
cat /tmp/theme.tgz | ssh "$HOST" '
  set -e
  rm -rf /tmp/th && mkdir -p /tmp/th && tar xzf - -C /tmp/th
  T=/opt/appswifts-wp/html/wp-content/themes/appswifts
  sudo rm -rf $T
  sudo cp -r /tmp/th/theme $T
  sudo chown -R 33:33 $T
  echo "   theme files: $(sudo find $T -type f | wc -l)"
'

echo "== 2. fetch the horizontal wordmark =="
ssh "$HOST" '
  set -e
  W="sudo docker exec -u www-data aswp-wp wp"
  T=/opt/appswifts-wp/html/wp-content/themes/appswifts

  curl -sSL -o /tmp/wordmark.png "https://appswifts.com/wp-content/uploads/2025/05/APPSWIFTS-1.png"
  echo "   downloaded: $(stat -c%s /tmp/wordmark.png) bytes"
  file /tmp/wordmark.png | sed "s/^/   /"

  sudo docker cp /tmp/wordmark.png aswp-wp:/tmp/wordmark.png
  sudo docker exec aswp-wp chown www-data:www-data /tmp/wordmark.png

  ID=$($W media import /tmp/wordmark.png --title="AppSwifts wordmark" --porcelain 2>/dev/null || echo "")
  echo "   wordmark attachment id: ${ID:-FAILED}"

  if [ -n "$ID" ]; then
    # horizontal wordmark in the header, square mark stays as the favicon
    $W theme mod set custom_logo "$ID" >/dev/null 2>&1 && echo "   custom_logo -> wordmark"
    $W post get "$ID" --field=guid 2>/dev/null | sed "s/^/   url: /"
  fi
  $W option get site_icon | sed "s/^/   site_icon (favicon): /"
'

echo "== 3. verify header markup =="
sleep 2
curl -s -m 25 https://new.appswifts.space/ -o /tmp/hp3.html
python3 - <<'PY'
import re
h = open('/tmp/hp3.html', encoding='utf-8', errors='replace').read()
m = re.search(r'<header class="site-header".*?</header>', h, re.S)
if m:
    seg = m.group(0)
    print("   nested anchors:", "YES (BUG)" if seg.count('<a') > seg.count('</a>') or '<a' in re.search(r'<a[^>]*>\s*<a', seg).group(0) if re.search(r'<a[^>]*>\s*<a', seg) else "no")
    img = re.search(r'<img[^>]*class="custom-logo"[^>]*>', seg)
    print("   logo img:", (img.group(0)[:200] if img else "NOT FOUND"))
PY
echo "   css rule:"
curl -s -m 20 https://new.appswifts.space/wp-content/themes/appswifts/style.css | grep -A5 'img.custom-logo' | head -8 | sed 's/^/     /'
