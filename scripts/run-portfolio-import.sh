#!/usr/bin/env bash
# Import the 22 portfolio projects into the new site.
#
#   ./run-portfolio-import.sh              # dry run: validates, writes nothing
#   ./run-portfolio-import.sh --commit     # writes
#
# Idempotent: re-running --commit skips projects whose slug already exists.
set -euo pipefail

HOST=appswifts-vps
MODE="${1:-}"

echo "=== 1. stage images + plan into the container ==="
tar czf /tmp/portfolio.tgz -C /tmp/portfolio-stage .
cat /tmp/portfolio.tgz | ssh "$HOST" "
  set -e
  sudo rm -rf /tmp/portfolio && sudo mkdir -p /tmp/portfolio
  sudo tar xzf - -C /tmp/portfolio
  sudo docker exec aswp-wp rm -rf /tmp/import
  sudo docker cp /tmp/portfolio aswp-wp:/tmp/import
  echo -n '  files in container: '
  sudo docker exec aswp-wp sh -c 'ls /tmp/import | wc -l'
"

echo
echo "=== 2. install the importer ==="
cat /opt/data/scripts/appswifts-wp/import-portfolio.php | ssh "$HOST" "
  set -e
  sudo tee /tmp/portfolio/import-portfolio.php >/dev/null
  sudo docker cp /tmp/portfolio/import-portfolio.php aswp-wp:/tmp/import-portfolio.php
"

echo
echo "=== 3. run ==="
if [ "$MODE" = "--commit" ]; then
  echo "  COMMITTING"
  ENVV=""
else
  echo "  DRY RUN (pass --commit to write)"
  ENVV="-e DRY_RUN=1"
fi

ssh "$HOST" "sudo docker exec -u www-data $ENVV aswp-wp wp eval-file /tmp/import-portfolio.php --skip-plugins" 2>&1 | tail -40
