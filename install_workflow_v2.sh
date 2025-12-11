#!/bin/bash

echo "🚀 Начинаем установку Workflow V2 для Diti.by..."

# 1. Структура папок документации
echo "📂 Создаем структуру docs/..."
mkdir -p docs/01_Stabilization
mkdir -p docs/02_Redesign
mkdir -p docs/03_Optimization
mkdir -p docs/archive
mkdir -p backups
mkdir -p .vscode

# 2. Иерархический План Проекта
echo "📝 Генерируем иерархический PROJECT_PLAN.md..."
cat > docs/PROJECT_PLAN.md << 'PLAN'
# 🏗 Project Plan: Diti.by Modernization

## 🏁 Этап 1: Стабилизация (Legacy Fixes)
- [x] Настройка Docker окружения
- [x] Очистка Git от мусора (картинки, кэш)
- [ ] **Восстановление Базового Функционала**
    - [ ] [IN PROGRESS] Диагностика подключения к БД (index.php, includes/app.php)
    - [ ] Исправление путей к картинкам категорий
    - [ ] Исправление SQL ошибок (MySQL 5.7 strict mode)
- [ ] **Подготовка к Редизайну**
    - [ ] Вынос CSS в modern.css
    - [ ] Чистка HTML от табличной верстки (где критично)

## 🎨 Этап 2: Редизайн (UI/UX)
- [ ] **Шапка (Header)**
    - [ ] [TODO] Верстка Flexbox-контейнера
    - [ ] [TODO] Мобильное меню (Гамбургер)
    - [ ] [TODO] Поиск и Корзина
- [ ] **Футер (Footer)**
    - [ ] Верстка сетки ссылок
    - [ ] Копирайты и соцсети
- [ ] **Главная страница**
    - [ ] Сетка товаров (Grid)
    - [ ] Баннеры

## 🚀 Этап 3: Оптимизация
- [ ] Настройка кэширования
- [ ] SEO теги
- [ ] Google PageSpeed > 90

## 📥 Входящие (Бэклог идей)
- [NEW] Поправить цвет кнопки купить
PLAN

# Линкуем план в корень для удобства
ln -sf docs/PROJECT_PLAN.md PROJECT_PLAN.md

# 3. Скрипт ./start
echo "⚙️ Обновляем ./start..."
cat > start << 'SCRIPT'
#!/bin/bash
clear
echo "========================================"
echo "   🚀 DITI.BY DEV ENVIRONMENT V2"
echo "========================================"
git status -s
echo "----------------------------------------"
echo "🎯 ТЕКУЩАЯ ЗАДАЧА (из плана):"
grep "\[IN PROGRESS\]" docs/PROJECT_PLAN.md | sed 's/^[ \t]*//'
echo "----------------------------------------"
echo "Доступные команды:"
echo " ./task \"текст\"  - Добавить задачу"
echo " ./done          - Закрыть текущую задачу"
echo " ./checkpoint    - Сохранить код в Git"
echo " ./snapshot      - Полный бэкап (Локально)"
echo " ./save          - Собрать знания для AI"
SCRIPT
chmod +x start

# 4. Скрипт ./task
echo "⚙️ Обновляем ./task..."
cat > task << 'SCRIPT'
#!/bin/bash
if [ -z "$1" ]; then
  echo "❌ Ошибка: Введите текст задачи. Пример: ./task \"Исправить логотип\""
  exit 1
fi
echo "- [NEW] $1" >> docs/PROJECT_PLAN.md
echo "✅ Задача добавлена в 'Входящие' (внизу PROJECT_PLAN.md)"
echo "💡 Совет: Перетащи её в нужный этап вручную."
SCRIPT
chmod +x task

# 5. Скрипт ./checkpoint
echo "⚙️ Обновляем ./checkpoint..."
cat > checkpoint << 'SCRIPT'
#!/bin/bash
MSG="$1"
if [ -z "$MSG" ]; then
  MSG="WIP: Update code and docs"
