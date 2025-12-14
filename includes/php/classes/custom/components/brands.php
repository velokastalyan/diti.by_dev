<?
class CBrands
{
	var $Application;
	var $DataBase;
	var $tv;
	var $last_error;

	function CBrands(&$app)
	{
		$this->Application = &$app;
		$this->tv = &$app->tv;
		$this->DataBase = &$this->Application->DataBase;
	}

	function get_last_error()
	{
		return $this->last_error;
	}

	function get($id)
	{
		if(!is_numeric($id) || intval($id) < 1)
		{
			$this->last_error = $this->Application->Localizer->get_string('invalid_input');
			return false;
		}
		$rs = $this->DataBase->select_sql('brand', array('id' => $id));
		return (($rs !== false && !$rs->eof()) ? $rs : false);
	}

	function get_all()
{
    // Выполняем SQL-запрос для выборки всех брендов, отсортированных по алфавиту
    $rs = $this->DataBase->select_custom_sql("SELECT * FROM %prefix%brand ORDER BY title ASC");
    
    // Проверяем, успешно ли выполнен запрос, и возвращаем результат
    return (($rs !== false && !$rs->eof()) ? $rs : false);
}


	function get_by_title($title)
	{
		if(!is_string($title) || strlen($title) < 1)
		{
			$this->last_error = $this->Application->Localizer->get_string('invalid_input');
			return false;
		}
		$rs = $this->DataBase->select_sql('brand', array('title' => $title));
		return (($rs !== false && !$rs->eof()) ? $rs : false);
	}

	function get_by_uri($uri)
	{
		if(!is_string($uri) || strlen($uri) < 1)
		{
			$this->last_error = $this->Application->Localizer->get_string('invalid_input');
			return false;
		}
		$rs = $this->DataBase->select_sql('brand', array('uri' => $uri));
		return (($rs !== false && !$rs->eof()) ? $rs : false);
	}

