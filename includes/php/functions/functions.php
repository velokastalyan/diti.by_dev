<?php
/**
 * @package Art-cms
 */
/**
 */

function responseToArr($arr)
{
	if (isset($arr->Rows) && is_array($arr->Rows)) {
		$resArr = array();
		foreach($arr->Rows as $key) {
			foreach($key as $val) {
				$resArr[] = $val;
			}
		}
		return $resArr;
	} else {
		return false;
	}
}

function dbQuery($sql, $qcache = true)
{
	$sql = trim($sql);
	if (empty($sql) || !is_string($sql)) {
		return false;
	}
	if ($qcache) {
		$rs = qCacheGet( $sql );
	} else
		$rs = $GLOBALS['app']->DataBase->select_custom_sql($sql);

	if ($rs != false && !$rs->eof()) {
		$rs = responseToArr($rs);
		return $rs;
	}
	return false;
}
/*dbSelect('user', 'id,login', 'id < 5', 'id DESC', 3)
**dbSELECT('user', array('id','login'), array('login' =>  'admin'), 'id DESC', 3);
**return: array(0 => array('id' => 1, 'login' => 'admin'), 1 => ...
*/
function dbSelect($table_name, $fields = array(), $cond = array(), $order = null, $limit = null, $qcache = true)
{
	if (!is_string($table_name) || empty($table_name)) {
		return false;
	}
	$where = null;
	if (!empty($cond)) {
		if (!is_array($cond)) {
			$where = 'WHERE '. dbEscape($cond);
		} else {
			$where = 'WHERE 1=1';
			foreach($cond as $key => $val) {
				if (!is_array($val)) {
					$where .= ' AND `'. $key .'` = "'. $val .'"';
				} else {
					$where .= ' AND `'. $key .'` IN("'. implode('","',$val) .'")';
				}
			}
		}
	}
	if (!empty($fields)) {
		if (is_array($fields)) {
			$fields = implode(',', $fields);
		}
	} else {
		$fields = '*';
	}

	if (!empty($order)) {
		$order = ' ORDER BY ' . $order;
	}

	if (!empty($limit)) {
		$limit = ' LIMIT '. $limit;
	}

	$sql = "SELECT ". $fields ." FROM %prefix%". $table_name ." ". $where . $order . $limit;

	if ($qcache)
		$rs = qCacheGet( $sql );
	else
		$rs = $GLOBALS['app']->DataBase->select_custom_sql( $sql );

	if ($rs && !$rs->eof()) {
		$rs = responseToArr($rs);
		return $rs;
	}
	return false;
}
//dbGet('user', 'id', 'login = "admin"')
//dbGet('user', 'id', array('login' => 'admin'));
//return: 1
function dbGet($table_name, $fields = array(), $cond = array(), $qcache = true)
{
	if (!is_string($table_name) || empty($table_name)) {
		return false;
	}
	$where = 'WHERE 1 = 1';
	if (!empty($cond)) {
		if (!is_array($cond)) {
			$where = 'WHERE '. $cond;
		} else {
			foreach($cond as $key => $val) {
				$where .= ' AND `'. $key .'`="'. $val .'"';
			}
		}
	}
	if (!empty($fields)) {
		if (is_array($fields)) {
			$fields = implode(',', $fields);
		}
	} else {
		$fields = '*';
	}

	$sql = "SELECT ". $fields ." FROM %prefix%". $table_name ." ". $where . " LIMIT 1";

	if ($qcache)
		$rs = qCacheGet($sql);
	else
		$rs = $GLOBALS['app']->DataBase->select_custom_sql( $sql );

	if ($rs && !$rs->eof()) {
		//recordset_to_vars($rs, $res);
		$res = responseToArr($rs);
		//array result
		if (sizeof($res[0]) > 1) {
			return $res[0];
		} else {
			//One result
			return $res[0][key($res[0])];
		}
	}
	return false;
}
function dbInsert($table_name, $fields)
{
	if (empty($table_name) || !is_string($table_name) || empty($fields) || !is_array($fields)) {
		return false;
	}

	$id = $GLOBALS['app']->DataBase->insert_sql($table_name, $fields);
	return $id;
}
function dbUpdate($table_name, $fields, $cond)
{
	if (empty($table_name) || !is_string($table_name) || empty($fields) || !is_array($fields)) {
		return false;
	}

	$id = $GLOBALS['app']->DataBase->update_sql($table_name, $fields, $cond);
	return $id;
}

