#!/usr/bin/env bash
set -euo pipefail
ssh appswifts-vps '
set -e
W="sudo docker exec -u www-data aswp-wp wp"
ID=12
$W theme mod set custom_logo "$ID" >/dev/null 2>&1 && echo "custom_logo: set"
$W option update site_icon "$ID" --quiet && echo "site_icon: set"
$W post get "$ID" --field=guid 2>/dev/null | sed "s/^/logo url: /"
echo "--- front page header markup ---"
curl -s -m 20 https://new.appswifts.space/ | grep -oE "<img[^>]*logo[^>]*>" | head -2
'
