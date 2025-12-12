<?php
require_once('config/_config.inc.php');
require_once(FUNCTION_PATH . 'functions.php');
require_once(BASE_CLASSES_PATH . 'application.php');

define('OBJECT_ACTIVE', 1);
define('OBJECT_NOT_ACTIVE', 0);
define('OBJECT_SUSPENDED', 2);

define('TH_IMAGE_WIDTH', 150);
define('TH_IMAGE_HEIGHT', 225);

define('MD_IMAGE_WIDTH', 250);
define('MD_IMAGE_HEIGHT', 375);

define('OR_IMAGE_WIDTH', 60);
define('OR_IMAGE_HEIGHT', 90);


define('RECORDSET_FIRST_ITEM', '- Select from the list -');

class CApp extends CApplication
{
    function CApp()
    {
		//$this->locale = 'ru_RU';
		
    	//$this->codepage = 'UTF-8';
        //setlocale(LC_ALL, ((!isset($_SERVER['WINDIR'])) ? sprintf('%1$s.%2$s', $this->locale, $this->codepage) : ''));	
        parent::CApplication();
        
        $this->Modules['Images'] = array(
			'ClassName' => 'CImages',
            'ClassPath' => CUSTOM_CLASSES_PATH . 'components/images.php',
            'Visual'=>'0',
            'Title' => 'Images'
        );
        $this->Modules['Inputs'] = array(
			'ClassName' => 'CInputs',
            'ClassPath' => CUSTOM_CLASSES_PATH . 'components/inputs.php',
            'Visual'=>'0',
            'AjaxVisible' => true,
            'Title' => 'Inputs'
        );
        $this->Modules['AjaxValidator'] = array(
			'ClassName' => 'CAjaxValidator',
            'ClassPath' => CUSTOM_CLASSES_PATH . 'components/ajaxvalidator.php',
            'Visual'=>'0',
            'AjaxVisible' => true,
            'Title' => 'AjaxValidator'
        );
		$this->Modules['Pages'] = array(
			'ClassName' => 'CPages',
			'ClassPath' => CUSTOM_CLASSES_PATH . 'components/pages.php',
			'Visual'=> '0',
			'AjaxVisible' => true,
			'Title' => 'Pages'
		);
		$this->Modules['Categories'] = array(
			'ClassName' => 'CCategories',
			'ClassPath' => CUSTOM_CLASSES_PATH . 'components/categories.php',
			'Visual'=>'0',
			'Title' => 'Categories'
		);
		$this->Modules['Brands'] = array(
			'ClassName' => 'CBrands',
			'ClassPath' => CUSTOM_CLASSES_PATH . 'components/brands.php',
			'Visual'=>'0',
			'Title' => 'Brands'
		);
		$this->Modules['Products'] = array(
			'ClassName' => 'CProducts',
			'ClassPath' => CUSTOM_CLASSES_PATH . 'components/products.php',
			'Visual'=>'0',
			'AjaxVisible' => true,
			'Title' => 'Products'
		);
		$this->Modules['Comments'] = array(
			'ClassName' => 'CComments',
			'ClassPath' => CUSTOM_CLASSES_PATH . 'components/comments.php',
			'Visual'=>'0',
			'Title' => 'Comments'
		);
		$this->Modules['Banners'] = array(
			'ClassName' => 'CBanners',
			'ClassPath' => CUSTOM_CLASSES_PATH . 'components/banners.php',
			'Visual'=>'0',
			'Title' => 'Banners'
		);
		$this->Modules['Videos'] = array(
			'ClassName' => 'CVideos',
			'ClassPath' => CUSTOM_CLASSES_PATH . 'components/videos.php',
			'Visual'=>'0',
			'Title' => 'Videos'
		);
		$this->Modules['Articles'] = array(
			'ClassName' => 'CArticles',
			'ClassPath' => CUSTOM_CLASSES_PATH . 'components/articles.php',
			'Visual'=>'0',
			'Title' => 'Articles'
		);
        $this->Modules['Service'] = array(
            'ClassName' => 'CService',
            'ClassPath' => CUSTOM_CLASSES_PATH . 'components/service.php',
            'Visual'=>'0',
            'Title' => 'Service'
        );
		$this->Modules['Consultation'] = array(
			'ClassName' => 'CConsultation',
			'ClassPath' => CUSTOM_CLASSES_PATH . 'components/consultation.php',
			'Visual'=>'0',
			'Title' => 'Consultation'
		);
        $this->Modules['Distribution'] = array(
            'ClassName' => 'CDistribution',
            'ClassPath' => CUSTOM_CLASSES_PATH . 'components/distribution.php',
            'Visual'=> '0',
            'Title' => 'Distribution'
        );
		$this->Modules['Orders'] = array(
			'ClassName' => 'COrders',
			'ClassPath' => CUSTOM_CLASSES_PATH . 'components/orders.php',
			'Visual'=> '0',
			'Title' => 'Orders'
		);
        $this->Modules['Ajax'] = array(
            'ClassName' => 'CAjax',
            'ClassPath' => CUSTOM_CLASSES_PATH . 'components/ajax.php',
            'Visual'=>'0',
            'AjaxVisible' => true,
            'Title' => 'Ajax'
        );
        $this->Modules['Menus'] = array(
			'ClassName' => 'CMenus',
			'ClassPath' => CUSTOM_CLASSES_PATH . 'components/menus.php',
			'Visual'=> '0',
			'AjaxVisible' => true,
			'Title' => 'Menus'
		);
   	}
    
