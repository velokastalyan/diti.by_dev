#!/usr/bin/env bash
set -euo pipefail

ROOT_DIR="$(cd "$(dirname "$0")/.." && pwd)"
cd "$ROOT_DIR"

homepage_url="http://localhost:8091/"
image_url="http://localhost:8091/pub/products/17429/79x79/gornolyizhnye_krepleniya_armada_n_shift_mnc_10_black_120_2022_.jpg"

status_header() {
  echo
  echo "========== $1 =========="
}

status_header "docker compose ps"
docker compose ps

status_header "Last 200 lines of web logs"
docker compose logs --tail=200 web || true

status_header "apache2ctl -S"
docker compose exec -T web apache2ctl -S

status_header "apache2ctl -M | grep -E 'rewrite|php'"
docker compose exec -T web apache2ctl -M | grep -E 'rewrite|php'

status_header "Tail of Apache error log"
docker compose exec -T web bash -lc 'tail -n 120 /var/log/apache2/error.log'

status_header "Homepage headers"
curl -sI "$homepage_url" | head -n 5

homepage_status=$(curl -s -o /dev/null -w "%{http_code}" "$homepage_url")
if [[ "$homepage_status" =~ ^[45] ]]; then
  echo "Homepage returned error status $homepage_status" >&2
  exit 1
fi

status_header "Product image headers"
img_headers=$(curl -sI "$image_url")
printf "%s\n" "$img_headers" | head -n 15

img_status=$(printf "%s" "$img_headers" | awk 'NR==1{print $2}')
if [[ "$img_status" != "200" ]]; then
  echo "Product image returned status $img_status" >&2
  exit 1
fi

if ! printf "%s" "$img_headers" | grep -iq 'Content-Type: image/'; then
  echo "Product image does not advertise an image Content-Type" >&2
  exit 1
fi
