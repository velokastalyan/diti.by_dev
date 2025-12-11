<?php
require_once(BASE_CLASSES_PATH. 'frontpage.php');
require_once(CUSTOM_CLASSES_PATH. 'controls/pager.php');

class CSalePage extends CFrontPage {

	protected $Products;
	protected $find;
	protected $on_page = 2;

	function CSalePage(&$app, $template){
		parent::__construct($app, $template);

	}

	function on_page_init(){
		parent::on_page_init();

		$this->Products = $this->Application->get_module('Products');
	}

	function parse_data(){

		if(!parent::parse_data())
			return false;


		$this->bind_data();

		return true;
	}

	function bind_data()
	{
		$this->bind_menu();
		$this->bind_static();
		$this->bind_brand();
		$this->bind_sale();
	}

	function bind_sale()
	{
		$this->Products = $this->Application->get_module('Products');

		$sale_rs = $this->Products->get_sale();


		$this->tv['sale_found'] = false;
		$this->tv['sale_arr'] = array();
		if ($sale_rs != false && !$sale_rs->eof())
		{
			$this->tv['sale_found'] = true;

			while(!$sale_rs->eof())
			{
				row_to_vars($sale_rs, $this->tv['sale_arr'][count($this->tv['sale_arr'])+1]);
				$sale_rs->next();
			}
		}
	}


};
?>