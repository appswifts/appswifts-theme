#!/usr/bin/env bash
# Provision the plugin-free WordPress stack on appswifts-vps.
# Idempotent: safe to re-run. Does not touch existing vhosts or containers.
set -euo pipefail

DOMAIN=new.appswifts.space
ROOT=/opt/appswifts-wp
PORT=8083

echo "== 1. directories =="
sudo mkdir -p "$ROOT/html" /var/www/letsencrypt
sudo chown -R deploy-admin:deploy-admin "$ROOT"

echo "== 2. secrets (generated once, reused on re-run) =="
if [ ! -f "$ROOT/.env" ]; then
  RP=$(openssl rand -base64 24 | tr -d '/+=' | head -c 24)
  UP=$(openssl rand -base64 24 | tr -d '/+=' | head -c 24)
  printf 'MYSQL_ROOT_PASSWORD=%s\nMYSQL_PASSWORD=%s\n' "$RP" "$UP" | sudo tee "$ROOT/.env" >/dev/null
  sudo chmod 600 "$ROOT/.env"
  echo "   generated $ROOT/.env"
else
  echo "   reusing existing $ROOT/.env"
fi

echo "== 3. compose up =="
cd "$ROOT"
sudo docker compose -p appswifts-wp up -d

echo "== 4. wait for wordpress =="
for i in $(seq 1 60); do
  if curl -sf -o /dev/null "http://127.0.0.1:$PORT/wp-login.php"; then
    echo "   wordpress responding after ${i}s"
    break
  fi
  sleep 2
  [ "$i" = 60 ] && { echo "   FAILED: wordpress never came up"; exit 1; }
done

echo "== 5. nginx vhost =="
sudo tee /etc/nginx/sites-available/$DOMAIN.conf >/dev/null <<NGINX
server {
    listen 80;
    listen [::]:80;
    server_name $DOMAIN;
    location ^~ /.well-known/acme-challenge/ { root /var/www/letsencrypt; }
    location / { return 301 https://\$host\$request_uri; }
}
NGINX
sudo ln -sf /etc/nginx/sites-available/$DOMAIN.conf /etc/nginx/sites-enabled/$DOMAIN.conf
sudo nginx -t && sudo systemctl reload nginx

echo "== 6. certificate (first issuance) =="
if [ ! -d "/etc/letsencrypt/live/$DOMAIN" ]; then
  sudo certbot certonly --webroot -w /var/www/letsencrypt -d "$DOMAIN" \
       --non-interactive --agree-tos --email appswifts@gmail.com --keep-until-expiring
else
  echo "   cert already exists"
fi

echo "== 7. full vhost with TLS + proxy =="
sudo tee /etc/nginx/sites-available/$DOMAIN.conf >/dev/null <<NGINX
server {
    listen 80;
    listen [::]:80;
    server_name $DOMAIN;
    location ^~ /.well-known/acme-challenge/ { root /var/www/letsencrypt; }
    location / { return 301 https://\$host\$request_uri; }
}

server {
    listen 443 ssl;
    listen [::]:443 ssl;
    http2 on;
    server_name $DOMAIN;

    ssl_certificate     /etc/letsencrypt/live/$DOMAIN/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/$DOMAIN/privkey.pem;
    include /etc/letsencrypt/options-ssl-nginx.conf;
    ssl_dhparam /etc/letsencrypt/ssl-dhparams.pem;

    client_max_body_size 32m;
    gzip_comp_level 3;
    gzip_min_length 1024;
    gzip_types text/plain text/css application/json application/javascript text/javascript image/svg+xml;
    brotli_comp_level 4;
    brotli_types text/plain text/css application/json application/javascript text/javascript image/svg+xml;

    add_header Strict-Transport-Security "max-age=31536000" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header Referrer-Policy "strict-origin-when-cross-origin" always;

    location / {
        proxy_pass http://127.0.0.1:$PORT;
        proxy_http_version 1.1;
        proxy_set_header Upgrade \$http_upgrade;
        proxy_set_header Connection 'upgrade';
        proxy_set_header Host \$host;
        proxy_set_header X-Real-IP \$remote_addr;
        proxy_set_header X-Forwarded-For \$proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto \$scheme;
        proxy_set_header X-Forwarded-Host \$host;
        proxy_cache_bypass \$http_upgrade;
        proxy_read_timeout 120s;
    }
}
NGINX
sudo nginx -t && sudo systemctl reload nginx

echo "== 8. verify =="
echo -n "   local  : "; curl -s -o /dev/null -w '%{http_code}\n' "http://127.0.0.1:$PORT/"
echo -n "   https  : "; curl -s -o /dev/null -w '%{http_code}\n' "https://$DOMAIN/"
sudo docker ps --filter name=aswp --format '   {{.Names}} | {{.Status}}'
echo "DONE"
