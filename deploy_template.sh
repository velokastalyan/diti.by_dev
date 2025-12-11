#!/bin/bash
docker cp /Users/mac/Downloads/База\ diti.by\ 01.12.25/diti.by/includes/templates/custom/productspage.tpl ditiby-web-1:/var/www/html/includes/templates/custom/productspage.tpl
docker exec ditiby-web-1 chown www-data:www-data /var/www/html/includes/templates/custom/productspage.tpl
echo "Template copied."
