<?
class CBanners
{
	var $Application;
	var $DataBase;
	var $tv;
	var $last_error;

	function CBanners(CApp &$app)
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
		$rs = $this->DataBase->select_custom_sql("
		SELECT id, description, image_filename, position,link
		FROM banner
		ORDER BY position");

		return ((!$rs->eof()) ? $rs : false);
	}



	function add_banner($arr)
	{

		if(!is_array($arr))
		{
			$this->last_error = $this->Application->Localizer->get_string('invalid_input');
			return false;
		}

		$insert_arr = array(
			'description' => $arr['description'],
			'title' => $arr['title'],
			'uri' => $arr['uri'],
			'position' => $arr['position'],
			'image_filename' => $arr['image_filename'],
            'link' => $arr['link']
		);



		if(!$id = $this->DataBase->insert_sql('banner', $insert_arr))
		{
			$this->last_error = $this->Application->Localizer->get_string('database_error');
			return false;
		}

		if(!$filename = AdminUploader::upload('image_filename', 'pub/banners/'.$id.'/'))
		{
			$this->last_error = $this->Application->Localizer->get_string('cannot_save_file', 'image_filename');
			return false;
		}

		$Images = $this->Application->get_module('Images');
		$Images->create('banner_image', ROOT .'pub/banners/'.$id.'/'.$filename, array('banner_id' => $id));


		return $id;
	}

	function update_banner($id, $arr)
	{
		if(!is_array($arr) || !is_numeric($id))
		{
			$this->last_error = $this->Application->Localizer->get_string('invlalid_input');
			return false;
		}


		$update_arr = array(
			'description' => $arr['description'],
			'title' => $arr['title'],
			'uri' => $arr['uri'],
			'position' => $arr['position'],
            'link' => $arr['link']
		);
		if ($arr['image_filename'] !== '')
		{
			$update_arr['image_filename'] = $arr['image_filename'];
			$old_rs = $this->DataBase->select_sql('banner', array('id' => intval($id)));
			if($old_rs === false || $old_rs->eof())
			{
				$this->last_error = $this->Application->Localizer->get_string('internal_error');
				return false;
			}

			$Images = $this->Application->get_module('Images');

			if(file_exists(ROOT .'pub/banners/'. $old_rs->get_field('id') .'/'. $old_rs->get_field('image_filename')))
			{
				$Images->delete('banner_image', ROOT .'pub/banners/'.$old_rs->get_field('id').'/'. $old_rs->get_field('image_filename'), array('banner_id' => $old_rs->get_field('id')));
				@unlink(ROOT .'pub/banners/'. $old_rs->get_field('id') .'/'. $old_rs->get_field('image_filename'));
			}

			if(!$filename = AdminUploader::upload('image_filename', 'pub/banners/'.$id.'/'))
			{
				$this->last_error = $this->Application->Localizer->get_string('cannot_save_file', 'image_filename');
				return false;
			}

			$Images->create('banner_image', ROOT .'pub/banners/'.$id.'/'.$filename, array('banner_id' => $id));
		}

		if(!$this->DataBase->update_sql('banner', $update_arr, array('id' => $id)))
		{
			$this->last_error = $this->Application->Localizer->get_string('database_error');
			return false;
		}


		return $id;
	}
}
?>