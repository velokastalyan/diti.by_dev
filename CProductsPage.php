<?php

require_once(BASE_CLASSES_PATH. 'frontpage.php');

class CProductsPage extends CFrontPage  {

	protected $Categories;
	protected $Products;
	protected $Brands;
	protected $c1_uri;
	protected $c2_uri;
	protected $c3_uri;
	protected $on_page;
	protected $sort_by;
	protected $page;

	protected $category_id = 0;

	function CProductsPage(&$app, $template){
		parent::__construct($app, $template);
	}


	function on_page_init(){
		parent::on_page_init();
		$this->c1_uri = InUri('c1_uri', false);
		$this->c2_uri = InUri('c2_uri', false);
		$this->c3_uri = InUri('c3_uri', false);
		if(!$this->c1_uri || !$this->c2_uri)
			$this->page_not_found();

                $this->tv['brand'] = intval(InGet('brand', -1));
		$this->tv['price'] = InGet('price', -1);
		$this->tv['sex'] = InGet('sex', -1);
        $this->tv['wheel'] = InGet('wheel', -1);
        $this->tv['suspension'] = InGet('suspension', -1);
        $this->tv['tormoza'] = InGet('tormoza', -1);
        $this->tv['vilka'] = InGet('vilka', -1);
        $this->tv['rama'] = InGet('rama', -1);
        $this->tv['year'] = InGet('year', -1);



        if($this->tv['price']!=-1){
            $PriceToSlider = explode(",",$this->tv['price']);

        }else{
            $PriceToSlider[0] = 0;
            $PriceToSlider[1] = 50000;
        }

        $this->tv["PriceToSlider"] = $PriceToSlider;


		$this->on_page = InCookie('on_page');
		if(!is_numeric($this->on_page) || !in_array($this->on_page, array(36, 'all')) || empty($this->on_page))
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


		$this->sort_by = InCookie('sort_by', 'price');

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

		//$this->page = intval(str_replace('page-', '', InUri('page', 1)));
        $this->page = InGet('page', 1);

		if($this->page < 1)
			$this->page_not_found();

                $this->Categories = $this->Application->get_module('Categories');
                $this->Products = $this->Application->get_module('Products');
                $this->Brands = $this->Application->get_module('Brands');

	}

	function parse_data(){
		if(!parent::parse_data())
			return false;

		$this->bind_data();

		return true;
	}

	function bind_data()
	{
		$this->tv['on_page'] = $this->on_page;
		$this->tv['sort_by'] = $this->sort_by;
		$this->bind_category();
		$this->bind_products();
		$this->bind_brand_for_products();
		$this->bind_filter_items();
	}


	function bind_category()
	{

		$rs = $this->Categories->get_by_uri($this->c2_uri, $this->c1_uri);

		if($rs !== false && !$rs->eof())
		{
			$this->tv['c1_title'] = $rs->get_field('parent_path');
			$this->tv['c1_uri'] = $this->c1_uri;
			$this->tv['c2_title'] = $rs->get_field('title');
            $this->tv['c_description_more'] = $rs->get_field('description_more');
			$this->tv['c2_uri'] = $this->c1_uri.'/'.$this->c2_uri;

			row_to_vars($rs, $this->tv, false, 'c_');
			$this->tv['children_found'] = false;

			$this->PAGE_TITLE = $this->tv['meta_title'] = $rs->get_field('meta_title');
			//$this->PAGE_KEYWORDS = $rs->get_field('meta_keywords');
			$this->PAGE_DESCRIPTION = $this->tv['meta_description'] = $rs->get_field('meta_description');

			if(strlen($rs->get_field('child_title')) > 0)
			{
				$this->tv['children_found'] = true;
				recordset_to_vars($rs, $this->tv['children']);
			}
			if(intval($rs->get_field('child_id')))
			{
				$this->tv['children_found'] = true;
				while (!$rs->eof())
				{
					$this->tv['children'][$rs->current_row]['id'] = $rs->get_field('child_id');
					$this->tv['children'][$rs->current_row]['title'] = $rs->get_field('child_title');
					if($rs->get_field('child_uri') == $this->c3_uri)
					{
						$this->tv['c3_title'] = $rs->get_field('child_title');
                        $this->tv['cat_id'] = $rs->get_field('child_id');
                        $this->tv['child_h1_text'] = $rs->get_field('child_h1_text');
                        $this->tv['child_description'] = $rs->get_field('child_description');
                        $this->tv['child_description_more'] = $rs->get_field('child_description_more');
						$this->tv['c3_uri'] = $this->c1_uri.'/'.$this->c2_uri.'/'.$this->c3_uri;
						$this->tv['ac_id'] = $this->category_id = $rs->get_field('child_id');
                                                $this->PAGE_TITLE = $this->tv['meta_title'] = $rs->get_field('child_meta_title');
                                                $this->PAGE_DESCRIPTION = $this->tv['meta_description'] = $rs->get_field('child_meta_description');
                                        }
                                        $rs->next();
                                }
                        }
                       $this->tv['current_category_uri'] = ($this->c3_uri ? $this->tv['c3_uri'] : $this->tv['c2_uri']);
                       $this->tv['current_category_id'] = ($this->category_id ? $this->category_id : $this->tv['c_id']);
                   $this->tv['parent_cat_id'] = '-';
                   $parent_cat_id =  $this->Categories->get_by_uri($this->c2_uri);
                   if ($parent_cat_id !== false && !$parent_cat_id ->eof()){
                       $this->tv['parent_cat_id'] = $parent_cat_id->get_field('id');
                   }
                }
                else
                        $this->page_not_found();

	}

