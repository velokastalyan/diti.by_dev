#!/bin/bash
echo "🔍 --- ЗАПУСК ПРИЕМОЧНОГО ТЕСТИРОВАНИЯ (WORKFLOW V2) ---"

FAIL=0

# Функция проверки существования и прав
check_tool() {
    if [ -f "$1" ]; then
        chmod +x "$1"
        echo "✅ [OK] Инструмент ./$1 установлен и готов."
    else
        echo "❌ [FAIL] Инструмент ./$1 отсутствует!"
        FAIL=1
    fi
}

# 1. Проверка инструментов по списку спецификации
echo "--- 1. Проверка инструментов ---"
check_tool "start"
check_tool "task"
check_tool "checkpoint"
check_tool "snapshot"
check_tool "restore"
check_tool "save"
check_tool "done"

# 2. Проверка Реструктуризации (папки docs)
echo "--- 2. Проверка структуры ---"
if [ -d "docs/01_Stabilization" ] && [ -d "docs/02_Redesign" ]; then
    echo "✅ [OK] Структура папок docs/ соответствует стандарту."
else
    echo "❌ [FAIL] Реструктуризация не выполнена (нет папок этапов)."
    FAIL=1
fi

# 3. Тест Бэклога (Task)
./task "Test_System_Check" > /dev/null
if grep -q "Test_System_Check" docs/PROJECT_PLAN.md; then
    echo "✅ [OK] ./task корректно пишет в Бэклог."
    # Чистка
    sed -i '' '/Test_System_Check/d' docs/PROJECT_PLAN.md 2>/dev/null || sed -i '/Test_System_Check/d' docs/PROJECT_PLAN.md
else
    echo "❌ [FAIL] ./task не работает."
    FAIL=1
fi

# 4. Тест Бэкапа (Snapshot)
./snapshot > /dev/null 2>&1
if ls backups/*.tar.gz 1> /dev/null 2>&1; then
    echo "✅ [OK] ./snapshot создает архивы."
else
    echo "❌ [FAIL] ./snapshot не создал файл."
    FAIL=1
fi

if [ $FAIL -eq 1 ]; then
    echo "⛔️ ТЕСТЫ ПРОВАЛЕНЫ. Проверьте установку."
    exit 1
fi

echo ""
echo "🎉 АВТОМАТИЗАЦИЯ ПРИНЯТА. ПЕРЕХОД К ДИАГНОСТИКЕ БД..."
echo "-----------------------------------------------------"

# 5. Диагностика БД (PHP)
cat > db_diagnose.php << 'PHP'
<?php
// Эмуляция окружения
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "1. Чтение конфигурации...\n";
$config_file = 'includes/config/_config.inc.php';

if (!file_exists($config_file)) {
    die("❌ ФАТАЛЬНО: Файл конфига $config_file не найден.\n");
}
require($config_file);

echo "   Хост: " . DB_SERVER . "\n";
echo "   Пользователь: " . DB_USER . "\n";
echo "   База: " . DB_DATABASE . "\n";

echo "2. Попытка соединения (MySQLi)...\n";
$link = @mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE, intval(DB_PORT));

if (!$link) {
    echo "❌ ОШИБКА ПОДКЛЮЧЕНИЯ: " . mysqli_connect_error() . "\n";
    echo "   Код ошибки: " . mysqli_connect_errno() . "\n";
    exit(1);
}

echo "✅ УСПЕХ: Соединение установлено.\n";
echo "   Версия сервера: " . mysqli_get_server_info($link) . "\n";

echo "3. Проверка кодировки...\n";
if (mysqli_set_charset($link, "utf8")) {
    echo "✅ Кодировка UTF-8 установлена.\n";
} else {
    echo "⚠️ Ошибка установки кодировки: " . mysqli_error($link) . "\n";
}

echo "4. Проверка данных (Таблица product)...\n";
$res = mysqli_query($link, "SELECT count(*) FROM product");
if ($res) {
    $row = mysqli_fetch_array($res);
    echo "✅ Таблица 'product' доступна. Товаров: " . $row[0] . "\n";
} else {
    echo "⚠️ Ошибка запроса (возможно, неверная структура): " . mysqli_error($link) . "\n";
}

mysqli_close($link);
PHP

php db_diagnose.php
rm db_diagnose.php
