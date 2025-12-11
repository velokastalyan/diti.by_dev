<?
class CConsultation
{
	var $Application;
	var $DataBase;
	var $tv;
	var $last_error;

	function CConsultation(&$app)
	{
		$this->Application = &$app;
		$this->tv = &$app->tv;
		$this->DataBase = &$this->Application->DataBase;
	}

	function get_last_error()
	{
		return $this->last_error;
	}

	function add_consultation($arr)
	{

		if(!is_array($arr))
		{
			$this->last_error = $this->Application->Localizer->get_string('invalid_input');
			return false;
		}

		$insert_arr = array(
			'product_id' => $arr['product_id'],
			'name' => $arr['name'],
			'phone' => $arr['phone'],
			'status' => $arr['status'],
            'comment' => $arr['comment']
		);

		if(!$id = $this->DataBase->insert_sql('consultation', $insert_arr))
		{
			$this->last_error = $this->Application->Localizer->get_string('database_error');
			return false;
		}

		return $id;
	}

	function update_consultation($id, $arr)
	{
		if(!is_array($arr) || !is_numeric($id))
		{
			$this->last_error = $this->Application->Localizer->get_string('invlalid_input');
			return false;
		}

		$update_arr = array(
			'product_id' => $arr['product_id'],
			'name' => $arr['name'],
			'phone' => $arr['phone'],
            'comment' => $arr['comment'],
			'status' => $arr['status']
		);

		if(!$this->DataBase->update_sql('consultation', $update_arr, array('id' => $id)))
		{
			$this->last_error = $this->Application->Localizer->get_string('database_error');
			return false;
		}



		return $id;
	}

}

?>