	function bind_products()
	{
		require_once(CUSTOM_CONTROLS_PATH .'pager.php');

		$page = InGetPost('page', 1);
		//$page = str_replace('page-', '', $page);

		if($page < 1)
			$page = 1;

		$cnt = 0;

		$this->Products = $this->Application->get_module('Products');
		if ($this->tv['brand'] == '') $this->tv['brand'] = -1;
		switch($this->tv['sex'])
		{
			case 'unisex': $this->tv['sex'] = 0; break;
			case 'men': $this->tv['sex'] = 1; break;
			case 'women': $this->tv['sex'] = 2; break;
			default: $this->tv['sex'] = -1; break;
		}

        switch($this->tv['wheel'])
        {
            case '27.5': $this->tv['wheel'] = 1; break;
            case '29': $this->tv['wheel'] = 2; break;
            default: $this->tv['wheel'] = -1; break;
        }

        switch($this->tv['suspension'])
        {
            case 'hard-tail': $this->tv['suspension'] = 1; break;
            case 'full_suspension': $this->tv['suspension'] = 2; break;
            default: $this->tv['suspension'] = -1; break;
        }

        switch($this->tv['tormoza'])
        {
            case 'torm1': $this->tv['tormoza'] = 1; break;
            case 'torm2': $this->tv['tormoza'] = 2; break;
            case 'torm3': $this->tv['tormoza'] = 3; break;
            default: $this->tv['tormoza'] = -1; break;
        }

        switch($this->tv['vilka'])
        {
            case 'vilka1': $this->tv['vilka'] = 1; break;
            case 'vilka2': $this->tv['vilka'] = 2; break;
            case 'vilka3': $this->tv['vilka'] = 3; break;
            case 'vilka4': $this->tv['vilka'] = 4; break;
            default: $this->tv['vilka'] = -1; break;
        }


        switch($this->tv['rama'])
        {
            case 'rama1': $this->tv['rama'] = 1; break;
    case 'rama2': $this->tv['rama'] = 2; break;
    case 'rama3': $this->tv['rama'] = 3; break;
    case 'rama4': $this->tv['rama'] = 4; break;
    case 'rama5': $this->tv['rama'] = 5; break; // Исправление для "Титана"
    default: $this->tv['rama'] = -1; break;
        }
        
switch($this->tv['year'])
{
    case 2018: $this->tv['year'] = 2018; break;
    case 2019: $this->tv['year'] = 2019; break;
    case 2020: $this->tv['year'] = 2020; break;
    case 2021: $this->tv['year'] = 2021; break;
    case 2022: $this->tv['year'] = 2022; break;
    case 2023: $this->tv['year'] = 2023; break;
    case 2024: $this->tv['year'] = 2024; break;
    case 2025: $this->tv['year'] = 2025; break;
    case 2026: $this->tv['year'] = 2026; break;
    default: $this->tv['year'] = -1; break;
}


		$price = explode(',',$this->tv['price']);



		if ($price[0] == '') $price[0] = -1;
        if (($price[0] > -1) && ($price[1] == '')) $price[1] = 999999999;
		elseif ($price[1] == '') $price[1] = -1;

		if ($price[0] != -1) $price[0] = $price[0]/$this->tv['sd_dollar'];
		if (($price[1] != -1) && ($price[1] != 999999999)) $price[1] = $price[1]/$this->tv['sd_dollar'];


		if (empty($this->tv['price_start']) || $this->tv['price_start'] < 0) $this->tv['price_start'] = 0;
		if (empty($this->tv['price_end']) || $this->tv['price_end'] < 0) $this->tv['price_end'] = 0;

		$product_rs = $this->Products->get_category_products(array('c1_uri' => $this->c1_uri, 'c2_uri' => $this->c2_uri, 'c3_uri' => $this->c3_uri), $this->sort_by, $page, $this->on_page, $this->tv['brand'], $price[0], $price[1], $this->tv['sex'], $this->tv['wheel'], $this->tv['suspension'], $this->tv['tormoza'],$this->tv['vilka'],$this->tv['rama'],$this->tv['year']);

		if ($product_rs != false) {
			$this->tv['product_found'] = true;

			$cnt = $product_rs->get_field('cnt_products');
			$this->tv['cnt'] = $cnt;

//			while(!$product_rs->eof())
//			{
//				row_to_vars($product_rs, $this->tv['product_arr'][count($this->tv['product_arr'])+1]);
//				$product_rs->next();
//			}
            recordset_to_vars($product_rs, $this->tv['product_arr']);
		}

        $all_product_rs = $this->Products->get_category_products(array('c1_uri' => $this->c1_uri, 'c2_uri' => $this->c2_uri, 'c3_uri' => $this->c3_uri), $this->sort_by, 1, 'all', $this->tv['brand'], $price[0], $price[1], $this->tv['sex'], $this->tv['wheel'], $this->tv['suspension'], $this->tv['tormoza'],$this->tv['vilka'],$this->tv['rama'],$this->tv['year']);

        if ($all_product_rs != false) {

            $start = microtime(true);
//            while(!$all_product_rs->eof())
//            {
//                row_to_vars($all_product_rs, $this->tv['all_product_arr'][]);
//                $all_product_rs->next();
//            }
            recordset_to_vars($all_product_rs, $this->tv['all_product_arr']);
        }

		$Pager = new Pager('Products', $page, $cnt, $this->on_page, true);
		$Pager->link = $this->HTTP.substr($_SERVER['REQUEST_URI'],1);

		$r_uri = parse_url($_SERVER['REQUEST_URI']); //Получаем параметры из url в виде строки
		$this->tv['r_path'] = substr($r_uri['path'], 1); //Путь без параметров
		parse_str($r_uri['query'], $this->tv['r_param']); //Разбиваем строку с параметрами на массив
		$this->tv['r_page'] = InGet('page', '');
	}


