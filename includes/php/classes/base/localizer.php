<?php

class CLocalizer {

	protected $DataBase;
	protected $tv;
	protected $last_error;
	protected $strings;

	function CLocalizer(CApp &$app) {
		$this->Application = &$app;
		$this->DataBase = &$this->Application->DataBase;
		$this->tv = &$app->tv;
		$this->tv['language_id'] = (int)InCookie('language_id', 1);
		$this->strings = array();
	}

	function set_language($lang_id){
		if(!is_numeric($lang_id)) return false;
		$check_rs = $this->Application->DataBase->select_sql('loc_lang', array('id' => $lang_id), null, array('id'));
		if ( ($check_rs === false) || $check_rs->eof() ) {
			$this->last_error = $this->Application->Localizer->get_string('object_not_exists');
			return false;
		}
		$this->strings = array();
		$this->tv['language_id'] = $lang_id;
		setcookie('language_id', $lang_id, (time()+(60*60*24*365)), '/');
	}
	
	function get_string($name){
		if(!strlen($name)) return false;
		$lang_id = (($this->tv['language_id']) ? $this->tv['language_id'] : 1);
		if(count($this->strings) == 0){
			$loc_string_rs = $this->DataBase->select_sql('loc_string', array('language_id' => $lang_id));
			if($loc_string_rs !== false)
			{
				while (!$loc_string_rs->eof()){
					$this->strings[$loc_string_rs->get_field('name')] = $loc_string_rs->get_field('value');
					$loc_string_rs->next();
				}
			}
		}
		
		if($name != '')
		{
			$value = '';
			if(isset($this->strings[$name])) $value = $this->strings[$name];
			else
			{
				$rs = $this->DataBase->select_sql('loc_string', array('language_id' => $lang_id, 'name' => $name));
				if($rs !== false && !$rs->eof()) $value = $rs->get_field('value');
				else
				{
					$this->DataBase->insert_sql('loc_string', array('language_id' => $lang_id, 'name' => $name, 'value' => $name));
					return $name;
				}
			}
			if (func_num_args() > 1) {
				$args = func_get_args();
				$args[0] = $value;
				$value = call_user_func_array('sprintf', $args);
			}
			return $value;
		}
		else system_die('Empty localizer name');
	}
	
	function add_loc_lang($arr)
	{
		if(!is_array($arr))
		{
			$this->last_error = $this->get_string('invalid_input');
			return false;
		}

		$insert_arr = array(
			'title' => $arr['title'],
			'abbreviation' => $arr['abbreviation'],
		);

		if (!$id = $this->Application->DataBase->insert_sql('loc_lang', $insert_arr))
		{
			$this->last_error = $this->Application->Localizer->get_string('database_error');
			return false;
		}

		return $id;
	}

    function update_loc_lang($id, $arr)
    {
		if(!is_numeric($id) || $id < 1 || !is_array($arr))
		{
			$this->last_error = $this->get_string('invalid_input');
			return false;
		}

		$update_arr = array(
        	'title' => $arr['title'],
            'abbreviation' => $arr['abbreviation'],
		);

        if (!$this->Application->DataBase->update_sql('loc_lang', $update_arr, array('id' => intval($id))))
        {
        	$this->last_error = $this->Application->Localizer->get_string('internal_error');
            return false;
		}

        return $id;
	}
	
	function add_loc_string($arr)
	{
		if(!is_array($arr))
		{
			$this->last_error = $this->get_string('invalid_input');
			return false;
		}

    	$insert_arr = array(
        	'name' => $arr['name'],
        	'language_id' => intval($arr['language_id']),
            'value' => $arr['value'],
        );

		if (!$id = $this->Application->DataBase->insert_sql('loc_string', $insert_arr))
		{
			$this->last_error = $this->Application->Localizer->get_string('database_error');
			return false;
		}

        return $id;
	}

    function update_loc_string($id, $arr)
    {
		if(!is_numeric($id) || $id < 1 || !is_array($arr))
		{
			$this->last_error = $this->get_string('invalid_input');
			return false;
		}

    	$update_arr = array(
        	'name' => $arr['name'],
			'language_id' => intval($arr['language_id']),
            'value' => $arr['value'],
        );

        if (!$this->DataBase->update_sql('loc_string', $update_arr, array('id' => intval($id))))
        {
        	$this->last_error = $this->Application->Localizer->get_string('internal_error');
            return false;
		}

        return $id;
	}
	
	function get_gstring($group_name, $key_name)
	{
		$arr = func_get_args();
		array_shift($arr);
		return call_user_func_array(array($this, 'get_string'), $arr);
	}

	function get_languages(){
		$lang_rs = $this->DataBase->select_sql('loc_lang');
		return ( ($lang_rs !== false && !$lang_rs->eof()) ? $lang_rs : false );
	}

	function get_last_error(){
		return $this->last_error;
	}
}
?>