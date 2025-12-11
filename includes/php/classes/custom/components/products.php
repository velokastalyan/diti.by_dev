<?php
class CProducts
{
	var $Application;
	var $DataBase;
	var $tv;
	var $last_error;

	function CProducts(CApp &$app)
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
		$rs = $this->DataBase->select_sql('product', array('id' => $id));
		return (($rs !== false && !$rs->eof()) ? $rs : false);
	}

    function get_by_uri($uri)
    {
        $rs = $this->DataBase->select_sql('product', array('uri' => $uri));
        return (($rs !== false && !$rs->eof()) ? $rs : false);
    }

	function get_all()
	{
		$rs = $this->DataBase->select_custom_sql("SELECT
		p.id,
		p.title,
		p.uri,
		p.price,
		p.discount,
		p.is_sale,
		p.is_new,
		c.uri as cat_uri,
		c.parent_path_uri as parent_path_uri,
		c.dollar_currency,
		c.rouble_currency,
		i.image_filename,
		i.is_core
		FROM %prefix%product p
		JOIN %prefix%product_image i on ((i.product_id = p.id) and (i.is_core = 1))
		JOIN %prefix%category c on ((c.id = p.category_id))
		ORDER BY id DESC");
		return (($rs !== false && !$rs->eof()) ? $rs : false);
	}

	function get_product_by_id($id)
	{
		if (!is_numeric($id))
		{
			$this->last_error = $this->Application->Localizer->get_string('invalid_input');
			return false;
		}

		$rs = $this->DataBase->select_custom_sql("
		SELECT
			p.id as id,
			p.title as title,
			p.category_id as category,
			p.uri as uri,
			p.price as price,
			b.title as b_title,
			i.image_filename as image_filename,
			c.parent_id as parent_id,
			(
			    SELECT c2.uri as c2_uri FROM %prefix%category as c2 WHERE c2.id = c.parent_id LIMIT 1
			) as c_parent_uri,
			(
			    SELECT c2.parent_id as c2_id FROM %prefix%category as c2 WHERE c2.id = c.parent_id LIMIT 1
			) as c_parent_id,
			(
			    SELECT c2.title as c2_title FROM %prefix%category as c2 WHERE c2.id = c.parent_id LIMIT 1
			) as c_parent_title,
			(
			    SELECT c3.uri as c3_uri FROM %prefix%category as c3 WHERE c3.id = c_parent_id LIMIT 1
			) as c_parent_path_uri,
			(
			    SELECT c3.title as c3_title FROM %prefix%category as c3 WHERE c3.id = c_parent_id LIMIT 1
			) as c_parent_path_title,
			c.uri as category_uri,
			c.dollar_currency,
		    c.rouble_currency,
			c.title as category_title
		FROM %prefix%product p
		JOIN %prefix%brand b on (b.id = p.brand_id)
		JOIN %prefix%product_image i on (i.product_id = p.id)
		JOIN %prefix%category c on (c.id = p.category_id)
		WHERE p.id = '".mysql_real_escape_string($id)."'
		");

		return (($rs != false && !$rs->eof()) ? $rs : false);
	}

    function get_product_by_arr_id($arr)
    {
        if (!is_array($arr) && empty($arr))
        {
            $this->last_error = $this->Application->Localizer->get_string('invalid_input');
            return false;
        }

        $q = '';
        $ord = '';
		$j=0;
        for($i = 0; $i < count($arr); $i++)
        {
			if ($arr[$i] != '' && is_numeric($arr[$i]))
            {
                $q.= ",'".$arr[$i]."'";
				$j++;
				if ($j >= 20) break;
            }
        }

        $rs = $this->DataBase->select_custom_sql("SELECT
        p.id,
		p.title,
		p.uri,
		p.price,
		p.discount,
		c.uri as cat_uri,
		c.parent_path_uri as parent_path_uri,
		c.dollar_currency,
		c.rouble_currency,
		i.image_filename as image
		FROM %prefix%product p
		JOIN %prefix%product_image i on ((i.product_id = p.id))
		JOIN %prefix%category c on ((c.id = p.category_id))
		WHERE i.is_core = 1 and p.id in('-1'".$q.")
		ORDER BY FIELD (p.id".$q.")
		");

        return (($rs !== false && !$rs->eof()) ? $rs : false);
    }

	function get_recommend()
	{
		$rs = $this->DataBase->select_custom_sql("SELECT
		p.id,
		p.title,
		p.uri,
		p.price,
		p.discount,
		p.is_recommend,
		p.is_sale,
		p.is_new,
		p.category_id,
		c.uri as cat_uri,
		c.parent_path_uri as parent_path_uri,
		c.dollar_currency,
		c.rouble_currency,
		b.title as brand,
		i.image_filename as image
		FROM %prefix%product p
		JOIN %prefix%brand b on ((p.brand_id = b.id))
		JOIN %prefix%product_image i on ((i.product_id = p.id))
		JOIN %prefix%category c on ((p.category_id = c.id))
		WHERE p.is_recommend = 1 and i.is_core = 1
		ORDER BY id DESC");
		return (($rs !== false && !$rs->eof()) ? $rs: false);
	}

	function get_category_products($cond_arr, $sort_by, $page, $on_page, $brand, $price_start, $price_end, $sex, $wheel, $suspension,$tormoza,$vilka,$rama,$year)
	{
		if(!is_array($cond_arr) || !strlen($cond_arr['c1_uri']) || !strlen($cond_arr['c2_uri']) || !in_array($sort_by, array('title', 'price')) || !is_numeric($page) || intval($page) < 1 || !is_numeric($brand) || !is_numeric($price_start) || !is_numeric($price_end) || !is_numeric($sex))
		{
			$this->last_error = $this->Application->Localizer->get_string('invalid_input');
			return false;
		}

                $nameCache = $cond_arr['c1_uri'].$cond_arr['c2_uri'].$cond_arr['c3_uri'].$sort_by.$page.$on_page.$brand.$price_start.$price_end.$sex.$wheel.$suspension.$tormoza.$vilka.$rama.$year . '.tmp';

                $cache_my = new CacheFileMY();
                $rs = $cache_my->read($nameCache);

                if (empty($rs)) {

		$q = $q1 = $q2 =  $q3 = $q4 = $q5 = $q6 = $q7 = $q8 = $q9 = '';

		if (($brand != -1)) $q = ' and b.id = "'.mysql_real_escape_string($brand).'"';

		if (($price_start > 0) && ($price_end > 0)) $q1 = ' and (IF(discount > 0, discount between "'.mysql_real_escape_string($price_start).'" and "'.mysql_real_escape_string($price_end).'", price between "'.mysql_real_escape_string($price_start).'" and "'.mysql_real_escape_string($price_end).'"))';



		if ($sex != -1) $q3 = ' and sex="'.mysql_real_escape_string($sex).'"';
        if ($wheel != -1) $q4 = ' and wheel="'.mysql_real_escape_string($wheel).'"';
        if ($suspension != -1) $q5 = ' and suspension="'.mysql_real_escape_string($suspension).'"';
        if ($tormoza != -1) $q6 = ' and tormoza="'.mysql_real_escape_string($tormoza).'"';
        if ($vilka != -1) $q7 = ' and vilka="'.mysql_real_escape_string($vilka).'"';
        if ($rama != -1) $q8 = ' and rama="'.mysql_real_escape_string($rama).'"';
        if ($year != -1) $q9 = ' and year="'.mysql_real_escape_string($year).'"';

		if (strlen($cond_arr['c3_uri'])) $category_where = 'c.parent_path_uri = "'.mysql_real_escape_string($cond_arr['c1_uri'].'/'.$cond_arr['c2_uri']).'" AND
			c.uri = "'.mysql_real_escape_string($cond_arr['c3_uri']).'"';
		else $category_where = 'c.parent_path_uri = "'.mysql_real_escape_string($cond_arr['c1_uri']).'" AND
			c.uri = "'.mysql_real_escape_string($cond_arr['c2_uri']).'"';
		$category_where_cnt = str_replace('c.', 'c2.', $category_where);
        if ($sort_by == 'price') $sort_by = 'actual_price';

		$rs = $this->DataBase->select_custom_sql('
		SELECT
			p.id as id,
			c.parent_path as c_parent_path,
			c.parent_path_uri as c_parent_path_uri,
			c.title as c3_title,
			c.uri as c3_uri,
			c.dollar_currency,
		        c.rouble_currency,
			b.title as brand,
			p.title as title,
			p.price as price,
			p.discount as discount,
			IF(p.discount > 0, p.discount, p.price) as actual_price,
			p.uri as uri,
			p.is_sale as is_sale,
			p.is_new as is_new,
			p.sex as sex,
			p.wheel as wheel,
			p.suspension as suspension,
			i.image_filename as image_filename,
			(
				SELECT count(p2.id) FROM %prefix%product p2 JOIN %prefix%category c2 on (p2.category_id = c2.id)
				JOIN %prefix%brand b2 on (p2.brand_id = b2.id)
				 WHERE '.$category_where_cnt.'
				'.str_replace("b.","b2.",$q).$q1.$q3.$q4.$q5.$q6.$q7.$q8.$q9.'
			) as cnt_products
		FROM
			%prefix%product p
			JOIN %prefix%category c on (p.category_id = c.id)
			JOIN %prefix%brand b on (p.brand_id = b.id)
			LEFT JOIN %prefix%product_image i on (i.product_id = p.id AND i.is_core = 1)
		WHERE
                       '.$category_where.'
			'.$q.$q1.$q3.$q4.$q5.$q6.$q7.$q8.$q9.'
		ORDER BY
			'.mysql_real_escape_string($sort_by).' ASC
		', $on_page!='all' ? array($page, $on_page) : null);

    $cache_my->write($nameCache, $rs);
}
		return (($rs !== false && !$rs->eof()) ? $rs : false);
	}

	function get_product_by_uri($uri, $cat_uries)
	{
		if(!is_string($uri) || !is_array($cat_uries) || !strlen($uri) || empty($cat_uries) || count($cat_uries) < 2)
		{
			$this->last_error = $this->Application->Localizer->get_string('invalid_input');
			return false;
		}

		foreach ($cat_uries as $c_uri)
			if(!is_string($c_uri) || !strlen($c_uri))
			{
				$this->last_error = $this->Application->Localizer->get_string('invalid_input');
				return false;
			}

		if ($cat_uries[2] != '')
			$wh = "AND c.uri = '".mysql_real_escape_string($cat_uries[2])."' AND c.parent_path_uri = '".mysql_real_escape_string($cat_uries[0].'/'.$cat_uries[1])."'";
		else
			$wh = "AND c.uri = '".mysql_real_escape_string($cat_uries[1])."' AND c.parent_path_uri = '".mysql_real_escape_string($cat_uries[0])."'";

		$rs = $this->DataBase->select_custom_sql("
		SELECT
			p.id as id,
			p.title as title,
			p.uri as uri,
			p.rate as rate,
			p.size as size,
			p.price as price,
			p.discount as discount,
			p.description as description,
			p.video as video,
			p.meta_title as meta_title,
			p.meta_description as meta_description,
			p.is_sale as is_sale,
			p.is_new as is_new,
			p.status as status,
			p.size_arr as size_arr,
			p.is_delivery_minsk as is_delivery_minsk,
			p.is_delivery_moscow as is_delivery_moscow,
			p.is_delivery_belarus as is_delivery_belarus,
			p.description_delivery_minsk as description_delivery_minsk,
			p.description_delivery_belarus as description_delivery_belarus,
			p.description_delivery_moscow as description_delivery_moscow,
			c.id as c_id,
			c.parent_path as c_parent_path,
			c.title as c_title,
			c.uri as c_uri,
			c.dollar_currency,
		    c.rouble_currency,
			b.id as b_id,
			b.title as b_title,
			b.uri as b_uri,
			b.link as b_link,
			b.image_filename as b_image_filename,
			i.id as i_id,
			i.image_filename as i_image_filename,
			i.is_core as i_is_core
		FROM
			%prefix%product p
			JOIN %prefix%category c on (p.category_id = c.id)
			JOIN %prefix%brand b on (p.brand_id = b.id)
			LEFT JOIN %prefix%product_image i on (i.product_id = p.id)
		WHERE
			p.uri = '".mysql_real_escape_string($uri)."'
			".$wh."
		");

		return (($rs !== false && !$rs->eof()) ? $rs : false);
	}


	function get_product_by_uri1($uri, $brand= -1)
	{
		if (!is_string($uri) || !strlen($uri) || !is_numeric($brand))
		{
			$this->last_error = $this->Application->Localizer->get_string('invalid_input');
		}

		$q = $q1 = $q2 = '';

		if (($brand != -1)) $q = ' and b.id = "'.mysql_real_escape_string($brand).'"';

			$rs = $this->DataBase->select_custom_sql("SELECT
			p.id as id,
			p.title as title,
			p.uri as uri,
			p.price as price,
			p.discount as discount,
			p.video as video,
			p.is_sale as is_sale,
			p.is_new as is_new,
			p.brand_id as brand_id,
			p.category_id as category_id,
			(
			SELECT count(p2.id) FROM %prefix%product as p2
			  JOIN %prefix%category as c2 on (c2.uri = '".mysql_real_escape_string($uri)."')
			  WHERE p2.category_id = c2.id
			) as cnd_product,
			c.id as c_id,
			c.dollar_currency,
		    c.rouble_currency,
			c.title as c_title,
			c.uri as c_uri,
			c.parent_path_uri as c_parent_path_uri,
			i.image_filename as image_filename
			FROM %prefix%product as p
			JOIN %prefix%category as c on ((c.uri = '".$uri."'))
			JOIN %prefix%product_image as i on ((i.product_id = p.id and i.is_core = 1))
			JOIN %prefix%brand b on (p.brand_id = b.id)
			WHERE p.category_id = c.id ".$q."
			");

		return (($rs != false && !$rs->eof()) ? $rs : false);
	}

	function get_sale()
	{
		$rs = $this->DataBase->select_custom_sql("SELECT
			p.id as id,
			p.title as title,
			p.uri as uri,
			p.price as price,
			p.discount as discount,
			p.is_sale as is_sale,
			p.is_new as is_new,
			p.brand_id as brand_id,
			p.category_id as category_id,
			c.id as c_id,
			c.title as c_title,
			c.dollar_currency,
		    c.rouble_currency,
			c.uri as c_uri,
			c.parent_path_uri as c_parent_path_uri,
			i.image_filename as image_filename
			FROM %prefix%product as p
			JOIN %prefix%category as c
			JOIN %prefix%product_image as i on ((i.product_id = p.id and i.is_core = 1))
			JOIN %prefix%brand b on (p.brand_id = b.id)
			WHERE p.is_sale = 1 and c.id = p.category_id
			");

		return (($rs != false && !$rs->eof()) ? $rs : false);
	}

	function search($find = '', $page = 1, $on_page = 12)
	{
		if((!is_string($find) && !is_numeric($find)) || !is_numeric($page) || !is_numeric($on_page) )
		{
			$this->last_error = $this->Application->Localizer->get_string('invalid_input');
			return false;
		}

		$where = '1=1';
		if (is_numeric($find)) { //����� �� ����
			$where = 'p.id = '.$find;
		} else { //����� �� ��������
			$where = "p.title LIKE '%".$this->DataBase->internalEscape($find)."%'";
		}

		$rs = $this->DataBase->select_custom_sql("SELECT
		p.id as id,
		p.title as title,
		p.uri as uri,
		p.description as description,
		p.category_id as category_id,
		p.price as price,
		p.discount as discount,
		p.is_sale as is_sale,
		p.is_new as is_new,
		c.parent_path_uri as parent_path_uri,
		c.uri as c_uri,
		c.dollar_currency,
		c.rouble_currency,
		(
			SELECT count(id) FROM product as p2 WHERE ".((strlen($find)) ? " p2.title LIKE '%".$this->DataBase->internalEscape($find)."%'" : '')."
		) as cnt,
		(
			SELECT i.image_filename FROM %prefix%product_image as i WHERE i.product_id = p.id AND i.is_core = 1 LIMIT 1
		) as image_filename
		FROM %prefix%product as p
		JOIN %prefix%category as c
		WHERE ". $where ." and c.id = p.category_id
		",array($page, $on_page));

		return (($rs != false && !$rs->eof()) ? $rs : false);
	}


	function add_product($arr)
	{

		if(!is_array($arr))
		{
			$this->last_error = $this->Application->Localizer->get_string('invalid_input');
			return false;
		}

		$insert_arr = array(
			'category_id' => intval($arr['category_id']),
			'brand_id' => intval($arr['brand_id']),
			'title' => $arr['title'],
			'uri' => $arr['uri'],
			'price' => floatval($arr['price']),
			'is_sale' => intval($arr['is_sale']),
			'is_recommend' => intval($arr['is_recommend']),
			'is_new' => intval($arr['is_new']),
			'is_delivery_minsk' => intval($arr['is_delivery_minsk']),
			'is_delivery_moscow' => intval($arr['is_delivery_moscow']),
			'is_delivery_belarus' => intval($arr['is_delivery_belarus']),
			'description_delivery_minsk' => $arr['description_delivery_minsk'],
			'description_delivery_belarus' => $arr['description_delivery_belarus'],
			'description_delivery_moscow' => $arr['description_delivery_moscow'],
			'year' => $arr['year'],
            'size_arr' => $arr['size_arr'],
            'wheel' => $arr['wheel'],
            'suspension' => $arr['suspension'],
            'tormoza' => $arr['tormoza'],
            'rama' => $arr['rama'],
            'vilka' => $arr['vilka'],

            'discount' => intval($arr['discount']),
			'status' => intval($arr['status']),
            'sex' => intval($arr['sex']),
			'description' => $arr['description'],
			'video' => $arr['video'],
			'meta_title' => $arr['meta_title'],
			'meta_description' => $arr['meta_description']
		);

		if(!$id = $this->DataBase->insert_sql('product', $insert_arr))
		{
			$this->last_error = $this->Application->Localizer->get_string('database_error');
			return false;
		}


		return $id;
	}

	function update_product($id, $arr)
	{
		if(!is_array($arr) || !is_numeric($id))
		{
			$this->last_error = $this->Application->Localizer->get_string('invalid_input');
			return false;
		}

		$update_arr = array(
			'category_id' => intval($arr['category_id']),
			'brand_id' => intval($arr['brand_id']),
			'title' => $arr['title'],
			'uri' => $arr['uri'],
			'price' => intval($arr['price']),
			'is_sale' => intval($arr['is_sale']),
			'is_new' => intval($arr['is_new']),
			'is_recommend' => intval($arr['is_recommend']),
			'is_delivery_minsk' => intval($arr['is_delivery_minsk']),
			'is_delivery_moscow' => intval($arr['is_delivery_moscow']),
			'is_delivery_belarus' => intval($arr['is_delivery_belarus']),
			'description_delivery_minsk' => $arr['description_delivery_minsk'],
			'description_delivery_belarus' => $arr['description_delivery_belarus'],
			'description_delivery_moscow' => $arr['description_delivery_moscow'],
			'year' => $arr['year'],
            'size_arr' => $arr['size_arr'],
            'wheel' => $arr['wheel'],
            'suspension' => $arr['suspension'],
            'tormoza' => $arr['tormoza'],
            'vilka' => $arr['vilka'],
            'rama' => $arr['rama'],
            'discount' => intval($arr['discount']),
            'status' => intval($arr['status']),
            'sex' => intval($arr['sex']),
			'description' => $arr['description'],
			'video' => $arr['video'],
			'meta_title' => $arr['meta_title'],
			'meta_description' => $arr['meta_description']
		);


		if(!$this->DataBase->update_sql('product', $update_arr, array('id' => $id)))
		{
			$this->last_error = $this->Application->Localizer->get_string('database_error');
			return false;
		}


		return $id;
	}

    function get_parent_id ($id)
    {
        $parent = $this->DataBase->select_custom_sql("
            SELECT id,
                   parent_id
            FROM %prefix%category
            WHERE id= ".$id);
        return (($parent != false && !$parent->eof()) ? $parent : false);
    }

    function get_service_label ($parent_id)
    {
        $label = $this->DataBase->select_custom_sql("
            SELECT id,
                   category_id,
                   description
            FROM %prefix%service
            WHERE category_id = ".$parent_id);
        return (($label != false && !$label->eof()) ? $label : false);
    }
	
	function get_rand_product($product_id, $category_id)
	{
		if ($product_id < 0 || !is_numeric($product_id) || $category_id < 0 || !is_numeric($category_id)) {
			$this->last_error = $this->Application->Localizer->get_string('invalid_input');
			return false;
		}

		$cnt = $this->Application->DataBase->select_custom_sql("SELECT count(id) as cnt FROM %prefix%product WHERE category_id = ".$category_id." AND id != ".$product_id.' LIMIT 4');
		if ($cnt != false && !$cnt->eof()) {
			$cnt = $cnt->get_field('cnt');
		}

		$order = array('ASC', 'DESC');
		$order = $order[rand(0,1)];
		$order_field = array('title', 'price', 'brand_id', 'sex', 'rate');
		$order_field = $order_field[rand(0, (sizeof($order_field)-1))];

		$rs = $this->DataBase->select_custom_sql("
			SELECT
				p.id as id,
				p.title as title,
				p.price as price,
				p.discount as discount,
				p.is_sale as is_sale,
				p.is_new as is_new,
				p.uri as uri,
				c.dollar_currency,
		        c.rouble_currency,
				IF(c.parent_id > 0, CONCAT(c.parent_path_uri, '/', c.uri), c.uri) as category,
				(SELECT b.title FROM %prefix%brand as b WHERE p.brand_id = b.id LIMIT 1) as brand,
				(SELECT i.image_filename FROM %prefix%product_image as i WHERE i.product_id = p.id AND i.is_core = 1 LIMIT 1) as image_filename
			FROM
				 %prefix%product as p
			JOIN %prefix%category as c on ((p.category_id = c.id))
			WHERE p.id != ".$product_id." ".(($cnt >= 4) ? " AND p.category_id = ".$category_id : null)."
			ORDER BY
				".$order_field." ".$order."
			LIMIT 4
			");

			return (($rs != false && !$rs->eof()) ? $rs : false);
	}

////////////////////////////////////////////////////////////
//														  //
//														  //
//						Product IMAGES				  	  //
//														  //
//														  //
////////////////////////////////////////////////////////////



	function add_product_image($arr)
	{

		if(!is_array($arr))
		{
			$this->last_error = $this->Application->Localizer->get_string('invalid_input');
			return false;
		}

		$core_rs = $this->DataBase->select_custom_sql('SELECT count(id) as cnt FROM %prefix%product_image WHERE is_core = "1" AND product_id = "'.$arr['product_id'].'"');
		if($core_rs == false)
		{
			$this->last_error = $this->Application->Localizer->get_string('database_error');
			return false;
		}

		if(intval($arr['is_core']) > 0 && $core_rs->get_field('cnt') > 0)
		{
			if(!$this->DataBase->update_sql('product_image', array('is_core' => 0), array('product_id' => $arr['product_id'], 'is_core' => 1)))
			{
				$this->last_error = $this->Application->Localizer->get_string('database_error');
				return false;
			}
		}
		elseif (intval($arr['is_core']) == 0 && $core_rs->get_field('cnt') == 0)
			$arr['is_core'] = 1;

		$insert_arr = array(
			'product_id' => intval($arr['product_id']),
			'is_core' => intval($arr['is_core']),
			'image_filename' => $arr['image_filename']
		);

		if(!$filename = AdminUploader::upload('image_filename', 'pub/products/'.$arr['product_id'].'/'))
		{
			$this->last_error = $this->Application->Localizer->get_string('cannot_save_file', 'image_filename');
			return false;
		}

		$Images = $this->Application->get_module('Images');
		$Images->create('product_image', ROOT .'pub/products/'.$arr['product_id'].'/'.$filename, array('product_id' => $arr['product_id']));

		if(!$id = $this->DataBase->insert_sql('product_image', $insert_arr))
		{
			$this->last_error = $this->Application->Localizer->get_string('database_error');
			return false;
		}


		return $id;
	}

	function update_product_image($id, $arr)
	{

		if(!is_array($arr) || !is_numeric($id) || $id < 1)
		{
			$this->last_error = $this->Application->Localizer->get_string('invalid_input');
			return false;
		}

		$core_rs = $this->DataBase->select_custom_sql('SELECT count(id) as cnt FROM %prefix%product_image WHERE is_core = "1" AND product_id = "'.$arr['product_id'].'"');
		if($core_rs == false)
		{
			$this->last_error = $this->Application->Localizer->get_string('database_error');
			return false;
		}

		if(intval($arr['is_core']) > 0 && $core_rs->get_field('cnt') > 0)
		{
			if(!$this->DataBase->update_sql('product_image', array('is_core' => 0), array('product_id' => $arr['product_id'], 'is_core' => 1)))
			{
				$this->last_error = $this->Application->Localizer->get_string('database_error');
				return false;
			}
		}
		elseif (intval($arr['is_core']) == 0 && $core_rs->get_field('cnt') == 0)
			$arr['is_core'] = 1;

		$update_arr = array(
			'product_id' => intval($arr['product_id']),
			'is_core' => intval($arr['is_core'])
		);

		if ($arr['image_filename'] !== '')
		{
			$update_arr['image_filename'] = $arr['image_filename'];
			$old_rs = $this->DataBase->select_sql('product_image', array('id' => intval($id)));
			if($old_rs === false || $old_rs->eof())
			{
				$this->last_error = $this->Application->Localizer->get_string('internal_error');
				return false;
			}

			$Images = $this->Application->get_module('Images');

			if(file_exists(ROOT .'pub/products/'. $old_rs->get_field('product_id') .'/'. $old_rs->get_field('image_filename')))
			{
				$Images->delete('products_image', ROOT .'pub/products/'.$old_rs->get_field('product_id').'/'. $old_rs->get_field('image_filename'), array('product_id' => $old_rs->get_field('product_id')));
				@unlink(ROOT .'pub/products/'. $old_rs->get_field('product_id') .'/'. $old_rs->get_field('image_filename'));
			}

			if(!$filename = AdminUploader::upload('image_filename', 'pub/products/'.$arr['product_id'].'/'))
			{
				$this->last_error = $this->Application->Localizer->get_string('cannot_save_file', 'image_filename');
				return false;
			}

			$Images->create('product_image', ROOT .'pub/products/'.$arr['product_id'].'/'.$filename, array('product_id' => $arr['product_id']));
		}///////////////
		else {
			$filename = $this->DataBase->select_custom_sql("SELECT image_filename FROM product_image WHERE id = ".$id);
			$file_arr = array();
			while (!$filename->eof())
			{
				row_to_vars($filename, $file_arr[count($file_arr)+1]);
				$filename->next();
			}
			$filename = $file_arr[1]['image_filename'];

			$Images = $this->Application->get_module('Images');
			$Images->create('product_image', ROOT .'pub/products/'.$arr['product_id'].'/'.$filename, array('product_id' => $arr['product_id']));
		}/////////////////////

		if(!$id = $this->DataBase->update_sql('product_image', $update_arr, array('id' => $id)))
		{
			$this->last_error = $this->Application->Localizer->get_string('database_error');
			return false;
		}
		return $id;
	}


	/////////////////////////*PRODUCT RECOMMEND*///////////////////////

	function get_recommend_product($id)
	{
		if (empty($id) || !is_numeric($id))
		{
			$this->last_error = $this->Application->Localizer->get_string('invalid_input');
			return false;
		}

		$rs = $this->DataBase->select_custom_sql("
		SELECT
			p.id as id,
			p.title as title,
			p.price as price,
			p.discount as discount,
			c.dollar_currency,
            c.rouble_currency,
			p.is_sale as is_sale,
			p.is_new as is_new,
			p.uri as uri,
			IF(c.parent_id > 0, CONCAT(c.parent_path_uri, '/', c.uri), c.uri) as category,
			b.title as brand,
			i.image_filename as image_filename,
			r.recommend_id
		FROM
			 %prefix%product as p
		JOIN %prefix%category as c on ((p.category_id = c.id))
		JOIN %prefix%brand as b on ((p.brand_id = b.id))
		JOIN %prefix%product_image as i on ((i.product_id = p.id and i.is_core = 1))
		JOIN %prefix%product_recommend as r on ((p.id = r.recommend_id))
		WHERE r.product_id = '".$id."'
		");

		return (($rs != false && !$rs->eof()) ? $rs : false);

	}

    function get_snoubord_products($cond_arr, $sort_by, $page, $on_page, $brand, $price_start, $price_end, $sex, $category,$year)
    {
        if(!is_array($cond_arr) || !strlen($cond_arr['c1_uri']) || !strlen($cond_arr['c2_uri']) || !in_array($sort_by, array('title', 'price')) || !is_numeric($page) || intval($page) < 1 || !is_numeric($brand) || !is_numeric($price_start) || !is_numeric($price_end) || !is_numeric($sex) || !is_numeric($category))
        {
            $this->last_error = $this->Application->Localizer->get_string('invalid_input');
            return false;
        }

        $q = $q1 = $q2 =  $q3 = $q4 = $q5 ='';

        if (($brand != -1)) $q = ' and b.id = "'.mysql_real_escape_string($brand).'"';

        if (($price_start > 0) && ($price_end > 0)) $q1 = ' and (price between "'.mysql_real_escape_string($price_start).'" and "'.mysql_real_escape_string($price_end).'")';



        if ($sex != -1) $q3 = ' and sex="'.mysql_real_escape_string($sex).'"';

        if ($category != -1) $q4 = ' and category_id="'.mysql_real_escape_string($category).'"';

        if ($year != -1) $q5 = ' and year="'.mysql_real_escape_string($year).'"';

        if (strlen($cond_arr['c2_uri'])) $category_where = 'c.parent_path_uri = "'.mysql_real_escape_string($cond_arr['c1_uri'].'/'.$cond_arr['c2_uri']).'"';
        else $category_where = 'c.parent_path_uri = "'.mysql_real_escape_string($cond_arr['c1_uri']).'" AND
			c.uri = "'.mysql_real_escape_string($cond_arr['c2_uri']).'"';
        $category_where_cnt = str_replace('c.', 'c2.', $category_where);

        $rs = $this->DataBase->select_custom_sql('
		SELECT
			p.id as id,
			c.parent_path as c_parent_path,
			c.parent_path_uri as c_parent_path_uri,
			c.title as c3_title,
			c.uri as c3_uri,
			b.title as brand,
			p.title as title,
			p.price as price,
			p.discount as discount,
			c.dollar_currency,
            c.rouble_currency,
			IF(p.discount > 0, p.discount, p.price) as actual_price,
			p.uri as uri,
			p.is_sale as is_sale,
			p.is_new as is_new,
			p.sex as sex,
			i.image_filename as image_filename,
			(
				SELECT count(p2.id) FROM %prefix%product p2 JOIN %prefix%category c2 on (p2.category_id = c2.id)
				JOIN %prefix%brand b2 on (p2.brand_id = b2.id)
				 WHERE '.$category_where_cnt.'
				'.str_replace("b.","b2.",$q).$q1.$q3.$q4.$q5.'
			) as cnt_products
		FROM
			%prefix%product p
			JOIN %prefix%category c on (p.category_id = c.id)
			JOIN %prefix%brand b on (p.brand_id = b.id)
			LEFT JOIN %prefix%product_image i on (i.product_id = p.id AND i.is_core = 1)
		WHERE
			'.$category_where.'
			'.$q.$q1.$q3.$q4.$q5.'
		ORDER BY
			'.mysql_real_escape_string($sort_by).' ASC
		', $on_page!='all' ? array($page, $on_page) : null);

        return (($rs !== false && !$rs->eof()) ? $rs : false);
    }
	
	function add_to_cart($id, $count = 0)
	{
		$id = intval($id);
		$count = intval($count);
		if ($id < 1 || $count < 1) {
			$this->last_error = $this->Application->Localizer->get_string('invalid_input');
			return false;
		}

		if ($_SESSION['cart'][$id]) {
			$_SESSION['cart'][$id] += $count;
		} else {
			$_SESSION['cart'][$id] = $count;
		}

		$count = 0;
		if (!empty($_SESSION['cart'])) {
			foreach($_SESSION['cart'] as $item) {
				$count += $item;
			}
		}
		return $count;
	}

	function get_products_in_cart()
	{
		if (empty($_SESSION['cart'])) {
			return false;
		}
		$ids = implode(',', array_keys($_SESSION['cart']));

		$rs = $this->Application->DataBase->select_custom_sql("
			SELECT
				p.id as id,
				p.title as title,
				p.category_id as category,
				p.uri as uri,
				p.price as price,
				c.dollar_currency,
		        c.rouble_currency,
				p.discount as discount,
				(
					SELECT b.title FROM %prefix%brand as b WHERE b.id = p.brand_id LIMIT 1
				) as b_title,
				(
					SELECT i.image_filename FROM %prefix%product_image as i WHERE i.product_id = p.id LIMIT 1
				) as image_filename,
				c.parent_id as parent_id,
				(
					SELECT c2.uri as c2_uri FROM %prefix%category as c2 WHERE c2.id = c.parent_id LIMIT 1
				) as c_parent_uri,
				(
					SELECT c2.parent_id as c2_id FROM %prefix%category as c2 WHERE c2.id = c.parent_id LIMIT 1
				) as c_parent_id,
				(
					SELECT c2.title as c2_title FROM %prefix%category as c2 WHERE c2.id = c.parent_id LIMIT 1
				) as c_parent_title,
				(
					SELECT c3.uri as c3_uri FROM %prefix%category as c3 WHERE c3.id = c_parent_id LIMIT 1
				) as c_parent_path_uri,
				(
					SELECT c3.title as c3_title FROM %prefix%category as c3 WHERE c3.id = c_parent_id LIMIT 1
				) as c_parent_path_title,
				c.uri as category_uri,
				c.title as category_title
			FROM %prefix%product p
			JOIN %prefix%category c on (c.id = p.category_id)
			WHERE p.id IN(".$ids.")
		");

		return (($rs != false && !$rs->eof()) ? $rs : false);
	}

	function remove_in_cart($id)
	{
		$id = intval($id);
		if ($id < 1) {
			$this->last_error = $this->Application->Localizer->get_string('invalid_input');
			return false;
		}

		unset($_SESSION['cart'][$id]);
	}
}

?>