cat > GEM_KNOWLEDGE_BASE.md << 'EOF'
# 🧠 Knowledge Base: Project Diti.by
**Type:** Technical Reference (Hard Facts Only)
**Updated:** $(date)

## 🔐 CREDENTIALS & ACCESS
*Все пароли проверены и жестко зафиксированы в конфигурации.*

### 🗄️ Database (MySQL 5.7)
* **Internal Host (Docker):** `db`
* **External Host:** `127.0.0.1`
* **Port:** `3306`
* **Database Name:** `user2160086_timistkas_sportmax`
* **Root User:** `root`
* **Root Password:** `rootpassword` (✅ Verified)
* **App User (Legacy):** `sportuser`
* **App Password (Legacy):** `mZ7oL9sD4g`

### 🌍 Web Environment
* **Container Name:** `ditiby-web-1`
* **Local URL:** http://localhost:8091
* **Internal Web Root:** `/var/www/html`
* **External Project Root:** `./` (Current Directory)

---

## ⚙️ CONFIGURATION MAP
Где лежат настройки, которые управляют проектом:

1.  **DB Connection Constant:**
    * `includes/config/_config.inc.php` (Define `DB_USER`, `DB_PASSWORD`, etc.)
2.  **DB Initialization Logic:**
    * `includes/app.php` (Creates `mysqli` object)
    * `includes/php/classes/_db.php` (Legacy wrapper class)
3.  **Routing / Entry:**
    * `index.php` -> `includes/router.php`
4.  **Docker Config:**
    * `docker-compose.yml` (Defines services, ports, volumes)

---

## 🏗️ ARCHITECTURE & STRUCTURE
* **Backend:** PHP 5.6 (Native, No Framework).
* **Frontend:** Smarty-like templates + jQuery + HTML4/5.
* **Controllers:** Located in `includes/php/classes/`.
    * `CCategoriesPage.php`
    * `CProductsPage.php`
* **Logs:**
    * Apache (Internal): `/var/log/apache2/error.log`
    * PHP Errors: Output to stdout/stderr in Docker.

---

## ⚠️ KNOWN TECHNICAL CONSTRAINTS
1.  **Charset:** Database is `latin1`, Application expects `utf8`. Use `SET NAMES` if needed.
2.  **SQL Mode:** MySQL 5.7 Strict Mode is ON. Dates like `0000-00-00` will cause fatal errors.
3.  **Paths:** Image paths in DB are often hardcoded or relative to legacy roots.

---

## 🤖 AUTOMATION INTERFACE (CLI Tools)
Use these scripts in the terminal root. Do not run manual git commands.

* `./start`      -> Check status & current task.
* `./task "msg"` -> Add new item to Backlog (`docs/PROJECT_PLAN.md`).
* `./checkpoint` -> **GIT COMMIT & PUSH** (Saves state).
* `./snapshot`   -> Create local backup (tar.gz) excluding cache/media.
* `./restore`    -> Restore from backup.
* `./save`       -> Update this Knowledge Base file.
EOF

# Добавляем актуальное дерево файлов в конец (техническая структура)
echo -e "\n## 📂 FILE SYSTEM TREE" >> GEM_KNOWLEDGE_BASE.md
echo '```' >> GEM_KNOWLEDGE_BASE.md
if command -v tree &> /dev/null; then
    tree -I 'cache|images|pub|minify|chat|includes|lib|vendor|backups' -L 2 >> GEM_KNOWLEDGE_BASE.md
else
    find . -maxdepth 2 -not -path '*/.*' -not -path './cache*' -not -path './images*' -not -path './pub*' | sort >> GEM_KNOWLEDGE_BASE.md
fi
echo '```' >> GEM_KNOWLEDGE_BASE.md

echo "✅ GEM_KNOWLEDGE_BASE.md обновлен. Теперь это чистый технический паспорт."