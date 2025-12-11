<?php
require_once(BASE_CLASSES_PATH. 'frontpage.php');
require_once(CUSTOM_CLASSES_PATH. 'controls/pager.php');

class CSearchPage extends CFrontPage {

	protected $Products;
	protected $find;
	protected $on_page = 12;

	function CSearchPage(&$app, $template){
		parent::__construct($app, $template);

	}

	function on_page_init(){
		parent::on_page_init();
        $this->tv['Global_uri'] = $_SERVER['REQUEST_URI'];

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
		$this->bind_history();
	}

	function on_search_submit($action)
	{
		if(CForm::is_submit('search'))
		{
			CValidator::add_nr('search_text', VRT_TEXT, false, 0, 255);
			$this->Products = $this->Application->get_module('Products');

			$find = InGet('search_text', false);

			$this->tv['search_found'] = false;
			$this->tv['search_arr'] = array();

			$page = InGet('page', 1);
			$page = str_replace('page-', '', $page);
			if($page < 1)
				$page = 1;

			if(CValidator::validate_input())
			{
				$rs = $this->Products->search($find, $page, $this->on_page);

				$cnt = 0;
					if ($rs != false && !$rs->eof())
					{
						$cnt = $rs->get_field('cnt');
						$this->tv['search_found'] = true;

						while(!$rs->eof())
						{
							row_to_vars($rs, $this->tv['search_arr'][count($this->tv['search_arr'])+1]);
							$rs->next();
						}
					}
			}
			else
				$this->tv['_errors'] = CValidator::get_errors();
		}

		$this->bind_data();

		$Pager = new Pager('Search', $page, $cnt, $this->on_page, true);

		$Pager->link = htmlentities ($this->HTTP.substr($_SERVER['REQUEST_URI'],1), null, "UTF-8");
	}
};
?>