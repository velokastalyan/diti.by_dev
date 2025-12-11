<?
class CComments
{
	var $Application;
	var $DataBase;
	var $tv;
	var $last_error;

	function CComments(&$app)
	{
		$this->Application = &$app;
		$this->tv = &$app->tv;
		$this->DataBase = &$this->Application->DataBase;
	}

	function get_last_error()
	{
		return $this->last_error;
	}

	function add_comment($arr)
	{

		if(!is_array($arr))
		{
			$this->last_error = $this->Application->Localizer->get_string('invalid_input');
			return false;
		}

		$insert_arr = array(
			'product_id' => $arr['product_id'],
			'name' => $arr['name'],
			'rate' => $arr['rate'],
			'title' => $arr['title'],
			'description' => $arr['description'],
			'status' => $arr['status'],
			'data' => date("Y-m-d G:i:s"),
            'answer' => $arr['answer']
		);

		if(!$id = $this->DataBase->insert_sql('comment', $insert_arr))
		{
			$this->last_error = $this->Application->Localizer->get_string('database_error');
			return false;
		}

		if (!$this->update_product_rate($arr['product_id']))
		{
			$this->last_error = $this->Application->Localizer->get_string('database_error');
			return false;
		}

		return $id;
	}

	function update_comment($id, $arr)
	{
		if(!is_array($arr) || !is_numeric($id))
		{
			$this->last_error = $this->Application->Localizer->get_string('invlalid_input');
			return false;
		}

		$update_arr = array(
			'product_id' => $arr['product_id'],
			'name' => $arr['name'],
			'rate' => $arr['rate'],
			'title' => $arr['title'],
			'description' => $arr['description'],
			'status' => $arr['status'],
			'data' => date("Y-m-d G:i:s"),
            'answer' => $arr['answer']
		);

		if(!$this->DataBase->update_sql('comment', $update_arr, array('id' => $id)))
		{
			$this->last_error = $this->Application->Localizer->get_string('database_error');
			return false;
		}

		if (!$this->update_product_rate($arr['product_id']))
		{
			$this->last_error = $this->Application->Localizer->get_string('database_error');
			return false;
		}


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


	function get_product_comments($id)
	{
		if (!is_numeric($id))
		{
			$this->last_error = $this->Application->Localizer->get_string('invalid_input');
			return false;
		}

		$rs = $this->DataBase->select_custom_sql("SELECT
		p.id as p_id,
		p.uri as p_uri,
		c.product_id as product_id,
		c.id as id,
		c.title as title,
		c.description as description,
		c.name as name,
		c.rate as rate,
		c.data as data,
		c.status as status,
		c.answer as answer
		FROM %prefix%comment as c
		JOIN %prefix%product as p on ((p.id = '".$id."'))
		WHERE c.product_id = p.id and c.status = 'active'
		ORDER BY c.data ASC
		");

		return (($rs != false && !$rs->eof()) ? $rs : false);
	}

}

?>