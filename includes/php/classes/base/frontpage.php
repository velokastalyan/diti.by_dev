<?php
require_once(BASE_CLASSES_PATH. 'htmlpage.php');
class CFrontPage extends CHTMLPage
{
	protected $Registry;
	protected $Categories;
	public $site_sett_rs;
	protected $Brands;
	
	function CFrontPage(&$app, $content)
	{
		parent::CHTMLPage($app, $content);
	}

	function on_page_init()
	{

		$res = parent::on_page_init();
		return $res;

	}

	function parse_data()
	{
		if (!parent::parse_data()) return false;

		$this->bind_static();
		$this->bind_menu();
		$this->bind_brand();
        $this->bind_history();

		return true;
	}

	function bind_static()
	{
		$this->Registry = $this->Application->get_module('Registry');
		$rs = $this->Registry->get_pathes_values('header/footer');
		if($rs !== false && !$rs->eof())
		{
			$this->tv['sd_found'] = true;
			while(!$rs->eof())
			{
				$this->tv['sd_'.$rs->get_field('value_path')] = $rs->get_field('value');
				$rs->next();
			}
		}
		
		
		/*ОПРЕДЕЛЕНИЕ СТРАНЫ ПОЛЬЗОВАТЕЛЯ*/
        /*if ( !isset($_COOKIE['client_country']) || !in_array($_COOKIE['client_country'], array('Russian Federation', 'Belarus')) ) {
            $ip = $_SERVER['REMOTE_ADDR'];
            $IntIp = $this->ip2int($ip);

            $rs = $this->Application->DataBase->select_custom_sql("
            SELECT
                COUNTRY
            FROM %prefix%ip_to_country
            WHERE
                `IP_TO` > ".$IntIp." AND `IP_FROM` < ".$IntIp."
            LIMIT 1
            ");

            if ($rs != false) {
                $this->tv['ip_country'] = $rs->get_field('COUNTRY');
            }
            setcookie('client_country', $this->tv['ip_country'], time()+86400, '/');
        } else {
            $this->tv['ip_country'] = $_COOKIE['client_country'];
        }*/
$this->tv['ip_country']="Belarus";
		/***********************/
		
		/*количество товаров в корзине*/
		$count = 0;
		if (!empty($_SESSION['cart'])) {
			foreach($_SESSION['cart'] as $item) {
				$count += $item;
			}
		}
		$this->tv['basketCart'] = $count;
	}

	function bind_menu()
	{
		$this->Categories = $this->Application->get_module('Categories');
		$tree_arr = $this->Categories->get_tree();
		if($tree_arr !== false)
		{
			$this->tv['menu_found'] = true;
			$this->tv['menu'] = $tree_arr;
		}
                $this->menu = $this->Application->get_module('Menus');
                $this->tv['top_menu_arr'] = get_items_nav_menu('top_menu');
	}

	function bind_brand()
	{
		$this->Brand = $this->Application->get_module('Brands');
		$brand_rs = $this->Brand->get_brand_footer();

		$this->tv['brand_main_arr'] = array();

		if ($brand_rs != false)
			$this->tv['brand_main_found'] = true;

		while(!$brand_rs->eof())
		{
			row_to_vars($brand_rs, $this->tv['brand_main_arr'][count($this->tv['brand_main_arr'])+1]);
			$brand_rs->next();
		}
	}

    function bind_history()
    {
        if (!empty($_COOKIE['history_str']))
        {
            $history_arr = explode(',', $_COOKIE['history_str']);

            $this->History = $this->Application->get_module('Products');

            $history_rs = $this->History->get_product_by_arr_id($history_arr);

            $this->tv['history_found'] = false;
            $this->tv['history_arr'] = array();
            if ($history_rs != false && !$history_rs->eof())
            {
                $this->tv['history_found'] = true;

                while(!$history_rs->eof())
                {
                    row_to_vars($history_rs, $this->tv['history_arr'][count($this->tv['history_arr'])+1]);
                    $history_rs->next();
                }
            }

			/* Скрыть/показать панель истории */
			$this->tv['show_history'] = InCookie('show_history', 1);
        }
    }
	
	function ip2int($ip) {
		$a=explode(".",$ip);
		return $a[0]*256*256*256+$a[1]*256*256+$a[2]*256+$a[3];
	}

    function page_not_found()
    {
        $this->internalRedirect($this->tv['HTTP'].'page-not-found.html');
    }
}