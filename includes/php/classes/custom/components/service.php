<?
class CService
{
	var $Application;
	var $DataBase;
	var $tv;
	var $last_error;

	function CService(&$app)
	{
		$this->Application = &$app;
		$this->tv = &$app->tv;
		$this->DataBase = &$this->Application->DataBase;
	}

	function get_last_error()
	{
		return $this->last_error;
	}

	function add_service($arr)
	{

		if(!is_array($arr))
		{
			$this->last_error = $this->Application->Localizer->get_string('invalid_input');
			return false;
		}

		$insert_arr = array(
			'category_id' => $arr['category_id'],
			'description' => $arr['description'],
			'status' => $arr['status'],
			'data' => date("Y-m-d G:i:s")
		);

		if(!$id = $this->DataBase->insert_sql('service', $insert_arr))
		{
			$this->last_error = $this->Application->Localizer->get_string('database_error');
			return false;
		}

		/*if (!$this->update_product_rate($arr['product_id']))
		{
			$this->last_error = $this->Application->Localizer->get_string('database_error');
			return false;
		}*/

		return $id;
	}

	function update_service($id, $arr)
	{
		if(!is_array($arr) || !is_numeric($id))
		{
			$this->last_error = $this->Application->Localizer->get_string('invlalid_input');
			return false;
		}

		$update_arr = array(
			'category_id' => $arr['category_id'],
			'description' => $arr['description'],
			'status' => $arr['status'],
			'data' => date("Y-m-d G:i:s")
		);

		if(!$this->DataBase->update_sql('service', $update_arr, array('id' => $id)))
		{
			$this->last_error = $this->Application->Localizer->get_string('database_error');
			return false;
		}

		/*if (!$this->update_product_rate($arr['product_id']))
		{
			$this->last_error = $this->Application->Localizer->get_string('database_error');
			return false;
		}*/


		return $id;
	}

	function update_product_rate($id)
	{
		if (!is_numeric($id))
		{
			$this->last_error = $this->Application->Localizer->get_string('invalid_input');
		}

		$rs = $this->DataBase->select_sql('comment', array('product_id' => $id, 'status' => 'active'));
		$sum_ratio = 0;
		$count_ratio = 0;

		if ($rs != false && !$rs->eof())
		{
			while(!$rs->eof())
			{
				$sum_ratio += $rs->get_field('rate');
				$count_ratio++;
				$rs->next();
			}

			//$count_ratio = count($sum_ratio);

			if ($count_ratio > 0)
			{
				$rate = ceil($sum_ratio/$count_ratio);
			}

			if (!$this->DataBase->update_sql('product', array('rate' => $rate), array('id' => $id)))
			{
				$this->last_error = $this->Application->Localizer->get_string('database_error');
				return false;
			}

			return true;
		}
	}


	function get_services($id)
	{
		if (!is_numeric($id))
		{
			$this->last_error = $this->Application->Localizer->get_string('invalid_input');
			return false;
		}

		$rs = $this->DataBase->select_custom_sql("SELECT
		s.id as s_id,
		s.category_id as category_id,
		c.id as id
		s.description as description,
		c.data as data,
		c.status as status,
		FROM %prefix%service as s
		JOIN %prefix%category as c on ((s.id = '".$id."'))
		WHERE s.product_id = c.id and s.status = 'active'
		ORDER BY s.data ASC
		");

		return (($rs != false && !$rs->eof()) ? $rs : false);
	}

    function get_all()
    {
        $rs = $this->DataBase->select_custom_sql("SELECT
		s.id,
		s.category_id,
		s.status,
		s.description,
		c.id,
		c.title as category
		FROM %prefix%service s
		JOIN %prefix%category as c on (s.category_id = c.id)
		ORDER BY s.id DESC");
        return (($rs !== false && !$rs->eof()) ? $rs : false);
    }

}

?>