    function on_page_init() {
        if (!parent::on_page_init())
                return false;
        global $DebugLevel;
        global $SiteName;

        $DebugLevel = 0;

            $client_ip = ( !empty($HTTP_SERVER_VARS['REMOTE_ADDR']) ) ? $HTTP_SERVER_VARS['REMOTE_ADDR'] : ( ( !empty($HTTP_ENV_VARS['REMOTE_ADDR']) ) ? $HTTP_ENV_VARS['REMOTE_ADDR'] : getenv('REMOTE_ADDR') );
            //$this->Session->session_notify($client_ip);

                $this->tv['copyright_year'] = date('Y');
                $this->tv['last_modified'] = gmdate('D, d M Y 00:00:00', time() - 24*60*60) . ' GMT';
                $this->tv['is_debug_mode'] = ($DebugLevel == 0);
                $this->tv['site_name'] = $SiteName;

                return true;
    }
}

function dev_log_error($message) {
    if (defined('DEV_ERROR_LOG_PATH')) {
        @error_log($message . "\n", 3, DEV_ERROR_LOG_PATH);
    } else {
        @error_log($message);
    }
}

function on_php_error($code, $message, $filename='', $linenumber=-1, $context=array()) {
    if (!IS_DEV_ENVIRONMENT) {
        return false;
    }

    $logMessage = sprintf('PHP Error [%s]: %s in %s on line %s', $code, $message, $filename, $linenumber);
    dev_log_error($logMessage);

    if (!headers_sent()) {
        echo '<pre style="color:#900">' . htmlspecialchars($logMessage) . '</pre>';
    }

    return false;
}

function on_exception($exception) {
    $logMessage = 'Uncaught exception ' . get_class($exception) . ': ' . $exception->getMessage() . ' in ' . $exception->getFile() . ' on line ' . $exception->getLine();
    if ($exception->getTraceAsString()) {
        $logMessage .= "\nStack trace:\n" . $exception->getTraceAsString();
    }

    if (IS_DEV_ENVIRONMENT) {
        dev_log_error($logMessage);
        if (!headers_sent()) {
            echo '<pre style="color:#900">' . nl2br(htmlspecialchars($logMessage)) . '</pre>';
        }
    } else {
        dev_log_error($logMessage);
    }
}

function on_php_shutdown() {
    $error = error_get_last();
    if ($error && in_array($error['type'], array(E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR, E_USER_ERROR))) {
        $logMessage = 'Fatal error: ' . $error['message'] . ' in ' . $error['file'] . ' on line ' . $error['line'];
        dev_log_error($logMessage);

        if (IS_DEV_ENVIRONMENT && !headers_sent()) {
            echo '<pre style="color:#900">' . htmlspecialchars($logMessage) . '</pre>';
        }
    }
}

@ob_start();
@set_error_handler('on_php_error');
@set_exception_handler('on_exception');
@register_shutdown_function('on_php_shutdown');
@session_start();
$GLOBALS['app'] = new CApp();
?>