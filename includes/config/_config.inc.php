<?php
// [DITI.BY LAB] Config Fix: Removed double port definition
/*
if (!(isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) || isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) {
    $redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    // header('Location: ' . $redirect); exit();
}
*/

function get_formatted_microtime() {
    list($usec, $sec) = explode(' ', microtime());
    return ($usec+$sec);
}
$_t1 = get_formatted_microtime();

$DebugLevel = 255;
$SiteName = 'Sportmax Local';

// --- ИСПРАВЛЕНИЕ: Указываем только хост, без порта ---
/**
 * URL/port configuration (Docker dev on localhost:8091)
 * This block replaces the old "ИСПРАВЛЕНИЕ" section above.
 */

 $requestScheme = !empty($_SERVER['REQUEST_SCHEME'])
 ? $_SERVER['REQUEST_SCHEME']
 : ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http');

$hostWithPort = !empty($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';

// Base site URL (includes trailing slash)
$SiteUrl      = $requestScheme . '://' . $hostWithPort . '/';
$HttpsSiteUrl = $SiteUrl;

$RootPath = '/';
$Ssl_root = '/';

// Don't force custom ports; rely on HTTP_HOST which уже содержит :8091.
$HttpPort  = '';
$HttpsPort = '';

$HttpName  = 'http';
$HttpsName = 'http';

$AdministratorEmail = 'info@diti.by';
$AdministratorName = 'sportmax';

define('PROD', '0');
define('MOD_REWRITE', false); 

// === НАСТРОЙКИ БАЗЫ ДАННЫХ ===
define('APPLICATION_DATABASE', 'mysql');
define('DB_SERVER', 'db'); 
define('DB_PORT', '3306');
define('DB_USER', 'root');
define('DB_PASSWORD', 'rootpassword');
define('DB_DATABASE', 'user2160086_timistkas_sportmax');
define('DB_PREFIX', '');

define('XHTML', '1');

$CUrlProxy = '';
$CUrlProxyUserName = '';
$CUrlProxyPassword = '';

$FilePath = $_SERVER["DOCUMENT_ROOT"];
if (substr($FilePath, -1) != "/") $FilePath .= "/";

$CSSPath = $RootPath . 'css/';
$JSPath = $RootPath . 'js/';
$ImagesPath = $RootPath . 'images/';

define('BR', '<br />');
define('REGISTRY_FILES_WEB', '_r/');
define('REGISTRY_FILES_STORAGE', $FilePath . '_r/');
define('REGISTRY_XML', $FilePath . 'includes/registry/');
define('FUNCTION_PATH', $FilePath . 'includes/php/functions/');
define('ROUTES_PATH', $FilePath . 'includes/routes/');
define('BASE_CLASSES_PATH', $FilePath . 'includes/php/classes/base/');
define('BASE_CONTROLS_PATH', $FilePath . 'includes/php/classes/base/controls/');
define('CUSTOM_CLASSES_PATH', $FilePath . 'includes/php/classes/custom/');
define('CUSTOM_CONTROLS_PATH', $FilePath . 'includes/php/classes/custom/controls/');
define('BASE_TEMPLATE_PATH', $FilePath . 'includes/templates/base/');
define('CUSTOM_TEMPLATE_PATH', $FilePath . 'includes/templates/custom/');
define('BASE_CONTROLS_TEMPLATE_PATH', $FilePath . 'includes/templates/base/controls/');
define('CUSTOM_CONTROLS_TEMPLATE_PATH', $FilePath . 'includes/templates/custom/controls/');
define('ROOT', $FilePath);

@ini_set('session.use_only_cookies', '1');
@ini_set('arg_separator.output', '&amp;');

if ($DebugLevel) {
    @error_reporting(E_ALL);
    @ini_set('display_errors', '1');
} else {
    @error_reporting(0);
}
@set_time_limit(120);
?>
