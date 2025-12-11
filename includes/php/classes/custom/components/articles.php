<?
class CArticles
{
	var $Application;
	var $DataBase;
	var $tv;
	var $last_error;

	function CArticles(CApp &$app)
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
		SELECT *
		FROM article");

		return ((!$rs->eof()) ? $rs : false);
	}

	function  get_news($uri = '', $page = 1, $on_page = 10)
	{
		if (!is_numeric($page))
		{
			$this->last_error = $this->Application->Localizer('invalid_input');
			return false;
		}

		if (strlen($uri))
			$u = " AND uri = '".$uri."'";
		else $u = '';

		$rs = $this->DataBase->select_custom_sql("SELECT
		id, title, uri, description, public_date as date, DATE_FORMAT(public_date,'%d.%m.%Y') as public_date, image_filename, meta_title, meta_description, description as description_main,
		(
			SELECT count(id) FROM article WHERE status = '".OBJECT_ACTIVE."' AND id_cat = 1
		) as cnt
		FROM article
		WHERE status=1
		".$u."
		ORDER BY date desc
		",array($page, $on_page));

        if($rs !== false && !$rs->eof())
        {
            while (!$rs->eof())
            {
                if(strpos($rs->get_field('description'), '<!-- more -->'))
                {
                    $contents = explode('<!-- more -->', $rs->get_field('description'));
                    $desc = close_tags($contents[0]);
                    $rs->set_field('description_main', $desc);
                }
                $rs->next();
            }
            $rs->first();
        }
		return (($rs != false && !$rs->eof()) ? $rs : false);
	}

	function get_articles($uri = '', $page = 1, $on_page = 10)
	{
		if (!is_numeric($page))
		{
			$this->last_error = $this->Application->Localizer('invalid_input');
			return false;
		}

		if (strlen($uri))
			$u = " AND uri = '".$uri."'";
		else $u = '';

		$rs = $this->DataBase->select_custom_sql("SELECT
		id, title, uri, description, public_date as date, DATE_FORMAT(public_date,'%d.%m.%Y') as public_date, image_filename, meta_title, meta_description,
		(
			SELECT count(id) FROM article WHERE status = '".OBJECT_ACTIVE."' AND id_cat = 2
		) as cnt
		FROM article
		WHERE status=1 and id_cat = 2
		".$u."
		ORDER BY date desc
		",array($page, $on_page));

		return (($rs != false && !$rs->eof()) ? $rs : false);
	}

	function get_news_main()
	{
		$rs = $this->DataBase->select_custom_sql("
		SELECT id, title, uri, description, public_date
		FROM article
		WHERE status=1
		ORDER BY public_date desc
		LIMIT 3
		");

		return ((!$rs->eof()) ? $rs : false);
	}

	function get_comment($article_id)
	{
		if (!is_numeric($article_id))
		{
			$this->last_error = $this->Application->Localizer->get_string('invalid_input');
			return false;
		}

		$rs = $this->DataBase->select_custom_sql("SELECT
		id, article_id, name, description, status
		FROM article_comment
		WHERE article_id='".$article_id."' and status = 'active'
		");

		return (($rs != false) ? $rs : false);
	}

	function add_article_comment($arr)
	{
		if(!is_array($arr))
		{
			$this->last_error = $this->Application->Localizer->get_string('invalid_input');
			return false;
		}

		$insert_arr = array(
			'article_id' => $arr['article_id'],
			'name' => $arr['name'],
			'description' => $arr['description'],
			'status' => $arr['status']
		);

		if(!$id = $this->DataBase->insert_sql('article_comment', $insert_arr))
		{
			$this->last_error = $this->Application->Localizer->get_string('database_error');
			return false;
		}

		return $id;
	}

	function update_article_comment($id, $arr)
	{
		if(!is_array($arr) || !is_numeric($id))
		{
			$this->last_error = $this->Application->Localizer->get_string('invalid_input');
			return false;
		}

		$update_arr = array(
			'article_id' => $arr['article_id'],
			'name' => $arr['name'],
			'description' => $arr['description'],
			'status' => $arr['status']
		);

		if(!$this->DataBase->update_sql('article_comment', $update_arr, array('id' => $id)))
		{
			$this->last_error = $this->Application->Localizer->get_string('database_error');
			return false;
		}
		return $id;
	}



	function add_article($arr)
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

		if(intval($arr['status']) !== OBJECT_NOT_ACTIVE && strtotime($arr['public_date']) > time())
			$insert_arr['status'] = OBJECT_SUSPENDED;
		elseif (intval($arr['status']) !== OBJECT_NOT_ACTIVE && strtotime($arr['public_date']) <= time())
			$insert_arr['status'] = OBJECT_ACTIVE;

		$insert_arr = array(
			'description' => $arr['description'],
			'title' => $arr['title'],
			'uri' => $arr['uri'],
			'id_cat' => intval($arr['id_cat']),
			'image_filename' => $arr['image_filename'],
			'status' => $arr['status'],
			'meta_title' => $arr['meta_title'],
			'meta_description' => $arr['meta_description'],
			'public_date' => $arr['public_date']
		);


		if(!$id = $this->DataBase->insert_sql('article', $insert_arr))
		{
			$this->last_error = $this->Application->Localizer->get_string('database_error');
			return false;
		}

		if(!$filename = AdminUploader::upload('image_filename', 'pub/articles/'.$id.'/'))
		{
			$this->last_error = $this->Application->Localizer->get_string('cannot_save_file', 'image_filename');
		}

		$Images = $this->Application->get_module('Images');
		$Images->create('article_image', ROOT .'pub/articles/'.$id.'/'.$filename, array('article_id' => $id));


		return $id;
	}

	function update_article($id, $arr)
	{
		if(!is_array($arr) || !is_numeric($id))
		{
			$this->last_error = $this->Application->Localizer->get_string('invlalid_input');
			return false;
		}


		if(strlen($arr['public_date']))
			$arr['public_date'] = date('Y-m-d H:i:s', strtotime($arr['public_date'].' '.((strlen($arr['hours'])) ? $arr['hours'] : '00').':'.((strlen($arr['minutes'])) ? $arr['minutes'] : '00').':'.((strlen($arr['seconds'])) ? $arr['seconds'] : '00')));
		else
			$arr['public_date'] = date('Y-m-d H:i:s');

		if(intval($arr['status']) !== OBJECT_NOT_ACTIVE && strtotime($arr['public_date']) > time())
			$insert_arr['status'] = OBJECT_SUSPENDED;
		elseif (intval($arr['status']) !== OBJECT_NOT_ACTIVE && strtotime($arr['public_date']) <= time())
			$insert_arr['status'] = OBJECT_ACTIVE;

		$update_arr = array(
			'description' => $arr['description'],
			'title' => $arr['title'],
			'uri' => $arr['uri'],
			'id_cat' => $arr['id_cat'],
			'image_filename' => $arr['image_filename'],
			'status' => $arr['status'],
			'meta_title' => $arr['meta_title'],
			'meta_description' => $arr['meta_description'],
			'public_date' => $arr['public_date']
		);
		if ($arr['image_filename'] !== '')
		{
			$update_arr['image_filename'] = $arr['image_filename'];
			$old_rs = $this->DataBase->select_sql('article', array('id' => intval($id)));
			if($old_rs === false || $old_rs->eof())
			{
				$this->last_error = $this->Application->Localizer->get_string('internal_error');
				return false;
			}

			$Images = $this->Application->get_module('Images');

			if(file_exists(ROOT .'pub/articles/'. $old_rs->get_field('id') .'/'. $old_rs->get_field('image_filename')))
			{
				$Images->delete('article_image', ROOT .'pub/article/'.$old_rs->get_field('id').'/'. $old_rs->get_field('image_filename'), array('article_id' => $old_rs->get_field('id')));
				@unlink(ROOT .'pub/articles/'. $old_rs->get_field('id') .'/'. $old_rs->get_field('image_filename'));
			}

			if(!$filename = AdminUploader::upload('image_filename', 'pub/articles/'.$id.'/'))
			{
				$this->last_error = $this->Application->Localizer->get_string('cannot_save_file', 'image_filename');
				return false;
			}

			$Images->create('article_image', ROOT .'pub/articles/'.$id.'/'.$filename, array('article_id' => $id));
		}

		if(!$this->DataBase->update_sql('article', $update_arr, array('id' => $id)))
		{
			$this->last_error = $this->Application->Localizer->get_string('database_error');
			return false;
		}


		return $id;
	}


	////Category

	function add_article_category($arr)
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
			'meta_title' => $arr['meta_title'],
			'meta_description' => $arr['meta_description']
		);



		if(!$id = $this->DataBase->insert_sql('article_category', $insert_arr))
		{
			$this->last_error = $this->Application->Localizer->get_string('database_error');
			return false;
		}

		return $id;
	}


	function update_article_category($id, $arr)
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
			'meta_title' => $arr['meta_title'],
			'meta_description' => $arr['meta_description']
		);

		if(!$this->DataBase->update_sql('article_category', $update_arr, array('id' => $id)))
		{
			$this->last_error = $this->Application->Localizer->get_string('database_error');
			return false;
		}


		return $id;
	}

	function get_category()
	{
		$rs = $this->DataBase->select_custom_sql("
		SELECT id, title, position
		FROM article_category
		ORDER BY position
		");

		return ((!$rs->eof()) ? $rs : false);
	}
}
?>