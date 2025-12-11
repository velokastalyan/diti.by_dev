#!/bin/bash
docker cp /Users/mac/Downloads/База\ diti.by\ 01.12.25/diti.by/includes/php/classes/custom/CCategoriesPage.php ditiby-web-1:/var/www/html/includes/php/classes/custom/CCategoriesPage.php
docker cp /Users/mac/Downloads/База\ diti.by\ 01.12.25/diti.by/includes/php/classes/custom/CProductsPage.php ditiby-web-1:/var/www/html/includes/php/classes/custom/CProductsPage.php
docker exec ditiby-web-1 chown www-data:www-data /var/www/html/includes/php/classes/custom/CCategoriesPage.php
docker exec ditiby-web-1 chown www-data:www-data /var/www/html/includes/php/classes/custom/CProductsPage.php
echo "Files copied and permissions updated."
