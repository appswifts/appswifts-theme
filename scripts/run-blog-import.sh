#!/usr/bin/env bash
# Import the original site's blog posts into the new site.
#
#   ./run-blog-import.sh            # dry run: validates, writes nothing
#   ./run-blog-import.sh --commit   # writes
set -euo pipefail

HOST=appswifts-vps
MODE="${1:-}"
SRC=/opt/data/scripts/appswifts-wp

echo "=== 1. stage the plan into the container ==="
cat /opt/data/migration/blog-plan.json | ssh "$HOST" 'sudo tee /tmp/blog-plan.json >/dev/null && sudo docker exec aswp-wp mkdir -p /tmp/import && sudo docker cp /tmp/blog-plan.json aswp-wp:/tmp/import/blog-plan.json >/dev/null && echo "  plan staged"'

echo "=== 2. install the importer ==="
cat "$SRC/import-blog.php" | ssh "$HOST" 'sudo tee /tmp/import-blog.php >/dev/null && sudo docker cp /tmp/import-blog.php aswp-wp:/tmp/import-blog.php >/dev/null'

echo
echo "=== 3. run ==="
if [ "$MODE" = "--commit" ]; then
  echo "  COMMITTING"
  ENVV=""
else
  echo "  DRY RUN (pass --commit to write)"
  ENVV="-e DRY_RUN=1"
fi

ssh "$HOST" "sudo docker exec -u www-data $ENVV aswp-wp wp eval-file /tmp/import-blog.php --skip-plugins" 2>&1 | grep -v "^Warning: unlink" | tail -30
