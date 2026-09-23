#!/usr/bin/env bash
# Deploy the theme to the WordPress host. Override with env vars:
#   WP_HOST       ssh host alias                 (default: appswifts-vps)
#   WP_THEME_DIR  theme path inside that host    (default below)
#   WP_CONTAINER  wp-cli container name          (default: aswp-wp)
set -euo pipefail

WP_HOST="${WP_HOST:-appswifts-vps}"
WP_THEME_DIR="${WP_THEME_DIR:-/opt/appswifts-wp/html/wp-content/themes/appswifts}"
WP_CONTAINER="${WP_CONTAINER:-aswp-wp}"

here="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
cd "$here"

tar czf /tmp/appswifts-theme.tgz theme

cat /tmp/appswifts-theme.tgz | ssh "$WP_HOST" "
  set -e
  rm -rf /tmp/tt && mkdir -p /tmp/tt && tar xzf - -C /tmp/tt
  sudo rm -rf $WP_THEME_DIR
  sudo cp -r /tmp/tt/theme $WP_THEME_DIR
  # must match the web user or media uploads fail
  sudo chown -R www-data:www-data $WP_THEME_DIR
  sudo find $WP_THEME_DIR -type d -exec chmod 755 {} +
  echo 'php files deployed:'
  sudo find $WP_THEME_DIR -name '*.php' | wc -l
  sudo docker exec -u www-data $WP_CONTAINER wp rewrite flush --hard --quiet 2>&1 | tail -1 || true
"
echo "deployed to $WP_HOST:$WP_THEME_DIR"