function qCacheGet($sql)
{
	//$sql = trim($sql);
	$sql = str_replace("'", '"', $sql);

	if (!isset($GLOBALS['qcache'][$sql])) {
		$rs = $GLOBALS['app']->DataBase->select_custom_sql( $sql );
		$GLOBALS['qcache'][$sql] = $rs;
	} else {
		$rs = $GLOBALS['qcache'][$sql];
	}
	return $rs;
}

function dbDelete($table_name, $cond)
{
	if (!is_string($table_name) || empty($table_name) || empty($cond) || !is_array($cond)) {
		return false;
	}

	$count = $GLOBALS['app']->DataBase->delete_sql($table_name, $cond);
	return $count;
}

/*NAV MENU*/
function get_items_nav_menu($menu, $lang_id = null)
{
	$items = $GLOBALS['app']->get_module('Menus')->get_items_nav_menu($menu, $lang_id);
	return $items;
}
function get_nav_menu($menu, $lang_id = null)
{
	$items = $GLOBALS['app']->get_module('Menus')->get_nav_menu($menu, $lang_id);
	return $items;
}
function print_nav_menu($menu, $lang_id = null)
{
	echo get_nav_menu($menu, $lang_id);
}
/*NAV MENU ==END==*/

function get_option($option_name)
{
	$option_name = trim($option_name);
	if (!is_string($option_name) || empty($option_name)) {
		return false;
	}

	$option = new COptions($GLOBALS['app']);
	$option = $option->get_option($option_name);
	return $option;
}
function get_options($options_arr)
{
	if (empty($options_arr) || !is_array($options_arr)) {
		return false;
	}
	$options = new COptions( $GLOBALS['app'] );
	$options = $options->get_options($options_arr);
	return $options;
}

function system_die()
{
	global $DebugLevel;
	@header('Status: 500 Server Error');
	if (function_exists('debug_backtrace')){
		$db = debug_backtrace();
		$db_text = '';
		for ($i=sizeof($db); $i>0; $i--) $db_text .= '<nobr> on line <b>'.( (isset($db[$i-1]['line']))?($db[$i-1]['line']):('?') ).'</b> of file <b>'.( (isset($db[$i-1]['file']))?($db[$i-1]['file']):('?') ).'</b></nobr>'."<br />";
	} else $db_text = ' - debug backtrace is not available';
	if (func_num_args() > 0){
		$text = htmlspecialchars(strval(func_get_arg(0)));
		if (func_num_args()> 1) $text = '<b>' . htmlspecialchars(strval(func_get_arg(1))) . '</b>: '.$text;
	} else $text = 'Unnamed system error';
	if($DebugLevel == 255) echo '<p align="left"><b><font color="red">System error:</font></b> '.$text."<br />"."<br />".$db_text.'</p>';
	$GLOBALS['GlobalDebugInfo']->OutPut();
	die();
}

function regexp_escape($str)
{
        return preg_quote($str, '/');
}

/**
 * Normalize media URLs that may contain hardcoded domains.
 *
 * If a URL contains diti.by or localhost/127.0.0.1 domains, we strip the
 * scheme/host part and return a host-relative path so assets load correctly
 * on the current environment.
 */
function normalize_media_url($url)
{
        if (empty($url) || !is_string($url)) {
                return $url;
        }

        $parsed = @parse_url($url);

        if (!is_array($parsed)) {
                return $url;
        }

        $host = isset($parsed['host']) ? strtolower($parsed['host']) : '';

        if ($host && (preg_match('/(^|\.)diti\.by$/', $host)
                || $host === 'localhost'
                || $host === '127.0.0.1')) {
                $path = isset($parsed['path']) ? $parsed['path'] : '';
                $query = isset($parsed['query']) ? ('?' . $parsed['query']) : '';

                return '/' . ltrim($path, '/') . $query;
        }

        return $url;
}

function get_base_url($https = false)
{
        global $SiteUrl, $HTTPSSiteUrl, $HttpName, $SHttpName, $RootPath;

        $scheme = $https ? $SHttpName : $HttpName;
        $host = $https ? ($HTTPSSiteUrl ?: $SiteUrl) : $SiteUrl;
        $host = rtrim($host, '/');

        $base = $scheme . '://' . $host;

        $basePath = strlen($RootPath) ? $RootPath : '/';
        if ($basePath[0] != '/') {
                $basePath = '/' . $basePath;
        }
        $basePath = rtrim($basePath, '/') . '/';

        $base .= $basePath;

        static $logged = false;
        if (!$logged && preg_match('/^(localhost|127\.0\.0\.1)/', $host)) {
                error_log('[diti.by] Resolved base URL: ' . $base);
                $logged = true;
        }

        return $base;
}

