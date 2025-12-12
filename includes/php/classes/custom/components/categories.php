<?php
class CCategories
{
	var $Application;
	var $DataBase;
	var $tv;
	var $last_error;

	function CCategories(&$app)
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
		$rs = $this->DataBase->select_sql('category', array('id' => $id));
		return (($rs !== false && !$rs->eof()) ? $rs : false);
	}

	function get_by_title($title)
	{
		if(!is_string($title) || strlen($title) < 1)
		{
			$this->last_error = $this->Application->Localizer->get_string('invalid_input');
			return false;
		}
		$rs = $this->DataBase->select_sql('category', array('title' => $title));
		return (($rs !== false && !$rs->eof()) ? $rs : false);
	}

	function get_by_uri($uri)
	{
		if(!is_string($uri) || !strlen($uri))
		{
			$this->last_error = $this->Application->Localizer->get_string('invalid_input');
			return false;
		}

		$rs = $this->DataBase->select_sql('category', array('uri' => $uri));
		return (($rs !== false && !$rs->eof()) ? $rs : false);
	}

	function get_by_uri_with_children($uri, $parent_uri = false, $max_lvl = 2)
	{
		if(!is_string($uri) || !strlen($uri) || !is_numeric($max_lvl) || $max_lvl < 1 || $max_lvl > 3 || ($parent_uri && (!is_string($parent_uri) || !strlen($parent_uri))))
		{
			$this->last_error = $this->Application->Localizer->get_string('invalid_input');
			return false;
		}

		$rs = $this->DataBase->select_custom_sql("
		SELECT
			c.id as id,
			c.parent_path as parent_path,
			c.parent_path_uri as parent_path_uri,
			c.title as title,
			c.h1_text as h1_text,
			c.description as description,
			c.meta_title as meta_title,
			c.meta_description as meta_description,
			c.deep as deep,
			c.uri as uri,
			c2.id as child_id,
			c2.parent_path_uri as child_parent_path_uri,
			c2.title as child_title,
            c2.description as child_description,
			c2.uri as child_uri,
			c2.h1_text as child_h1_text,
			c2.image_filename as child_image_filename,
			c2.meta_title as child_meta_title,
			c2.meta_description as child_meta_description
		FROM
			%prefix%category c
			LEFT JOIN %prefix%category c2 on (c2.parent_id = c.id)
		WHERE
			c.uri = '".mysql_real_escape_string($uri)."'
			AND c.deep <= {$max_lvl}
		".(($parent_uri) ? "AND c.parent_path_uri = '".mysql_real_escape_string($parent_uri)."'": 'AND c.parent_id = 0')."
		ORDER by
			c2.position ASC
		");

        if($rs !== false && !$rs->eof())
        {
            while (!$rs->eof())
            {
                if(strpos($rs->get_field('child_description'), '<!-- more -->'))
                {
                    $contents = explode('<!-- more -->', $rs->get_field('child_description'));
                    $desc = close_tags($contents[0]);
                    $rs->set_field('child_description', $desc);
                    $desc2 = close_tags($contents[1]);
                    $rs->set_field('child_description_more', $desc2);
                }
                if(strpos($rs->get_field('description'), '<!-- more -->'))
                {
                    $contents = explode('<!-- more -->', $rs->get_field('description'));
                    $desc = close_tags($contents[0]);
                    $rs->set_field('description', $desc);
                    $desc2 = close_tags(trim($contents[1]));

                    $rs->set_field('description_more', $desc2);
                }
                $rs->next();
            }
            $rs->first();
        }
		return (($rs !== false && !$rs->eof()) ? $rs : false);
	}

	function get_categories_list($deep = 3, $exception_id = false)
	{
		if((!is_numeric($deep) || !intval($deep) || $deep > 3 || $deep < 1) || ($exception_id && (intval($exception_id) < 1 || !is_numeric($exception_id))))
		{
			$this->last_error = $this->Application->Localizer->get_string('invalid_input');
			return false;
		}
		$rs = $this->DataBase->select_custom_sql('
		SELECT 
			id,
			parent_id,
			IF(deep = 2, CONCAT("&nbsp;&nbsp;&nbsp;", title), IF(deep = 3, CONCAT("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;", title), title)) as title
		FROM
			%prefix%category
		WHERE
			deep <= '.intval($deep).'
			'.(($exception_id) ? ' AND id <> '.intval($exception_id) : null).'
		ORDER by parent_id ASC
		');
		if($rs !== false && !$rs->eof())
		{
			$list_rs = new CRecordSet();
			$parent_id = false;
			while (!$rs->eof())
			{
				if(!$parent_id || !$rs->find('parent_id', $parent_id))
				{
					$rs->first();
					if(!$rs->eof())
					{
						$parent_id = $rs->get_field('id');
						$list_rs->add_row(array('id' => $rs->get_field('id'), 'title' => $rs->get_field('title')));
						$rs->delete_row();
						if($rs->find('parent_id', $parent_id))
						{
							$list_rs->add_row(array('id' => $rs->get_field('id'), 'title' => $rs->get_field('title')));
							$rs->delete_row();
						}
					}
				}
				else
				{
					$list_rs->add_row(array('id' => $rs->get_field('id'), 'title' => $rs->get_field('title')));
					$rs->delete_row();
				}
				$rs->first();
			}

			$list_rs->first();
			if($list_rs->eof())
				$this->last_error = $this->Application->Localizer->get_string('internal_error');

			return ((!$list_rs->eof()) ? $list_rs : false);
		}

		$this->last_error = $this->Application->Localizer->get_string('categories_not_found');
		return false;
	}

	function get_categories($deep = false)
	{
		if($deep && (!is_numeric($deep) || !intval($deep) || $deep > 3 || $deep < 1))
		{
			$this->last_error = $this->Application->Localizer->get_string('invalid_input');
			return false;
		}
		$rs = $this->DataBase->select_custom_sql('
		SELECT 
			id,
			parent_id,
			IF(parent_id > 0, CONCAT(parent_path," / ",title), title) as title 
		FROM
			%prefix%category
		'.(($deep) ? 'WHERE deep = '.intval($deep) : null).'
		ORDER by title ASC
		');

		return ((!$rs->eof()) ? $rs : false);
	}

	function get_all()
	{
		$rs = $this->DataBase->select_custom_sql('
		SELECT 
			id,
			parent_id,
			parent_path,
			parent_path_uri,
			title,
			uri,
			deep
		FROM
			%prefix%category
		ORDER by deep ASC, position ASC
		');

		return ((!$rs->eof()) ? $rs : false);
	}

	function get_tree()
	{
		$categories_rs = $this->get_all();
		$categories_arr = array();
		if($categories_rs !== false && !$categories_rs->eof())
		{
			$search_arr = $categories_rs->get_2darray();
			while (!$categories_rs->eof())
			{
                                if(intval($categories_rs->get_field('deep')) == 1)
                                {
                                        if(!array_key_exists($categories_rs->get_field('id'), $categories_arr))
                                        {
                                                row_to_vars($categories_rs, $categories_arr[$categories_rs->get_field('id')]);

                                                if(!isset($categories_arr[$categories_rs->get_field('id')]['children']) || !is_array($categories_arr[$categories_rs->get_field('id')]['children']))
                                                        $categories_arr[$categories_rs->get_field('id')]['children'] = array();
                                        }
                                }
                                elseif (intval($categories_rs->get_field('deep')) == 2)
                                {
                                        if(array_key_exists($categories_rs->get_field('parent_id'), $categories_arr))
                                        {
                                                if(!isset($categories_arr[$categories_rs->get_field('parent_id')]['children']) || !is_array($categories_arr[$categories_rs->get_field('parent_id')]['children']))
                                                        $categories_arr[$categories_rs->get_field('parent_id')]['children'] = array();

                                                if(!array_key_exists($categories_rs->get_field('id'), $categories_arr[$categories_rs->get_field('parent_id')]['children']))
                                                {
                                                        row_to_vars($categories_rs, $categories_arr[$categories_rs->get_field('parent_id')]['children'][$categories_rs->get_field('id')]);

                                                        if(!isset($categories_arr[$categories_rs->get_field('parent_id')]['children'][$categories_rs->get_field('id')]['children']) || !is_array($categories_arr[$categories_rs->get_field('parent_id')]['children'][$categories_rs->get_field('id')]['children']))
                                                                $categories_arr[$categories_rs->get_field('parent_id')]['children'][$categories_rs->get_field('id')]['children'] = array();
                                                }
                                        }
                                }
                                elseif(intval($categories_rs->get_field('deep')) == 3)
                                {
                                        $lvl2_k = array_search_assoc($search_arr, array('id' => $categories_rs->get_field('parent_id')));
                                        if($lvl2_k !== false)
                                        {
                                                if(array_key_exists($search_arr[$lvl2_k]['parent_id'], $categories_arr))
                                                {
                                                        if(!isset($categories_arr[$search_arr[$lvl2_k]['parent_id']]['children']) || !is_array($categories_arr[$search_arr[$lvl2_k]['parent_id']]['children']))
                                                                $categories_arr[$search_arr[$lvl2_k]['parent_id']]['children'] = array();

                                                        if(!isset($categories_arr[$search_arr[$lvl2_k]['parent_id']]['children'][$categories_rs->get_field('parent_id')]) || !is_array($categories_arr[$search_arr[$lvl2_k]['parent_id']]['children'][$categories_rs->get_field('parent_id')]))
                                                                $categories_arr[$search_arr[$lvl2_k]['parent_id']]['children'][$categories_rs->get_field('parent_id')] = array();

                                                        if(!isset($categories_arr[$search_arr[$lvl2_k]['parent_id']]['children'][$categories_rs->get_field('parent_id')]['children']) || !is_array($categories_arr[$search_arr[$lvl2_k]['parent_id']]['children'][$categories_rs->get_field('parent_id')]['children']))
                                                                $categories_arr[$search_arr[$lvl2_k]['parent_id']]['children'][$categories_rs->get_field('parent_id')]['children'] = array();

                                                        if(!array_key_exists($categories_rs->get_field('id'), $categories_arr[$search_arr[$lvl2_k]['parent_id']]['children'][$categories_rs->get_field('parent_id')]['children']))
                                                        {
                                                                row_to_vars($categories_rs, $categories_arr[$search_arr[$lvl2_k]['parent_id']]['children'][$categories_rs->get_field('parent_id')]['children'][$categories_rs->get_field('id')]);
                                                        }
                                                }
                                        }
				}
				$categories_rs->next();
			}
		}

		return ((!empty($categories_arr)) ? $categories_arr : false);
	}

	function add_category($arr)
	{
		if(!is_array($arr))
		{
			$this->last_error = $this->Application->Localizer->get_string('invalid_input');
			return false;
		}

		$double_rs = $this->DataBase->select_custom_sql('SELECT count(id) as cnt FROM %prefix%category WHERE (title = "'.mysql_real_escape_string($arr['title']).'" OR uri = "'.mysql_real_escape_string($arr['uri']).'") AND parent_id = "'.intval($arr['parent_id']).'"');
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

		if(intval($arr['parent_id']) > 0)
		{
			$parent_rs = $this->get($arr['parent_id']);
			if($parent_rs == false || $parent_rs->eof())
			{
				$this->last_error = $this->Application->Localizer->get_string('parent_not_exists');
				return false;
			}
			elseif($parent_rs->get_field('deep') > 2)
			{
				$this->last_error = $this->Application->Localizer->get_string('maximum_3_levels_menu');
				return false;
			}

			$arr['parent_path'] = (($parent_rs->get_field('parent_id') > 0) ? $parent_rs->get_field('parent_path').' / '.$parent_rs->get_field('title') : $parent_rs->get_field('title'));
			$arr['parent_path_uri'] = (($parent_rs->get_field('parent_id') > 0) ? $parent_rs->get_field('parent_path_uri').'/'.$parent_rs->get_field('uri') : $parent_rs->get_field('uri'));
			$arr['deep'] = (($parent_rs->get_field('parent_id') > 0) ? 3 : 2);
		}
		else
			$arr['deep'] = 1;

		$insert_arr = array(
			'parent_id' => intval($arr['parent_id']),
			'parent_path' => $arr['parent_path'],
			'parent_path_uri' => $arr['parent_path_uri'],
			'title' => $arr['title'],
            'h1_text' => $arr['h1_text'],
			'uri' => $arr['uri'],
			'image_filename' => $arr['image_filename'],
			'description' => $arr['description'],
			'meta_title' => $arr['meta_title'],
			'meta_description' => $arr['meta_description'],
			'deep' => $arr['deep'],
            'position' => $arr['position'],
            'dollar_currency' => $arr['dollar_currency'],
            'rouble_currency' => $arr['rouble_currency']
		);

		if(!$id = $this->DataBase->insert_sql('category', $insert_arr))
		{
			$this->last_error = $this->Application->Localizer->get_string('database_error');
			return false;
		}

		if(!$filename = AdminUploader::upload('image_filename', 'pub/categories/'.$id.'/'))
		{
			$this->last_error = $this->Application->Localizer->get_string('cannot_save_file', 'image_filename');
			return false;
		}

		$Images = $this->Application->get_module('Images');
		$Images->create('category_image', ROOT .'pub/categories/'.$id.'/'.$filename, array('category_id' => $id));


		return $id;
	}

	function update_category($id, $arr)
	{
		if(!is_array($arr) || !is_numeric($id) || !intval($id))
		{
			$this->last_error = $this->Application->Localizer->get_string('invalid_input');
			return false;
		}

		$double_rs = $this->DataBase->select_custom_sql('SELECT count(id) as cnt FROM %prefix%category WHERE (title = "'.mysql_real_escape_string($arr['title']).'" OR uri = "'.mysql_real_escape_string($arr['uri']).'") AND parent_id = "'.intval($arr['parent_id']).'" AND id <> '.intval($id));
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

		if(intval($arr['parent_id']) > 0)
		{
			$parent_rs = $this->get($arr['parent_id']);
			if($parent_rs == false || $parent_rs->eof())
			{
				$this->last_error = $this->Application->Localizer->get_string('parent_not_exists');
				return false;
			}
			elseif($parent_rs->get_field('deep') > 2)
			{
				$this->last_error = $this->Application->Localizer->get_string('maximum_3_levels_menu');
				return false;
			}

			$arr['parent_path'] = (($parent_rs->get_field('parent_id') > 0) ? $parent_rs->get_field('parent_path').' / '.$parent_rs->get_field('title') : $parent_rs->get_field('title'));
			$arr['parent_path_uri'] = (($parent_rs->get_field('parent_id') > 0) ? $parent_rs->get_field('parent_path_uri').'/'.$parent_rs->get_field('uri') : $parent_rs->get_field('uri'));
			$arr['deep'] = (($parent_rs->get_field('parent_id') > 0) ? 3 : 2);
		}
		else
			$arr['deep'] = 1;

		$update_arr = array(
			'parent_id' => intval($arr['parent_id']),
			'parent_path' => $arr['parent_path'],
			'parent_path_uri' => $arr['parent_path_uri'],
			'title' => $arr['title'],
            'h1_text' => $arr['h1_text'],
			'uri' => $arr['uri'],
			'description' => $arr['description'],
			'meta_title' => $arr['meta_title'],
			'meta_description' => $arr['meta_description'],
			'deep' => $arr['deep'],
            'position' => $arr['position'],
            'dollar_currency' => $arr['dollar_currency'],
            'rouble_currency' => $arr['rouble_currency']
		);

		if ($arr['image_filename'] !== '')
		{
			$update_arr['image_filename'] = $arr['image_filename'];
			$old_rs = $this->DataBase->select_sql('category', array('id' => intval($id)));
			if($old_rs === false || $old_rs->eof())
			{
				$this->last_error = $this->Application->Localizer->get_string('internal_error');
				return false;
			}

			$Images = $this->Application->get_module('Images');

			if(file_exists(ROOT .'pub/categories/'. $old_rs->get_field('id') .'/'. $old_rs->get_field('image_filename')))
			{
				$Images->delete('category_image', ROOT .'pub/categories/'.$old_rs->get_field('id').'/'. $old_rs->get_field('image_filename'), array('category_id' => $old_rs->get_field('id')));
				@unlink(ROOT .'pub/categories/'. $old_rs->get_field('id') .'/'. $old_rs->get_field('image_filename'));
			}

			if(!$filename = AdminUploader::upload('image_filename', 'pub/categories/'.$id.'/'))
			{
				$this->last_error = $this->Application->Localizer->get_string('cannot_save_file', 'image_filename');
				return false;
			}

			$Images->create('category_image', ROOT .'pub/categories/'.$id.'/'.$filename, array('category_id' => $id));
		}
		

		$old_rs = $this->DataBase->select_sql('category', array('id' => $id));
		if($old_rs == false || $old_rs->eof())
		{
			$this->last_error = $this->Application->Localizer->get_string('internal_error');
			return false;
		}



		if(!$this->DataBase->update_sql('category', $update_arr, array('id' => $id)))
		{
			$this->last_error = $this->Application->Localizer->get_string('database_error');
			return false;
		}

		if(!$this->rebuild_children($id))
			return false;

		return $id;
	}

	function rebuild_children($id)
	{
		if(!is_numeric($id) || !intval($id))
		{
			$this->last_error = $this->Application->Localizer->get_string('invalid_input');
			return false;
		}

		$child_rs = $this->DataBase->select_custom_sql("
		SELECT
			c.parent_path,
			c.parent_path_uri,
			c.title,
			c.uri,
			c2.id as id
		FROM
			%prefix%category c
			JOIN %prefix%category c2 on (c2.parent_id = c.id)
		WHERE
			c.id = '".intval($id)."' 
		");

		if($child_rs !== false && !$child_rs->eof())
		{
			while (!$child_rs->eof())
			{
				$update_arr = array(
					'parent_path' => ((strlen($child_rs->get_field('parent_path')) > 0) ? $child_rs->get_field('parent_path').' / '.$child_rs->get_field('title') : $child_rs->get_field('title')),
					'parent_path_uri' => ((strlen($child_rs->get_field('parent_path_uri')) > 0) ? $child_rs->get_field('parent_path_uri').'/'.$child_rs->get_field('uri') : $child_rs->get_field('uri')),
				);
				if(!$this->DataBase->update_sql('category', $update_arr, array('id' => $child_rs->get_field('id'))))
				{
					$this->last_error = $this->Application->Localizer->get_string('database_error');
					return false;
				}
				if(!$this->rebuild_children($child_rs->get_field('id')))
					return false;

				$child_rs->next();
			}
		}

		return true;
	}
};
?>