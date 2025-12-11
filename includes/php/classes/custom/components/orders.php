<?
class COrders
{
	var $Application;
	var $DataBase;
	var $tv;
	var $last_error;

	function COrders(CApp &$app)
	{
		$this->Application = &$app;
		$this->tv = &$app->tv;
		$this->DataBase = &$this->Application->DataBase;
	}

	function get_last_error()
	{
		return $this->last_error;
	}

	function get_all()
	{
		$rs = $this->DataBase->select_custom_sql("");

		return ((!$rs->eof()) ? $rs : false);
	}

	function add_shipping_info($arr)
	{
		if(!is_array($arr))
		{
			$this->last_error = $this->Application->Localizer->get_string('invalid_input');
			return false;
		}

		$insert_arr = array(
			'status' => $arr['status'],
		);

		if (!$id = $this->Application->DataBase->insert_sql('shipping_info', $insert_arr)) {
			$this->last_error = $this->Application->Localizer->get_string('database_error');
			return false;
		}

		return $id;
	}

	function update_shipping_info($id, $arr)
	{
		if(!is_array($arr) || !is_numeric($id))
		{
			$this->last_error = $this->Application->Localizer->get_string('invlalid_input');
			return false;
		}

		$update_arr = array(
			'status' => $arr['status'],
            'name' => $arr['name'],
            'address' => $arr['address'],
            'phone' => $arr['phone'],
            'comment' => $arr['comment'],
		);

		if (!$id = $this->Application->DataBase->update_sql('shipping_info', $update_arr, array('id' => $id))) {
			$this->last_error = $this->Application->Localizer->get_string('database_error');
			return false;
		}

		return $id;
	}
}
?>