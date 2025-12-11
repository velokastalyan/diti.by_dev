<?php

require_once(BASE_CLASSES_PATH. 'frontpage.php');

class CCategoriesPage extends CFrontPage  {

	protected $Categories;
	protected $category_uri;
	protected $Products;
	protected $Brands;


	function CCategoriesPage(&$app, $template){
		parent::__construct($app, $template);
	}

	function on_page_init(){
		parent::on_page_init();
		if(!$this->category_uri = InUri('category_uri', false))
			$this->page_not_found();

		$this->tv['brand'] = InGet('brand');
		$this->tv['price_start'] = InGet('price_start');
		$this->tv['price_end'] = InGet('price_end');

		$this->on_page = InCookie('on_page');
		if(!is_numeric($this->on_page) || !in_array($this->on_page, array(36)) || empty($this->on_page))
		{
			$this->on_page = 36;
			setcookie('on_page', $this->on_page, time()+60*60*24*30, '/');
		}
		$get_on_page = InGet('on_page', '');
		if (($this->on_page !== $get_on_page) && (!empty($get_on_page)))
		{
			$this->on_page = $get_on_page;
			setcookie('on_page', $this->on_page, time()+60*60*24*30);
		}


		$this->sort_by = InCookie('sort_by', 'title');
		if(!in_array($this->sort_by, array('title', 'price')))
		{
			$this->sort_by = 'title';
			setcookie('sort_by', 'title', time()+60*60*24*30, '/');
		}
		$get_sort_by = InGet('sort_by', '');
		if (($this->sort_by !== $get_sort_by) && (!empty($get_sort_by)))
		{
			$this->sort_by = $get_sort_by;
			setcookie('sort_by', $this->sort_by, time()+60*60*24*30, '/');
		}

		$this->page = intval(str_replace('page-', '', InUri('page', 1)));

		$this->Categories = $this->Application->get_module('Categories');

	}



	function parse_data(){
		if(!parent::parse_data())
			return false;

		$this->tv['category_uri'] = InUri('category_uri', false);
		$this->bind_data();

		$this->bind_brand_products();

		return true;
	}

	function bind_data()
	{
		$rs = $this->Categories->get_by_uri($this->category_uri, InUri('parent_category_uri', false));
		$this->tv['category_found'] = false;
		if($rs !== false && !$rs->eof())
		{
			row_to_vars($rs, $this->tv, false, 'c_');
			$this->tv['meta_title'] = $rs->get_field('meta_title');
            $this->tv['meta_description'] = $rs->get_field('meta_description');
            $this->tv['c_description_more'] = $rs->get_field('description_more');
			$this->tv['children_found'] = false;
			if(strlen($rs->get_field('child_title')) > 0)
			{
				$this->tv['children_found'] = true;
				recordset_to_vars($rs, $this->tv['children']);
			}
		}
        else
        {
            $this->page_not_found();
        }

	}


	function bind_brand_products()
	{
		$this->Brands = $this->Application->get_module('Brands');

		if ($this->tv['c_id'] != 31)
		$brand_rs = $this->Brands->get_by_category_uri($this->tv['category_uri'], $this->tv['c_parent_path_uri']);
		else $brand_rs = $this->Brands->get_brand_for_sale();


		$this->tv['brand_found'] = false;
		$this->tv['brand_arr'] = array();

		if ($brand_rs != false && !$brand_rs->eof())
		{
			$this->tv['brand_found'] = true;

			while(!$brand_rs->eof())
			{
				row_to_vars($brand_rs, $this->tv['brand_arr'][count($this->tv['brand_arr']) + 1]);

				$brand_rs->next();
			}
		}

	}
};