        function get_by_category_uri($uri, $parentPathUri = null)
        {
                if (!is_string($uri) || strlen($uri) < 1)
                {
                        $this->last_error = $this->Application->Localizer->get_string('invalid_input');
                        return false;
                }

                $parentCondition = '';
                if (is_string($parentPathUri) && strlen($parentPathUri) > 0)
                {
                        $parentCondition = " AND c.parent_path_uri = '" . mysql_real_escape_string($parentPathUri) . "'";
                }

                $rs = $this->DataBase->select_custom_sql("SELECT
                b.id as id,
                b.title as title,
                b.uri as uri
                FROM %prefix%brand as b
                JOIN %prefix%category as c on (c.uri = '".mysql_real_escape_string($uri)."'" . $parentCondition . ")
                JOIN %prefix%product as p on (p.category_id = c.id)
                WHERE p.brand_id = b.id
                GROUP by b.id
                ");

                return (($rs !== false && !$rs->eof()) ? $rs : false);
        }

	function get_brand_for_sale()
	{
		$rs = $this->DataBase->select_custom_sql("SELECT
		b.id as id,
		b.title as title,
		b.uri as uri
		FROM %prefix%brand as b
		JOIN %prefix%product as p on (p.brand_id = b.id)
		WHERE b.id = p.brand_id and p.is_sale = 1
		GROUP by b.id
		");

		return (($rs != false && !$rs->eof()) ? $rs : false);
	}

	function get_brand_footer()
	{
		$rs = $this->DataBase->select_custom_sql("SELECT
		b.id as id,
		b.title as title,
		b.link as link,
		b.uri as uri,
		b.image_filename as image_filename
		FROM %prefix%brand_footer as b
		WHERE b.is_display = '1'
		LIMIT 999
		");

		return (($rs != false && !$rs->eof()) ? $rs : false);
	}

        function get_category_brands($category_id, $sex=-1, $price_start=-1, $price_end=-1)
        {
                if(!is_numeric($category_id) || intval($category_id) < 0 || !is_numeric($sex) || !is_numeric($price_start) || !is_numeric($price_end))
                {
                        $this->last_error = $this->Application->Localizer->get_string('invalid_input');
                        return false;
                }

                $category_id = intval($category_id);

                $category_info = $this->DataBase->select_custom_sql("SELECT id, uri, parent_path_uri FROM %prefix%category WHERE id = '".$category_id."' LIMIT 1");

                if ($category_info === false || $category_info->eof())
                {
                        return false;
                }

                $category_path = trim($category_info->get_field('parent_path_uri'), '/');
                $category_uri = trim($category_info->get_field('uri'), '/');
                $full_path = trim(($category_path ? $category_path.'/' : '').$category_uri, '/');

                $category_where = "c.id = '".$category_id."'";

                if ($full_path !== '')
                {
                        $full_path_escaped = mysql_real_escape_string($full_path);
                        $category_where .= " OR c.parent_path_uri = '".$full_path_escaped."' OR c.parent_path_uri LIKE '".$full_path_escaped."/%'";
                }

                if ($sex == -1) $q = ""; else $q = " and p.sex = '".$sex."'";
                if ($price_start == -1 || $price_end == -1) $q2 = ""; else $q2 = " and ( price between '".$price_start."' and '".$price_end."')";

                $rs = $this->DataBase->select_custom_sql("
                SELECT
                        b.id as id,
                        b.title as title,
                        b.uri as uri
                FROM
                        %prefix%brand b
                        JOIN %prefix%product p on (p.brand_id = b.id)
                        JOIN %prefix%category c on (p.category_id = c.id)
                WHERE
                        (".$category_where.")
                        ".$q.$q2."
                GROUP by b.id
                ORDER by title ASC
                ");

		return (($rs !== false && !$rs->eof()) ? $rs : false);
	}

	function add_brand($arr)
	{
		if(!is_array($arr))
		{
			$this->last_error = $this->Application->Localizer->get_string('invalid_input');
			return false;
		}

		$double_rs = $this->DataBase->select_custom_sql('SELECT count(id) as cnt FROM %prefix%brand WHERE (title = "'.mysql_real_escape_string($arr['title']).'" OR uri = "'.mysql_real_escape_string($arr['uri']).'")');
		if($double_rs == false)
		{
			$this->last_error = $this->Application->Localizer->get_string('database_error');
			return false;
		}

		if($double_rs->get_field('cnt') > 0)
		{
			$this->last_error = $this->Application->Localizer->get_string('object_exists');
			return false;
		}

		$insert_arr = array(
			'title' => $arr['title'],
			'uri' => $arr['uri'],
			'description' => $arr['description'],
			'link' => $arr['link'],
			'image_filename' => $arr['image_filename'],
			'meta_title' => $arr['meta_title'],
			'meta_description' => $arr['meta_description']
		);

		if(!$id = $this->DataBase->insert_sql('brand', $insert_arr))
		{
			$this->last_error = $this->Application->Localizer->get_string('database_error');
			return false;
		}

		if(!$filename = AdminUploader::upload('image_filename', 'pub/brands/'.$id.'/'))
		{
			$this->last_error = $this->Application->Localizer->get_string('cannot_save_file', 'image_filename');
			return false;
		}

		$Images = $this->Application->get_module('Images');
		$Images->create('brand_image', ROOT .'pub/brands/'.$id.'/'.$filename, array('brand_id' => $id));

		return $id;
	}

	function update_brand($id, $arr)
	{
		if(!is_array($arr) || !is_numeric($id) || !intval($id))
		{
			$this->last_error = $this->Application->Localizer->get_string('invalid_input');
			return false;
		}

		$double_rs = $this->DataBase->select_custom_sql('SELECT count(id) as cnt FROM %prefix%brand WHERE (title = "'.mysql_real_escape_string($arr['title']).'" OR uri = "'.mysql_real_escape_string($arr['uri']).'") AND id <> '.intval($id));
		if($double_rs == false)
		{
			$this->last_error = $this->Application->Localizer->get_string('database_error');
			return false;
		}

		if($double_rs->get_field('cnt') > 0)
		{
			$this->last_error = $this->Application->Localizer->get_string('object_exists');
			return false;
		}

		$update_arr = array(
			'title' => $arr['title'],
			'uri' => $arr['uri'],
			'description' => $arr['description'],
			'link' => $arr['link'],
			'meta_title' => $arr['meta_title'],
			'meta_description' => $arr['meta_description']
		);

		if ($arr['image_filename'] !== '')
		{
			$update_arr['image_filename'] = $arr['image_filename'];
			$old_rs = $this->DataBase->select_sql('brand', array('id' => intval($id)));
			if($old_rs === false || $old_rs->eof())
			{
				$this->last_error = $this->Application->Localizer->get_string('internal_error');
				return false;
			}

			$Images = $this->Application->get_module('Images');

			if(file_exists(ROOT .'pub/brands/'. $old_rs->get_field('id') .'/'. $old_rs->get_field('image_filename')))
			{
				$Images->delete('brand_image', ROOT .'pub/brands/'.$old_rs->get_field('id').'/'. $old_rs->get_field('image_filename'), array('brand_id' => $old_rs->get_field('id')));
				@unlink(ROOT .'pub/brands/'. $old_rs->get_field('id') .'/'. $old_rs->get_field('image_filename'));
			}

			if(!$filename = AdminUploader::upload('image_filename', 'pub/brands/'.$id.'/'))
			{
				$this->last_error = $this->Application->Localizer->get_string('cannot_save_file', 'image_filename');
				return false;
			}

			$Images->create('brand_image', ROOT .'pub/brands/'.$id.'/'.$filename, array('brand_id' => $id));

		}

		$old_rs = $this->DataBase->select_sql('brand', array('id' => $id));
		if($old_rs == false || $old_rs->eof())
		{
			$this->last_error = $this->Application->Localizer->get_string('internal_error');
			return false;
		}



		if(!$id = $this->DataBase->update_sql('brand', $update_arr, array('id' => $id)))
		{
			$this->last_error = $this->Application->Localizer->get_string('database_error');
			return false;
		}

		return $id;
	}

	function add_brand_footer($arr)
	{
		if(!is_array($arr))
		{
			$this->last_error = $this->Application->Localizer->get_string('invalid_input');
			return false;
		}

		$double_rs = $this->DataBase->select_custom_sql('SELECT count(id) as cnt FROM %prefix%brand_footer WHERE (title = "'.mysql_real_escape_string($arr['title']).'" OR uri = "'.mysql_real_escape_string($arr['uri']).'")');
		if($double_rs == false)
		{
			$this->last_error = $this->Application->Localizer->get_string('database_error');
			return false;
		}

		if($double_rs->get_field('cnt') > 0)
		{
			$this->last_error = $this->Application->Localizer->get_string('object_exists');
			return false;
		}

		$insert_arr = array(
			'title' => $arr['title'],
			'uri' => $arr['uri'],
			'description' => $arr['description'],
			'link' => $arr['link'],
			'image_filename' => $arr['image_filename'],
			'is_display' => $arr['is_display']
		);

		if(!$id = $this->DataBase->insert_sql('brand_footer', $insert_arr))
		{
			$this->last_error = $this->Application->Localizer->get_string('database_error');
			return false;
		}

		if(!$filename = AdminUploader::upload('image_filename', 'pub/footer/'.$id.'/'))
		{
			$this->last_error = $this->Application->Localizer->get_string('cannot_save_file', 'image_filename');
			return false;
		}

		$Images = $this->Application->get_module('Images');
		$Images->create('brand_footer', ROOT .'pub/footer/'.$id.'/'.$filename, array('brand_id' => $id));

		return $id;
	}


	function update_brand_footer($id, $arr)
	{
		if(!is_array($arr) || !is_numeric($id) || !intval($id))
		{
			$this->last_error = $this->Application->Localizer->get_string('invalid_input');
			return false;
		}

		$double_rs = $this->DataBase->select_custom_sql('SELECT count(id) as cnt FROM %prefix%brand_footer WHERE (title = "'.mysql_real_escape_string($arr['title']).'" OR uri = "'.mysql_real_escape_string($arr['uri']).'") AND id <> '.intval($id));
		if($double_rs == false)
		{
			$this->last_error = $this->Application->Localizer->get_string('database_error');
			return false;
		}

		if($double_rs->get_field('cnt') > 0)
		{
			$this->last_error = $this->Application->Localizer->get_string('object_exists');
			return false;
		}

		$update_arr = array(
			'title' => $arr['title'],
			'uri' => $arr['uri'],
			'description' => $arr['description'],
			'link' => $arr['link'],
			'is_display' => intval($arr['is_display'])
		);

		if ($arr['image_filename'] !== '')
		{
			$update_arr['image_filename'] = $arr['image_filename'];
			$old_rs = $this->DataBase->select_sql('brand_footer', array('id' => intval($id)));
			if($old_rs === false || $old_rs->eof())
			{
				$this->last_error = $this->Application->Localizer->get_string('internal_error');
				return false;
			}

			$Images = $this->Application->get_module('Images');

			if(file_exists(ROOT .'pub/brands/footer/'. $old_rs->get_field('id') .'/'. $old_rs->get_field('image_filename')))
			{
				$Images->delete('brand_footer', ROOT .'pub/brands/footer/'.$old_rs->get_field('id').'/'. $old_rs->get_field('image_filename'), array('brand_id' => $old_rs->get_field('id')));
				@unlink(ROOT .'pub/brands/footer/'. $old_rs->get_field('id') .'/'. $old_rs->get_field('image_filename'));
			}

			if(!$filename = AdminUploader::upload('image_filename', 'pub/brands/footer/'.$id.'/'))
			{
				$this->last_error = $this->Application->Localizer->get_string('cannot_save_file', 'image_filename');
				return false;
			}

			$Images->create('brand_footer', ROOT .'pub/brands/footer/'.$id.'/'.$filename, array('brand_id' => $id));

		}

		$old_rs = $this->DataBase->select_sql('brand_footer', array('id' => $id));
		if($old_rs == false || $old_rs->eof())
		{
			$this->last_error = $this->Application->Localizer->get_string('internal_error');
			return false;
		}



		if(!$id = $this->DataBase->update_sql('brand_footer', $update_arr, array('id' => $id)))
		{
			$this->last_error = $this->Application->Localizer->get_string('database_error');
			return false;
		}

		return $id;
	}


    function get_snoubord_brands($category_id, $sex=-1, $price_start=-1, $price_end=-1)
    {
        if(!is_numeric($sex) || !is_numeric($price_start) || !is_numeric($price_end))
        {
            $this->last_error = $this->Application->Localizer->get_string('invalid_input');
            return false;
        }

        if ($sex == -1) $q = ""; else $q = " and p.sex = '".$sex."'";
        if ($price_start == -1 || $price_end == -1) $q2 = ""; else $q2 = " and ( price between '".$price_start."' and '".$price_end."')";

        $rs = $this->DataBase->select_custom_sql("
		SELECT
			b.id as id,
			b.title as title,
			b.uri as uri
		FROM
			%prefix%brand b
			JOIN %prefix%product p on (p.brand_id = b.id)
		WHERE
			p.category_id in (SELECT c.id
			FROM %prefix%category c
			WHERE c.parent_path_uri = '".$category_id."')
			".$q.$q2."
		GROUP by b.id
		ORDER by title ASC
		");
        return (($rs !== false && !$rs->eof()) ? $rs : false);
    }

}
?>