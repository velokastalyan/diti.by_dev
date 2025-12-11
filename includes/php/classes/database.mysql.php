<?php
require_once('_db.php');

class CDataBase extends CBaseDB
{
    function CDataBase(&$app, $locale = 'en_US')
    {
        parent::CBaseDB($app);
        $this->now_stmt = 'now()';
        $this->datetime_stmt = 'DATETIME';
        $this->clob_stmt = 'TEXT';
        $this->auto_inc_stmt = 'auto_increment';
        $this->concat_func_stmt = 'concat';
        $this->concat_char = ',';
        $this->locale = $locale;

        if (!function_exists('mysqli_connect')) system_die('MySQLi module is not installed');
    }

    function _set_limit($sql, $limit)
    {
        if (!is_null($limit))
            if (!is_array($limit))
                $sql.= $limit> 0 ? ' LIMIT '.intval($limit):'';
            elseif (isset($limit[1]) && isset($limit[0]) && ($limit[1] > 0) && ($limit[0] > 0))
                $sql.=' LIMIT '.((intval($limit[0])-1) * intval($limit[1])). ',' . intval($limit[1]);
        return $sql;
    }

    function free($id) { if ($id instanceof mysqli_result) return mysqli_free_result($id); return false; }
    function get_last_id() { return mysqli_insert_id($this->LinkID); }

    function internalConnect()
    {
        if ($this->LinkID === false)
        {
            $this->LinkID = @mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE, DB_PORT);
            if ($this->LinkID !== false)
            {
                mysqli_query($this->LinkID, "set names 'utf8'");
                mysqli_query($this->LinkID, "SET SESSION sql_mode = ''");
                return $this->LinkID;
            }
            else {
                $this->last_error = 'Connect Error: ' . mysqli_connect_error();
                return false;
            }
        }
        return $this->LinkID;
    }

    function internalDisconnect() { if ($this->LinkID) @mysqli_close($this->LinkID); $this->LinkID = false; }
    
    function internalEscape($s) { 
        if (($this->LinkID === false) && (!$this->internalConnect())) return false;
        return mysqli_real_escape_string($this->LinkID, $s); 
    }

    function internalLikeEscape($s) {
        $s = $this->internalEscape($s);
        return str_replace(array('\\', '%', '_'), array('\\\\', '\\%', '\\_'), $s);
    }

    function internalFetchRow($r) { return @mysqli_fetch_array($r, MYSQLI_NUM); }
    function internalFetchArray($r) { return @mysqli_fetch_array($r, MYSQLI_BOTH); }
    function internalFetchAssoc($r) { return @mysqli_fetch_array($r, MYSQLI_ASSOC); }
    function internalNumRows($r) { return @mysqli_num_rows($r); }
    function internalNumFields($r) { return @mysqli_num_fields($r); }
    function internalFetchField($r, $i) { return @mysqli_fetch_field_direct($r, $i); }

    function internalQuery($str)
    {
        $this->last_error = '';
        if (($this->LinkID === false) && (!$this->internalConnect())) return false;
        $str = str_replace('%prefix%', (defined('DB_PREFIX')?DB_PREFIX:''), $str);
        
        $id = @mysqli_query($this->LinkID, $str);
        if (!$id) {
            $this->last_error = mysqli_error($this->LinkID);
            if($GLOBALS['DebugLevel']) echo "<div style='color:red; border:1px solid red; padding:5px;'>SQL Error: ".$this->last_error."<br>Query: ".$str."</div>";
        }
        return $id;
    }

    function lock($tables=null) { return true; } // Not vital for local
    function unlock() { return true; }
    
    function is_table($TableName) {
        $res = $this->internalQuery("SHOW TABLES LIKE '".(defined('DB_PREFIX')?DB_PREFIX:'').$TableName."'");
        return (mysqli_num_rows($res) == 1);
    }

    function version() { return 5.7; }
}
?>
