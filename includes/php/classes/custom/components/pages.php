<?
class CPages
{
	var $Application;
	var $DataBase;
	var $tv;
	var $last_error;

	function CPages(&$app)
	{
		$this->Application = &$app;
		$this->tv = &$app->tv;
		$this->DataBase = &$this->Application->DataBase;
	}

	function get_last_error()
	{
		return $this->last_error;
	}
	
	function get($id, $language_id = null)
	{
		if(!is_numeric($id) || intval($id) < 1 || (!is_null($language_id) && (!is_numeric($language_id) || intval($language_id) < 1)))
		{
			$this->last_error = $this->Application->Localizer->get_string('invalid_input');
			return false;
		}
		if(is_null($language_id))
			$language_id = $this->tv['language_id'];
			
		$rs = $this->DataBase->select_custom_sql("
		SELECT
			p.id,
			p.status,
			p.public_date,
			pd.title,
			pd.uri,
			pd.description,
			pd.meta_title,
			pd.meta_description
		FROM
			%prefix%page p JOIN %prefix%page_data pd on (pd.page_id = p.id)
		WHERE
			pd.is_ready = '1' AND pd.language_id = '".intval($language_id)."' AND pd.id = '".intval($id)."'
		");
		return (($rs !== false && !$rs->eof()) ? $rs : false);
	}

    function get_all()
    {
        $rs = $this->DataBase->select_custom_sql("
		SELECT
			p.id,
			p.status,
			p.public_date,
			pd.title,
			pd.uri,
			pd.description,
			pd.meta_title,
			pd.meta_description
		FROM
			%prefix%page p JOIN %prefix%page_data pd on (pd.page_id = p.id)
		WHERE
			pd.is_ready = '1'
		");
        return (($rs !== false && !$rs->eof()) ? $rs : false);
    }

	function get_by_uri($uri, $language_id = null)
	{
		if(!strval($uri) || !strlen($uri) || (!is_null($language_id) && (!is_numeric($language_id) || intval($language_id) < 1)))
		{
			$this->last_error = $this->Application->Localizer->get_string('invalid_input');
			return false;
		}
		if(is_null($language_id))
			$language_id = $this->tv['language_id'];
			
		$rs = $this->DataBase->select_custom_sql("
		SELECT
			p.id,
			p.status,
			p.public_date,
			pd.title,
			pd.uri,
			pd.description,
			pd.meta_title,
			pd.meta_description
		FROM
			%prefix%page p JOIN %prefix%page_data pd on (pd.page_id = p.id)
		WHERE
			pd.is_ready = '1' AND pd.language_id = '".intval($language_id)."' AND pd.uri = '".mysql_real_escape_string($uri)."'
		");
		return (($rs !== false && !$rs->eof()) ? $rs : false);
	}
	
	function add_page($arr)
	{
		if(!is_array($arr))
		{
			$this->last_error = $this->Application->Localizer->get_string('invalid_input');
			return false;
		}
		
		if(strlen($arr['public_date']))
			$arr['public_date'] = date('Y-m-d H:i:s', strtotime($arr['public_date'].' '.((strlen($arr['hours'])) ? $arr['hours'] : '00').':'.((strlen($arr['minutes'])) ? $arr['minutes'] : '00').':'.((strlen($arr['seconds'])) ? $arr['seconds'] : '00')));
		else 
			$arr['public_date'] = date('Y-m-d H:i:s');
			
		$insert_arr = array(
			'status' => intval($arr['status']),
			'public_date' => $arr['public_date']
		);
		
		if(intval($arr['status']) !== OBJECT_NOT_ACTIVE && strtotime($arr['public_date']) > time())
			$insert_arr['status'] = OBJECT_SUSPENDED;
		elseif (intval($arr['status']) !== OBJECT_NOT_ACTIVE && strtotime($arr['public_date']) <= time())
			$insert_arr['status'] = OBJECT_ACTIVE;
		
		if(!$id = $this->DataBase->insert_sql('page', $insert_arr))
		{
			$this->last_error = $this->Application->Localizer->get_string('database_error');
			return false;
		}
		
		$lang_rs = $this->Application->Localizer->get_languages();
		if($lang_rs !== false)
		{
			while (!$lang_rs->eof())
			{
				$double_rs = $this->DataBase->select_custom_sql("SELECT count(pd.id) as cnt FROM %prefix%page p JOIN %prefix%page_data pd on (pd.page_id = p.id) WHERE (pd.title = '".mysql_real_escape_string($arr['data_'.$lang_rs->get_field('abbreviation').'_title'])."' OR pd.uri = '".mysql_real_escape_string($arr['data_'.$lang_rs->get_field('abbreviation').'_uri'])."') AND pd.language_id = '".$lang_rs->get_field('id')."'");
				if($double_rs == false)
				{
					$this->last_error = $this->Application->Localizer->get_string('database_error');
					return false;
				}
				elseif ($double_rs->get_field('cnt'))
				{
					$this->DataBase->delete_sql('page', array('id' => $id));
					$this->last_error = $this->Application->Localizer->get_string('object_exists');
					return false;
				}
				
				if(intval($arr['data_'.$lang_rs->get_field('abbreviation').'_is_ready']) || intval($arr['data_'.$lang_rs->get_field('abbreviation').'_save_data']))
				{
					$insert_arr = array(
						'page_id' => $id,
						'language_id' => $lang_rs->get_field('id'),
						'title' => $arr['data_'.$lang_rs->get_field('abbreviation').'_title'],
						'uri' => $arr['data_'.$lang_rs->get_field('abbreviation').'_uri'],
						'description' => $arr['data_'.$lang_rs->get_field('abbreviation').'_description'],
						'meta_title' => $arr['data_'.$lang_rs->get_field('abbreviation').'_meta_title'],
						'meta_description' => $arr['data_'.$lang_rs->get_field('abbreviation').'_meta_description'],
						'is_ready' => intval($arr['data_'.$lang_rs->get_field('abbreviation').'_is_ready'])
					);
					
					if(!$this->DataBase->insert_sql('page_data', $insert_arr))
					{
						$this->last_error = $this->Application->Localizer->get_string('database_error');
						return false;
					}
				}
				
				$lang_rs->next();
			}
		}
		
		return $id;
	}
	
	function update_page($id, $arr)
	{
		if(!is_array($arr) || !is_numeric($id) || !intval($id))
		{
			$this->last_error = $this->Application->Localizer->get_string('invalid_input');
			return false;
		}
		
		if(strlen($arr['public_date']))
			$arr['public_date'] = date('Y-m-d H:i:s', strtotime($arr['public_date'].' '.((strlen($arr['hours'])) ? $arr['hours'] : '00').':'.((strlen($arr['minutes'])) ? $arr['minutes'] : '00').':'.((strlen($arr['seconds'])) ? $arr['seconds'] : '00')));
		else 
			$arr['public_date'] = date('Y-m-d H:i:s');
			
		$update_arr = array(
			'status' => intval($arr['status']),
			'public_date' => $arr['public_date']
		);
		
		if(intval($arr['status']) !== OBJECT_NOT_ACTIVE && strtotime($arr['public_date']) > time())
			$update_arr['status'] = OBJECT_SUSPENDED;
		elseif (intval($arr['status']) !== OBJECT_NOT_ACTIVE && strtotime($arr['public_date']) <= time())
			$update_arr['status'] = OBJECT_ACTIVE;
		
		if(!$this->DataBase->update_sql('page', $update_arr, array('id' => $id)))
		{
			$this->last_error = $this->Application->Localizer->get_string('database_error');
			return false;
		}
		
		$lang_rs = $this->Application->Localizer->get_languages();
		if($lang_rs !== false)
		{
			while (!$lang_rs->eof())
			{
				$double_rs = $this->DataBase->select_custom_sql("SELECT count(pd.id) as cnt FROM %prefix%page p JOIN %prefix%page_data pd on (pd.page_id = p.id) WHERE (pd.title = '".mysql_real_escape_string($arr['data_'.$lang_rs->get_field('abbreviation').'_title'])."' OR pd.uri = '".mysql_real_escape_string($arr['data_'.$lang_rs->get_field('abbreviation').'_uri'])."') AND pd.language_id = '".$lang_rs->get_field('id')."' AND pd.id <> '".intval($arr['data_'.$lang_rs->get_field('abbreviation').'_id'])."'");
				if($double_rs == false)
				{
					$this->last_error = $this->Application->Localizer->get_string('database_error');
					return false;
				}
				elseif ($double_rs->get_field('cnt'))
				{
					$this->last_error = $this->Application->Localizer->get_string('object_exists');
					return false;
				}
				
				$insert_arr = $update_arr = array(
					'page_id' => $id,
					'language_id' => $lang_rs->get_field('id'),
					'title' => $arr['data_'.$lang_rs->get_field('abbreviation').'_title'],
					'uri' => $arr['data_'.$lang_rs->get_field('abbreviation').'_uri'],
					'description' => $arr['data_'.$lang_rs->get_field('abbreviation').'_description'],
					'meta_title' => $arr['data_'.$lang_rs->get_field('abbreviation').'_meta_title'],
					'meta_description' => $arr['data_'.$lang_rs->get_field('abbreviation').'_meta_description'],
					'is_ready' => intval($arr['data_'.$lang_rs->get_field('abbreviation').'_is_ready'])
				);
				
				if(!intval($arr['data_'.$lang_rs->get_field('abbreviation').'_id']))
				{
					if(intval($arr['data_'.$lang_rs->get_field('abbreviation').'_is_ready']) || intval($arr['data_'.$lang_rs->get_field('abbreviation').'_save_data']))
					{
						if(!$this->DataBase->insert_sql('page_data', $insert_arr))
						{
							$this->last_error = $this->Application->Localizer->get_string('database_error');
							return false;
						}
					}
				}
				else 
				{
					if(!$this->DataBase->update_sql('page_data', $update_arr, array('id' => intval($arr['data_'.$lang_rs->get_field('abbreviation').'_id']))))
					{
						$this->last_error = $this->Application->Localizer->get_string('database_error');
						return false;
					}
				}
				
				$lang_rs->next();
			}
		}
			
		return $id;
	}


	function get_page_by_uri($uri)
	{
		if (!strlen($uri))
		{
			$this->last_error = $this->Application->Localizer->get_string('invalid_input');
			return false;
		}

		$rs = $this->DataBase->select_custom_sql("SELECT
		pd.id as id,
		pd.title as title,
		pd.uri as uri,
		pd.page_id as page_id,
		pd.meta_title as meta_title,
		pd.meta_description as meta_description,
		pd.description as description,
		p.id as p_id,
		p.status as status
		FROM %prefix%page_data as pd
		JOIN %prefix%page as p
		WHERE pd.uri = '".$uri."' and p.id = pd.page_id
		");

		return (($rs != false && !$rs->eof()) ? $rs : false);
	}
}
?>