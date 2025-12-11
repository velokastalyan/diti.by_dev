<?
class CVideos
{
	var $Application;
	var $DataBase;
	var $tv;
	var $last_error;

	function CVideos(&$app)
	{
		$this->Application = &$app;
		$this->tv = &$app->tv;
		$this->DataBase = &$this->Application->DataBase;
	}

	function get_last_error()
	{
		return $this->last_error;
	}

	function add_video($arr)
	{

		if(!is_array($arr))
		{
			$this->last_error = $this->Application->Localizer->get_string('invalid_input');
			return false;
		}

		$insert_arr = array(
			'title' => $arr['title'],
            'link' => $arr['link'],
            'position' => $arr['position']
		);

		if(!$id = $this->DataBase->insert_sql('video', $insert_arr))
		{
			$this->last_error = $this->Application->Localizer->get_string('database_error');
			return false;
		}


		return $id;
	}

	function update_video($id, $arr)
	{
		if(!is_array($arr) || !is_numeric($id))
		{
			$this->last_error = $this->Application->Localizer->get_string('invlalid_input');
			return false;
		}

		$update_arr = array(
			'title' => $arr['title'],
			'link' => $arr['link'],
            'position' => $arr['position']
		);

		if(!$this->DataBase->update_sql('video', $update_arr, array('id' => $id)))
		{
			$this->last_error = $this->Application->Localizer->get_string('database_error');
			return false;
		}


		return $id;
	}

	function get_all()
	{
		$rs = $this->DataBase->select_custom_sql("SELECT id, title, link, position FROM video ORDER BY position ASC");

		return ((!$rs->eof()) ? $rs : false);
	}

	function get_video()
	{
		$videos_rs = $this->DataBase->select_custom_sql("SELECT id, title, link, position FROM video ORDER BY position ASC LIMIT 5");

		return ((!$videos_rs->eof()) ? $videos_rs : false);
	}

}

?>