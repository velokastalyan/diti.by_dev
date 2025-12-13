#!/usr/bin/env bash
set -euo pipefail

url=${1:-}

if [[ -z "${url}" ]]; then
    echo "Usage: $0 <category_page_url>" >&2
    exit 1
fi

page_content=$(curl -fsSL "${url}")

if echo "${page_content}" | grep -qiE "brand=|бренд"; then
    echo "Brand filter detected on ${url}"
    exit 0
fi

echo "Brand filter was not detected on ${url}" >&2
exit 2
