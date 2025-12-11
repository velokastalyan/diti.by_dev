#!/bin/bash
echo "🧨 НАЧИНАЕМ ПОЛНЫЙ СБРОС СИСТЕМЫ..."

# 1. Убиваем старый Git
rm -rf .git
rm -f install_workflow.sh fix_git.sh

# 2. Создаем правильный .gitignore
cat > .gitignore << 'EOF'
images/
pub/products/
pub/categories/
pub/banners/
pub/uploads/
pub/brands/
pub/articles/
backups/
*.sql
!schema_structure.sql
*.zip
*.tar.gz
*.rar
cache/
tmp/
*.log
.DS_Store
Thumbs.db
.vscode/
.idea/
EOF

# 3. Инициализируем чистый Git
git init
git add .
git commit -m "Initial Clean Commit: System Reset"

# 4. Создаем скрипты
cat > start << 'EOF'
#!/bin/bash
PLAN_FILE="docs/PROJECT_PLAN.md"
echo "🤖 Diti.by: Статус..."
if [ -n "$(git status --porcelain)" ]; then
  echo "⚠️  ЕСТЬ ИЗМЕНЕНИЯ! Сделай ./checkpoint"
  exit 1
fi
CURRENT=$(grep -m 1 "\- \[ \] \[TODO\]" "$PLAN_FILE")
if [ -n "$CURRENT" ]; then
    echo "🔥 В РАБОТЕ:"
    echo "$CURRENT"
else
    awk '{if ($0 ~ /- \[ \] \[NEW\]/ && !found) {sub(/\[NEW\]/, "[TODO]"); found=1} print}' "$PLAN_FILE" > "${PLAN_FILE}.tmp" && mv "${PLAN_FILE}.tmp" "$PLAN_FILE"
    NEW=$(grep -m 1 "\- \[ \] \[TODO\]" "$PLAN_FILE")
    if [ -n "$NEW" ]; then echo "🚀 НОВАЯ ЗАДАЧА:"; echo "$NEW"; else echo "🎉 Все задачи выполнены."; fi
fi
EOF

cat > task << 'EOF'
#!/bin/bash
if [ -z "$1" ]; then echo "⚠️ Ошибка. Пример: ./task \"текст\""; exit 1; fi
sed -i '' "/## 📅 Очередь (Backlog)/a\\
- [ ] [NEW] $1
" docs/PROJECT_PLAN.md
echo "✅ Добавлено в очередь."
./start
EOF

cat > done << 'EOF'
#!/bin/bash
PLAN_FILE="docs/PROJECT_PLAN.md"
if ! grep -q "\- \[ \] \[TODO\]" "$PLAN_FILE"; then echo "🤷‍♂️ Нет активных задач."; exit 1; fi
awk '{if ($0 ~ /- \[ \] \[TODO\]/ && !found) {sub(/- \[ \] \[TODO\]/, "- [x]"); found=1} print}' "$PLAN_FILE" > "${PLAN_FILE}.tmp" && mv "${PLAN_FILE}.tmp" "$PLAN_FILE"
echo "✅ Задача выполнена!"
./start
EOF

cat > checkpoint << 'EOF'
#!/bin/bash
if [ -z "$1" ]; then echo "⚠️ Введи комментарий!"; exit 1; fi
echo "☁️  GIT-Save (Только код)..."
docker exec -i $(docker ps -qf "name=db") mysqldump -u root -prootpassword --no-data user2160086_timistkas_sportmax > schema_structure.sql 2>/dev/null
git add .
git commit -m "$1"
[ -n "$(git remote -v)" ] && git push origin main
echo "✅ Код сохранен."
EOF

cat > snapshot << 'EOF'
#!/bin/bash
if [ -z "$1" ]; then NAME="auto"; else NAME=$(echo "$1" | tr ' ' '_'); fi
mkdir -p backups
TIMESTAMP=$(date +"%Y-%m-%d_%H-%M")
FILE="backups/${TIMESTAMP}__${NAME}.tar.gz"
echo "💿 Полный бэкап (Код + Фото + База)..."
docker exec -i $(docker ps -qf "name=db") mysqldump -u root -prootpassword user2160086_timistkas_sportmax > full.sql
tar --exclude='./backups' --exclude='./.git' --exclude='./cache' -czf "$FILE" .
rm full.sql
echo "✅ Сохранено локально: $FILE"
EOF

cat > restore << 'EOF'
#!/bin/bash
echo "⏪ ВОССТАНОВЛЕНИЕ"
PS3="Выбери копию: "
select f in backups/*.tar.gz; do
 [ -n "$f" ] && tar -xzf "$f" && break
done
if [ -f "full.sql" ]; then
 docker exec -i $(docker ps -qf "name=db") mysql -u root -prootpassword user2160086_timistkas_sportmax < full.sql
 rm full.sql
fi
rm -rf cache/*
echo "✅ Готово."
EOF

cat > save << 'EOF'
#!/bin/bash
OUT="GEM_KNOWLEDGE_BASE.md"
echo "# Project Diti.by" > $OUT
echo "## Config" >> $OUT; echo "\`\`\`php" >> $OUT; cat includes/config/_config.inc.php >> $OUT; echo "\`\`\`" >> $OUT
echo "## Plan" >> $OUT; cat docs/PROJECT_PLAN.md >> $OUT
echo "✅ Файл $OUT готов."
EOF

chmod +x start task done checkpoint snapshot restore save

echo "🎉 ПОЛНАЯ ПЕРЕУСТАНОВКА ЗАВЕРШЕНА!"