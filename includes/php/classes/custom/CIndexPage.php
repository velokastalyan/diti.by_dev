<?php
require_once(BASE_CLASSES_PATH. 'frontpage.php');

class CIndexPage extends CFrontPage {
	
	protected $user_rs;
	protected $Videos;
	protected $Products;
	protected $Articles;
	
	function CIndexPage(&$app, $template){
		parent::__construct($app, $template);	
	}
	
	function on_page_init(){
		parent::on_page_init();
        $this->Static_page = $this->Application->get_module('Pages');
        $page_rs = $this->Static_page->get_page_by_uri('index');

        $this->tv['page_found'] = false;
        $this->tv['page_arr'] = array();

        if ($page_rs !=false && !$page_rs->eof())
        {
            $this->tv['page_found'] = true;

            row_to_vars($page_rs, $this->tv['page_arr'][count($this->tv['page_arr'])+1]);
        }

        $this->tv['meta_title'] = $this->tv['page_arr'][1]['meta_title'];
        $this->tv['meta_description'] = $this->tv['page_arr'][1]['meta_description'];
        $this->tv['meta_keywords'] = $this->tv['page_arr'][1]['meta_keywords'];
	}
	
	function parse_data(){
		
		if(!parent::parse_data())
			return false;

		$this->bind_video();
		$this->bind_slider();
		$this->bind_recommend();
		$this->bind_news();
		
		return true;
	}

	function bind_video()
	{
		$this->Videos = $this->Application->get_module('Videos');
		$rs = $this->Videos->get_video();

		$this->tv['vd_title'] = array();
		$this->tv['vd_link'] = array();

		if($rs !== false && !$rs->eof())
		{
			$this->tv['vd_found'] = true;
			while(!$rs->eof())
			{
				$this->tv['vd_title'][count($this->tv['vd_title'])+1] = $rs->get_field('title');
				$this->tv['vd_link'][count($this->tv['vd_link'])+1] = $rs->get_field('link');

				$rs->next();
			}
		}
	}

	function bind_slider()
	{
		$this->Slyders = $this->Application->get_module('Banners');
		$slider_rs = $this->Slyders->get_all();

		$this->tv['slider_arr'] = array();

		if ($slider_rs != false)
		{
			$this->tv['slider_found'] = true;

			while(!$slider_rs->eof())
			{
				row_to_vars($slider_rs, $this->tv['slider_arr'][count($this->tv['slider_arr'])+1]);
				$slider_rs->next();
			}
		}
	}



	function bind_recommend()
	{
		$this->Products = $this->Application->get_module('Products');
		$rec_rs = $this->Products->get_recommend();

		if ($rec_rs != false)
		{
			$this->tv['recommend_found'] = true;

			while(!$rec_rs->eof())
			{
				row_to_vars($rec_rs, $this->tv['recommend'][count($this->tv['recommend'])+1]);
				$rec_rs->next();
			}
		}
	}

	function bind_news()
	{
		$this->News = $this->Application->get_module('Articles');
		$news_rs = $this->News->get_news_main();

		$this->tv['news_arr'] = array();

		if ($news_rs != false)
		{
			$this->tv['news_found'] = true;
			while(!$news_rs->eof())
			{
				row_to_vars($news_rs, $this->tv['news_arr'][count($this->tv['news_arr'])+1]);
				$news_rs->next();
			}
		}
	}
};
?>