	function bind_brand_for_products()
	{
                $this->Brands = $this->Application->get_module('Brands');

                $price = explode(',',$this->tv['price']);

                $price_start = (isset($price[0]) && $price[0] !== '') ? $price[0] : -1;
                $price_end = (isset($price[1]) && $price[1] !== '') ? $price[1] : -1;
                if ($price_start == '1501000') $price_end = '99999999999';

                $target_category_id = ($this->category_id ? $this->category_id : $this->tv['c_id']);

                $brand_rs = $this->Brands->get_category_brands($target_category_id, $this->tv['sex'], $price_start, $price_end);

                $this->tv['brand_arr'] = array();
                $this->tv['brand_found'] = false;

		if ($brand_rs !=false)
		{
			$this->tv['brand_found'] = true;
            recordset_to_vars($brand_rs, $this->tv['brand_arr']);
		}
	}

	function bind_filter_items()
	{
		$sex_rs = array(
			0 => array(
				'id' => 0,
				'title' => 'Унисекс',
				'name' => 'unisex'
			),
			1 => array(
				'id' => 1,
				'title' => 'Мужской',
				'name' => 'men'
			),
			2 => array(
				'id' => 2,
				'title' => 'Женский',
				'name' => 'women'
			)
		);

        $this->tv['wheel_arr'] = array(
            1 => array(
                'id' => 1,
                'title' => '27.5',
                'name' => '27.5'
            ),
            2 => array(
                'id' => 2,
                'title' => '29',
                'name' => '29'
            )
        );

        $this->tv['suspension_arr'] = array(
            1 => array(
                'id' => 1,
                'title' => 'Хард-тейл',
                'name' => 'hard-tail'
            ),
            2 => array(
                'id' => 2,
                'title' => 'Двухподвес',
                'name' => 'full_suspension'
            )
        );

        $this->tv['tormoza_arr'] = array(
            1 => array(
                'id' => 1,
                'title' => 'Дисковые гидравлические',
                'name' => 'torm1'
            ),
            2 => array(
                'id' => 2,
                'title' => 'Дисковые механические',
                'name' => 'torm2'
            ),
            3 => array(
                'id' => 3,
                'title' => 'Ободные',
                'name' => 'torm3'
            )
        );

        $this->tv['vilka_arr'] = array(
            1 => array(
                'id' => 1,
                'title' => 'Воздух',
                'name' => 'vilka1'
            ),
            2 => array(
                'id' => 2,
                'title' => 'Масло',
                'name' => 'vilka2'
            ),
            3 => array(
                'id' => 2,
                'title' => 'Пружина',
                'name' => 'vilka3'
            ),
            4 => array(
                'id' => 2,
                'title' => 'Ригидная',
                'name' => 'vilka4'
            )
        );

 $this->tv['rama_arr'] = array(
    1 => array(
        'id' => 1,
        'title' => 'Алюминий',
        'name' => 'rama1'
    ),
    2 => array(
        'id' => 2,
        'title' => 'Сталь Hi-ten',
        'name' => 'rama2'
    ),
    3 => array(
        'id' => 3,
        'title' => 'Карбон',
        'name' => 'rama3'
    ),
    4 => array(
        'id' => 4,
        'title' => 'Хром-молибденовая сталь',
        'name' => 'rama4'
    ),
    5 => array(
        'id' => 5,
        'title' => 'Титан',
        'name' => 'rama5'
    )
        );

$this->tv['year_arr'] = array(
    1 => array(
        'id' => 2026,
        'title' => '2026',
        'name' => '2026'
    ),
    2 => array(
        'id' => 2025,
        'title' => '2025',
        'name' => '2025'
    ),
    3 => array(
        'id' => 2024,
        'title' => '2024',
        'name' => '2024'
    ),
    4 => array(
        'id' => 2023,
        'title' => '2023',
        'name' => '2023'
    ),
    5 => array(
        'id' => 2022,
        'title' => '2022',
        'name' => '2022'
    ),
    6 => array(
        'id' => 2021,
        'title' => '2021',
        'name' => '2021'
    ),
    7 => array(
        'id' => 2020,
        'title' => '2020',
        'name' => '2020'
    ),
    8 => array(
        'id' => 2019,
        'title' => '2019',
        'name' => '2019'
    ),
    9 => array(
        'id' => 2018,
        'title' => '2018',
        'name' => '2018'
    )
);


		$price_rs = array(
			0 => array (
				'id' => '1,50',
                'between' => '1,'.(50/$this->tv['sd_dollar']),
				'title' => 'До 50 BYN',
				'name' => 'price1'
			),
			1 => array (
				'id' => '50.1,100',
                'between' => (50.1/$this->tv['sd_dollar']).','.(100/$this->tv['sd_dollar']),
				'title' => '50.10 - 100 BYN',
				'name' => 'price2'
			),
			2 => array(
				'id' => '100.1,150',
                'between' => (100.1/$this->tv['sd_dollar']).','.(150/$this->tv['sd_dollar']),
				'title' => '100.10 - 150 BYN',
				'name' => 'price3'
			),
            3 => array(
                'id' => '150.1,300',
                'between' => (150.1/$this->tv['sd_dollar']).','.(300/$this->tv['sd_dollar']),
                'title' => '150.10 - 300 BYN',
                'name' => 'price4'
            ),
            4 => array(
                'id' => '300.1,500',
                'between' => (300.10/$this->tv['sd_dollar']).','.(500/$this->tv['sd_dollar']),
                'title' => '300.10 - 500 BYN',
                'name' => 'price5'
            ),
            5 => array(
                'id' => '500.1,700',
                'between' => (500.1/$this->tv['sd_dollar']).','.(700/$this->tv['sd_dollar']),
                'title' => '500.10 - 700 BYN',
                'name' => 'price6'
            ),
            6 => array(
                'id' => '700.1,1000',
                'between' => (700.1/$this->tv['sd_dollar']).','.(1000/$this->tv['sd_dollar']),
                'title' => '700.10 - 1000 BYN',
                'name' => 'price7'
            ),
			7 => array (
				'id' => '1000.1',
                'between' => (1000.10/$this->tv['sd_dollar']).',999999999',
				'title' => 'Выше 1000.10 BYN',
				'name' => 'price8'
			)
		);

		$this->tv['sex_arr'] = array();
		$this->tv['price_arr'] = array();

        $pricesCount = count($price_rs);
        $pricesArrCount = count($this->tv['price_arr']);
        $allProductsCount = count($this->tv['all_product_arr']);
        $sexCount = count($sex_rs);


		//Массив пола
		$d = false;
		for ($i = 0; $i < $sexCount; $i++)
		{
			for ($j = 0; $j < $allProductsCount; $j++)
			{
				if ($this->tv['all_product_arr'][$j]['sex'] == $sex_rs[$i]['id'])
				{
					for ($k = 0; $k < count($this->tv['sex_arr']); $k++)
					{
						if ($sex_rs[$i]['id'] !=  $this->tv['sex_arr'][$k]['id']) $d = false; else { $d = true; break; } //Поиск дубликата
					}
					if (!$d)
					row_to_vars($sex_rs[$i], $this->tv['sex_arr'][]);
				}
			}
		}

		foreach ($this->tv['sex_arr'] as $k=>$v){
			if(empty($v)) unset($this->tv['sex_arr'][$k]);
		}



		//Массив цен
		$d = false;
		for ($i = 0; $i < count($price_rs); $i++)
		{
			$prices = explode(",",$price_rs[$i]['between']);

			if ($allProductsCount > 1)
			for ($j = 0; $j < $allProductsCount; $j++)
			{
				if ($this->tv['all_product_arr'][$j]['discount'] == 0)
				{
					if (($this->tv['all_product_arr'][$j]['price'] >= $prices[0]) && ($prices[1] >= $this->tv['all_product_arr'][$j]['price']))
					{
						for ($k = 0; $k < count($this->tv['price_arr']); $k++)
						{
							if ($price_rs[$i]['id'] !=  $this->tv['price_arr'][$k]['id'])  { $d = false; } else { $d = true; break; } //Поиск дубликата
						}
						if (!$d)
						row_to_vars($price_rs[$i], $this->tv['price_arr'][]);
					}
				}
				else
				{
					if (($this->tv['all_product_arr'][$j]['discount'] >= $prices[0]) && ($prices[1] >= $this->tv['all_product_arr'][$j]['discount']))
					{
						for ($k = 0; $k < count($this->tv['price_arr']); $k++)
						{
							if ($price_rs[$i]['id'] !=  $this->tv['price_arr'][$k]['id']) $d = false; else { $d = true; break; } //Поиск дубликата
						}
						if (!$d)
						row_to_vars($price_rs[$i], $this->tv['price_arr'][]);
					}
				}
			}
			else {
				if ($this->tv['all_product_arr'][0]['discount'] == 0)
				{
					if (($this->tv['all_product_arr'][0]['price'] >= $prices[0]) && ($prices[1] >= $this->tv['all_product_arr'][0]['price']))
					{
						row_to_vars($price_rs[$i], $this->tv['price_arr'][]);
					}
				}
				else
				{
					if (($this->tv['all_product_arr'][0]['discount'] >= $prices[0]) && ($prices[1] >= $this->tv['all_product_arr'][0]['discount']))
					{
                        row_to_vars($price_rs[$i], $this->tv['price_arr'][]);
					}
				}
			}
		}



		foreach ($this->tv['price_arr'] as $k=>$v){
			if(empty($v)) unset($this->tv['price_arr'][$k]);
		}
	}
}