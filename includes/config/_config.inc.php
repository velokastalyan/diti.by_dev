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

$httpHostHeader = !empty($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
$serverPort = !empty($_SERVER['SERVER_PORT']) ? $_SERVER['SERVER_PORT'] : '';
$appEnv = getenv('APP_ENV');

@date_default_timezone_set('Europe/Minsk');

$isLocalHost = (stripos($httpHostHeader, 'localhost') !== false) || (stripos($httpHostHeader, '127.0.0.1') !== false);
$isDevPort = ($serverPort == '8091');
$isDevEnvironment = ($isLocalHost || $isDevPort || (is_string($appEnv) && strtolower($appEnv) === 'dev'));

$DebugLevel = $isDevEnvironment ? 255 : 0;
$SiteName = 'Sportmax Local';

// --- URL/port configuration ---
$requestScheme = !empty($_SERVER['REQUEST_SCHEME'])
        ? $_SERVER['REQUEST_SCHEME']
        : ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http');

$isHttps = ($requestScheme === 'https') || (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');

$hostWithPort = !empty($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';
$hostParts = explode(':', $hostWithPort, 2);
$hostName = $hostParts[0];
$resolvedPort = isset($hostParts[1])
        ? $hostParts[1]
        : (!empty($_SERVER['SERVER_PORT']) ? $_SERVER['SERVER_PORT'] : ($isHttps ? '443' : '80'));

// Only expose port in URL when it is non-standard for the current scheme.
$portForUrl = ((!$isHttps && $resolvedPort != '80') || ($isHttps && $resolvedPort != '443')) ? $resolvedPort : '';

$SiteHost = $hostName;
$SitePort = $portForUrl;
$SiteUrl = $SiteHost . ($SitePort ? ':' . $SitePort : '');
$HTTPSSiteUrl = $SiteUrl;
$HttpsSiteUrl = $HTTPSSiteUrl;

$RootPath = '/';
$ssl_root = $RootPath;
$Ssl_root = $ssl_root;

// Scheme/port names remain for backwards compatibility.
$HttpPort  = (!$isHttps ? $SitePort : '');
$HttpsPort = ($isHttps ? $SitePort : '');
$HttpName  = 'http';
$HttpsName = 'https';
$SHttpName = 'https';
$SHttpPort = $HttpsPort;

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

$ErrorLogPath = $FilePath . 'var/log/php_error.log';
$errorLogDir = dirname($ErrorLogPath);
if (!is_dir($errorLogDir)) {
    @mkdir($errorLogDir, 0777, true);
}

define('IS_DEV_ENVIRONMENT', $isDevEnvironment);
define('DEV_ERROR_LOG_PATH', $ErrorLogPath);

@ini_set('session.use_only_cookies', '1');
@ini_set('arg_separator.output', '&amp;');

if (IS_DEV_ENVIRONMENT) {
    @error_reporting(E_ALL | E_STRICT);
    @ini_set('display_errors', '1');
    @ini_set('log_errors', '1');
    @ini_set('html_errors', '1');
    @ini_set('error_log', DEV_ERROR_LOG_PATH);
} else {
    @error_reporting(0);
}
@set_time_limit(120);
?>
