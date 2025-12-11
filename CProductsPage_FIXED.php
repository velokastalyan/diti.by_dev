echo "--- 1. Ищем потерянную функцию ---"
docker exec -i ditiby-web-1 grep -r "function get_categories_list" /var/www/html/

echo -e "\n--- 2. Ищем файл класса CCategories ---"
FILE=$(docker exec -i ditiby-web-1 grep -r -l "class CCategories" /var/www/html/ | head -n 1)
echo "Нашел файл: $FILE"

echo -e "\n--- 3. Список доступных функций в этом файле ---"
docker exec -i ditiby-web-1 grep "function" "$FILE"