function sanitize_broken_prefix($page_url)
{
        if (preg_match('/^https?:\/\//i', $page_url)) {
            return $page_url;
        }

        $page_url = ltrim($page_url);

        if (strpos($page_url, '/:/') === 0) {
                $page_url = '/' . ltrim(substr($page_url, 3), '/');
        }

        if (strpos($page_url, ':/') === 0) {
                $page_url = '/' . ltrim(substr($page_url, 2), '/');
        }

        return $page_url;
}

/*
function get_url([page_url[, acc_arr[, keep_old_arg[, https[, always_add]]]]])
string page_url - url of the page in form /root path/sub_path/name.ext or NULL to the current page
array acc_ar - map of GET method attributes
bool keep_old_arg - set to keep old GET attributes
bool https - create url with https protocol
bool always_add - always create full path info
*/
function get_url($page_url=null, $acc_arr = array(), $keep_old_arg = true, $https = false, $always_add = false){
        global $RootPath, $ssl_root;
        if (is_null($page_url)) $page_url = $_SERVER['PHP_SELF'];

        $page_url = sanitize_broken_prefix($page_url);
        if (preg_match('/^https?:\/\//i', $page_url)) return $page_url;

        if (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on'))
                $page_url = preg_replace('/^'.regexp_escape($ssl_root).'/', '', $page_url);
        else
                $page_url = preg_replace('/^'.regexp_escape($RootPath).'/', '', $page_url);
        $page_url = preg_replace('/^'.regexp_escape('/').'/', '', $page_url);

        if ($https)
                $url = get_base_url(true);
        elseif (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on') || $always_add)
                $url = get_base_url(false);
        else {
                $url = rtrim(strlen($RootPath) ? $RootPath : '/', '/') . '/';
        }

        $url .= $page_url;
        if (!is_array($acc_arr)) system_die();
        if ($keep_old_arg) $acc_arr = array_merge($_GET, $acc_arr);
        if (sizeof($acc_arr)> 0){
		$c = '?';
		foreach ($acc_arr as $key => $val){
			$url .= $c . $key . '=' . urlencode($val);
			$c = '&';
		}
	}
	return $url;
}
function in_post($name){for ($i=0; $i<func_num_args(); ++$i)if (!array_key_exists(func_get_arg($i), $_POST)) return false;return true;}
function in_get($name){for ($i=0; $i<func_num_args(); ++$i)if (!array_key_exists(func_get_arg($i), $_GET)) return false;return true;}
function in_get_post($name){for ($i=0; $i<func_num_args(); ++$i){$v=func_get_arg($i);if (!array_key_exists($v, $_POST) && !array_key_exists($v, $_GET)) return false;}return true;}

function str_to_bool($var){if (in_array(strtolower($var), array('true', 'yes', '1'))) return true;elseif (in_array(strtolower($var), array('false', 'no', '0'))) return false;else return (bool) $var;}
function bool_to_str($v){return ($v)?('true'):('false');}

function code2utf($num)
{
	if ($num<128) return chr($num);
	if ($num<2048) return chr(($num>>6)+192).chr(($num&63)+128);
	if ($num<65536) return chr(($num>>12)+224).chr((($num>>6)&63)+128).chr(($num&63)+128);
	if ($num<2097152) return chr(($num>>18)+240).chr((($num>>12)&63)+128).chr((($num>>6)&63)+128).chr(($num&63)+128);
	return '';
}
function utf16parse($t)
{
	if (!is_array($t)) $t = preg_replace('/\&\#([0-9]+)\;/me', "((\\1>255)?(utf8_decode(code2utf(\\1))):('&#\\1;'))", $t);
	else foreach ($t as $k => $tv) $t[$k] = utf16parse($t[$k]);
	return $t;
}
require_once(((get_magic_quotes_gpc())?('_in.quote.php'):('_in.php')));

function SetCacheVar($VarName, $Value, $CachId = 'common') {
if (!strlen($CachId)) return;
if (is_array($Value)) $_SESSION['cache'][$CachId][$VarName] = array_merge($_SESSION['cache'][$CachId], $Value);
else $_SESSION['cache'][$CachId][$VarName] = $Value;
}
// set variables in template_vars ($tv) to values from array or CRecordSet(current row) or CRecordSetRow
function row_to_vars(&$row, &$tv, $create_array = false, $prefix=''){
if ($create_array) $tv = array();
if (is_array($row)) foreach ($row as $k => $v) $tv[$prefix.$k] = $v;
if (strcasecmp(get_class($row), 'CRecordSet')==0) foreach ($row->Fields as $v) $tv[$prefix.$v] = $row->get_field($v);
if (strcasecmp(get_class($row), 'CRecordSetRow')==0) foreach ($row->Fields as $k => $v) $tv[$prefix.$k] = $v;
}
// set variables in template_vars ($tv) to values from CRecordSet
function recordset_to_vars(&$rs, &$tv){
if ($rs === false) {$tv = array();return false;}
$tv = $rs->get_2darray();
}
function recordset_to_vars_callback(&$rs, &$tv, $counter_varname, $cb = '', $prefix='', $data=null, $ovewrite_tv = true){
if ( $rs === false ) {$tv[$counter_varname]=0;return false;}
if ( ($ovewrite_tv) || (!isset($tv[$counter_varname])) ) $tv[$counter_varname] = 0;
$tv[$counter_varname] += $rs->get_record_count();
$rs->first();
foreach ($rs->Fields as $v) {if ($ovewrite_tv || !isset($tv[$prefix.$v])) $tv[$prefix.$v] = array();}
while (!$rs->eof()) {
foreach ($rs->Fields as $v) $tv[$prefix.$v][] = $rs->get_field($v);
call_user_func($cb, $tv, $rs->get_row(), $prefix, $rs->current_row, $data);
$rs->next();
}
}
function arr_val($arr, $key_val, $def_val = '') {
if (is_array($arr) && isset($arr[$key_val])) return $arr[$key_val];
else return $def_val;
}
// should be used instead of print_r
function print_arr() {
$arg_list = func_get_args();echo '<pre>';
foreach ($arg_list as $v) {
print_r($v); echo "\n";}
echo '</pre>';}
// more convenient output
function echox ($text = ''){echo $text."<br />"."\n";}

function is_index(){
for ($i=0; $i<func_num_args(); ++$i){
$v=func_get_arg($i);if (!preg_match('/^[0-9]+$/', $v)) return false;}
return true;
}
function check_file($post_var_name){return ($_FILES[$post_var_name]['size']>0);}
function save_file_to_folder($post_var_name, $folder, $save_original_name = true, $full_folder = true)
{
	if (!$full_folder) {
		$p = $GLOBALS['FilePath'];
		if ( (substr($folder, -1) != '/') && (substr($folder, -1) != '\\') )
			$folder .= '/';
		$folder = str_replace('\\', '/', $folder);
		$a = explode('/', $folder);
		if (!is_dir($p)) {
			@mkdir($p, 0777);
			@chmod($p, 0777);
		}
		foreach ($a as $v)
			if (strlen($v)) {
				$p .= ($v .'/');
				if (!@is_dir($p)) {
					@mkdir($p, 0777);
					@chmod($p, 0777);
				}
			}
	
		$folder = $GLOBALS['FilePath'] . $folder;
		
	}

	$file_name = (($save_original_name)?($_FILES[$post_var_name]['name']):(time() . '_' . $_FILES[$post_var_name]['name']));
	if ($save_original_name)
	{
		$file_name = strtolower($_FILES[$post_var_name]['name']);
	}
	else 
	{
		$mark = microtime();
		$mark = substr($mark,11,11).substr($mark,2,6);
		$ext = strrchr($_FILES[$post_var_name]['name'], '.');
		$file_name = strtolower($mark.(($ext===false)?'':$ext));
	}
	if (@file_exists($folder . $file_name))
	{
		@chmod($folder . $file_name, 0777);
		@unlink($folder . $file_name);
	}
	if (@move_uploaded_file($_FILES[$post_var_name]['tmp_name'], $folder . $file_name))
		return $file_name;
	else
		return false;
}
function save_file_to_folder_from_url($url, $folder, $save_original_name = true, $full_folder = true)
{
	if (!$full_folder) {
		$p = $GLOBALS['FilePath'];
		if ( (substr($folder, -1) != '/') && (substr($folder, -1) != '\\') )
			$folder .= '/';
		$folder = str_replace('\\', '/', $folder);
		$a = explode('/', $folder);
		if (!is_dir($p)) {
			@mkdir($p, 0777);
			@chmod($p, 0777);
		}
		foreach ($a as $v)
			if (strlen($v)) {
				$p .= ($v .'/');
				if (!@is_dir($p)) {
					@mkdir($p, 0777);
					@chmod($p, 0777);
				}
			}
	
		$folder = $GLOBALS['FilePath'] . $folder;
	}
	
	if ($save_original_name)
	{
		$file_name = substr(strtolower(strrchr($url, '/')), 1);
	}
	else 
	{
		$mark = microtime();
		$mark = substr($mark,11,11).substr($mark,2,6);
		$ext = strrchr($url, '.');
		$file_name = strtolower($mark.(($ext===false)?'':$ext));
	}
	if(!$img_s = file_get_contents($url))
		return false;
		
	$res = file_put_contents($folder.$file_name, $img_s); //for local machine
	
	/*$ch = curl_init($url); delete comment on server
	$res = $fp = fopen($folder.$file_name, 'wb');
	curl_setopt($ch, CURLOPT_FILE, $fp);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_exec($ch);
	curl_close($ch);
	fclose($fp);*/
	//print_arr($folder.$file_name, $res);
	
	if($res)
		return $file_name;
	else 
		return false;
}
function compare($str1, $str2){
	if ( strcasecmp($str1, $str2)===0) return true; else return false;
}
function generate_back_url($url)
{
	return base64_encode(str_replace("/", "*", substr($url, 1, strlen($url))));
}
function gen_rand_name($filename){
	$mark = microtime();
	$mark = substr($mark,11,11).substr($mark,2,6);
	$ext = strrchr($filename, '.');
	return strtolower($mark.(($ext===false)?'':$ext));
}
function get_month_name($month)
{
	$months = array(
		'1' => 'января', 
		'2' => 'февраля', 
		'3' => 'марта', 
		'4' => 'апреля', 
		'5' => 'мая', 
		'6' => 'июня', 
		'7' => 'июля', 
		'8' => 'августа', 
		'9' => 'сентября', 
		'10' => 'октября', 
		'11' => 'ноября', 
		'12' => 'декабря', 
	);
	return $months[$month];
}
function convert_template($template){
	return str_replace('&lt;', '<', str_replace('&gt;', '>', $template));
}

function translit( $cyr_str) {
$tr = array(
"Ґ"=>"G","Ё"=>"YO","Є"=>"E","Ї"=>"YI","І"=>"I",
"і"=>"i","ґ"=>"g","ё"=>"yo","№"=>"#","є"=>"e",
"ї"=>"yi","А"=>"A","Б"=>"B","В"=>"V","Г"=>"G",
"Д"=>"D","Е"=>"E","Ж"=>"ZH","З"=>"Z", "И"=>"I",
"Й"=>"Y","К"=>"K","Л"=>"L","М"=>"M","Н"=>"N",
"О"=>"O","П"=>"P","Р"=>"R","С"=>"S","Т"=>"T",
"У"=>"U","Ф"=>"F","Х"=>"H","Ц"=>"TS","Ч"=>"CH",
"Ш"=>"SH","Щ"=>"SCH","Ъ"=>"'","Ы"=>"YI","Ь"=>"",
"Э"=>"E","Ю"=>"YU","Я"=>"YA","а"=>"a","б"=>"b",
"в"=>"v","г"=>"g","д"=>"d","е"=>"e","ж"=>"zh",
"з"=>"z","и"=>"i","й"=>"y","к"=>"k","л"=>"l",
"м"=>"m","н"=>"n","о"=>"o","п"=>"p","р"=>"r",
"с"=>"s","т"=>"t","у"=>"u","ф"=>"f","х"=>"h",
"ц"=>"ts","ч"=>"ch","ш"=>"sh","щ"=>"sch","ъ"=>"'",
"ы"=>"yi","ь"=>"","э"=>"e","ю"=>"yu","я"=>"ya"
);
return strtr($cyr_str,$tr);
}

function array_search_assoc($array, $search_arr)
{
	foreach ($array as $key => $arr) 
		foreach ($search_arr as $search_key => $search_val)
	        if($arr[$search_key] == $search_val)
	        	return $key;
        	
    return false;
}

function parse_json($json, $return_array = true)
{
	if($json === false || is_null($json) || strlen($json) == 0) return false;
	$res = json_decode($json, $return_array);
	return (((is_array($res) && !empty($res)) || (is_object($res))) ? $res : false);
		
}

function curl_get_contents($url)
{
	$curl = curl_init($url);
	curl_setopt($curl, CURLOPT_FAILONERROR, true);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    $result = curl_exec($curl);
    curl_close($curl);
    return $result;
}

function sanitize_output($buffer) {

    $replace = array(
        "#<!--.*?-->#s" => "",      // strip comments
        "#>\s+<#"       => ">\n<",  // strip excess whitespace
        "#\n\s+<#"      => "\n<"    // strip excess whitespace
    );
    $search = array_keys( $replace );
    $html = preg_replace( $search, $replace, $buffer );
    return trim( $html );
}

function close_tags($content)
{
    $position = 0;
    $open_tags = array();
    //теги для игнорирования
    $ignored_tags = array('br', 'hr', 'img');

    while (($position = strpos($content, '<', $position)) !== FALSE)
    {
        //забираем все теги из контента
        if (preg_match("|^<(/?)([a-z\d]+)\b[^>]*>|i", substr($content, $position), $match))
        {
            $tag = strtolower($match[2]);
            //игнорируем все одиночные теги
            if (in_array($tag, $ignored_tags) == FALSE)
            {
                //тег открыт
                if (isset($match[1]) && $match[1] == '')
                {
                    if (isset($open_tags[$tag]))
                        $open_tags[$tag]++;
                    else
                        $open_tags[$tag] = 1;
                }
                //тег закрыт
                if (isset($match[1]) && $match[1] == '/')
                {
                    if (isset($open_tags[$tag]))
                        $open_tags[$tag]--;
                }
            }
            $position += strlen($match[0]);
        }
        else
            $position++;
    }
    //закрываем все теги
    foreach ($open_tags as $tag => $count_not_closed)
    {
        $content .= str_repeat("</{$tag}>", $count_not_closed);
    }

    return $content;
}

function get_price_in_bel_rub( $price, $category_currency, $global_currency ) {

    if ( $category_currency == 0 ) {
        $currency = $global_currency;
    } else {
        $currency = $category_currency;
    }

    return number_format( ( $price * $currency ), 2, '.', ' ');
}

function get_price_in_rus_rub( $price, $dollar_currency, $global_dollar_currency, $rus_currency, $global_rus_currency ) {

    if ( $dollar_currency == 0 || $rus_currency == 0 ) {
        $currency = floatval( $global_dollar_currency )/floatval( $global_rus_currency );
    } else {
        $currency = floatval( $dollar_currency )/floatval( $rus_currency );
    }

    return number_format( round( $price * $currency ), 0, ',', ' '  );
}

/**
 * @param $price
 * @param $category_currency
 * @param $global_currency
 * @return string
 */
function get_price_in_old_bel_rub( $price, $category_currency, $global_currency ) {

    if ( $category_currency == 0 ) {
        $currency = $global_currency;
    } else {
        $currency = $category_currency;
    }

    return number_format( ( $price * $currency * 10000 ), 0, '.', ' ');
}

class CacheFileMY {
    function read($fileName) {
    $fileName = $_SERVER['DOCUMENT_ROOT'].'/cache/'.$fileName;
    if (file_exists($fileName)) {
        $handle = fopen($fileName, 'rb');
        $variable = fread($handle, filesize($fileName));
        fclose($handle);
        return unserialize($variable);
    } else {
        return null;
    }
}
    function write($fileName,$variable) {
        $fileName = $_SERVER['DOCUMENT_ROOT'].'/cache/'.$fileName;
        $handle = fopen($fileName, 'a');
        fwrite($handle, serialize($variable));
        fclose($handle);
    }
    function delete($fileName) {
        $fileName = $_SERVER['DOCUMENT_ROOT'].'/cache/'.$fileName;
        @unlink($fileName);
    }
    function deleteAll() {
        if (file_exists($_SERVER['DOCUMENT_ROOT'].'/cache/')) {
            foreach (glob($_SERVER['DOCUMENT_ROOT'].'/cache/*') as $file) {
                unlink($file);
            }
        }
    }
}