fi
git add .
git commit -m "$MSG"
# git push origin main # Раскомментировать если подключен удаленный репо
echo "✅ Код и Документация сохранены в Git!"
SCRIPT
chmod +x checkpoint

# 6. Скрипт ./snapshot (Бэкап)
echo "⚙️ Обновляем ./snapshot..."
cat > snapshot << 'SCRIPT'
#!/bin/bash
NAME="backup_$(date +%Y%m%d_%H%M%S)"
echo "📦 Создаем локальный снэпшот: $NAME..."

# Дамп базы (если контейнер запущен)
docker exec -i $(docker ps -qf "name=db") mysqldump -u root -prootpassword user2160086_timistkas_sportmax > dump_temp.sql 2>/dev/null

# Архивация (исключая тяжелые папки node_modules и т.д., если появятся)
tar -czf backups/$NAME.tar.gz --exclude='backups' --exclude='.git' .

rm dump_temp.sql
echo "✅ Снэпшот сохранен в backups/$NAME.tar.gz"
SCRIPT
chmod +x snapshot

# 7. Скрипт ./restore
echo "⚙️ Обновляем ./restore..."
cat > restore << 'SCRIPT'
#!/bin/bash
echo "⚠️  ВОССТАНОВЛЕНИЕ ИЗ БЭКАПА"
PS3="Выбери файл восстановления: "
select filename in backups/*.tar.gz; do
    if [ -n "$filename" ]; then
        echo "⏳ Восстанавливаем из $filename..."
        tar -xzf "$filename"
        
        if [ -f "dump_temp.sql" ]; then
             echo "🔄 Восстанавливаем БД..."
             docker exec -i $(docker ps -qf "name=db") mysql -u root -prootpassword user2160086_timistkas_sportmax < dump_temp.sql
             rm dump_temp.sql
        fi
        echo "✅ Система восстановлена!"
        break
    else
        echo "❌ Неверный выбор."
    fi
done
SCRIPT
chmod +x restore

# 8. Скрипт ./done
echo "⚙️ Обновляем ./done..."
cat > done << 'SCRIPT'
#!/bin/bash
# Находит первую задачу IN PROGRESS и меняет на DONE
sed -i '0,/\[IN PROGRESS\]/s//\[x\]/' docs/PROJECT_PLAN.md
echo "🎉 Отлично! Задача помечена выполненной."
# Ищет следующую TODO и делает IN PROGRESS
sed -i '0,/\[TODO\]/s//\[IN PROGRESS\]/' docs/PROJECT_PLAN.md
NEXT=$(grep "\[IN PROGRESS\]" docs/PROJECT_PLAN.md | sed 's/^[ \t]*//')
echo "👉 Твоя следующая задача: $NEXT"
SCRIPT
chmod +x done

# 9. Скрипт ./save (Генератор памяти для AI)
echo "⚙️ Обновляем ./save..."
cat > save << 'SCRIPT'
#!/bin/bash
OUT="GEM_KNOWLEDGE_BASE.md"
echo "# 🧠 Knowledge Base: Project Diti.by" > $OUT
echo "Updated: $(date)" >> $OUT
echo "" >> $OUT
echo "## 📋 Technical Passport" >> $OUT
echo "- **Stack:** Docker, PHP 5.6/7.4, MySQL 5.7" >> $OUT
echo "" >> $OUT
echo "## 📂 Structure (Docs)" >> $OUT
tree docs >> $OUT
echo "" >> $OUT
echo "## 📝 Current Plan & Status" >> $OUT
cat docs/PROJECT_PLAN.md >> $OUT
echo "" >> $OUT
echo "## 🔍 Key Files Content" >> $OUT
echo '```php' >> $OUT
echo "// index.php" >> $OUT
head -n 50 index.php >> $OUT
echo '```' >> $OUT
echo "✅ Файл $OUT обновлен. Скачай его перед сменой чата!"
SCRIPT
chmod +x save

echo "🎉 УСТАНОВКА ЗАВЕРШЕНА!"
echo "Попробуй набрать ./start"
