<?php
require_once(BASE_CLASSES_PATH. 'frontpage.php');
require_once(CUSTOM_CONTROLS_PATH. 'pager.php');

class CNewsPage extends CFrontPage {

	protected $user_rs;
	protected $News;
	protected $on_page = 30;

	function CNewsPage(&$app, $template){
		parent::__construct($app, $template);
	}

	function on_page_init(){
		parent::on_page_init();

		$this->news_page = $this->tv['news_page'] = InUri('news_page', false);

		$this->bind_news_list();
		$this->bind_comment();
	}

	function bind_data()
	{
		$this->bind_news_list();
		$this->bind_static();
		$this->bind_menu();
		$this->bind_brand();
		$this->bind_history();
	}

	function parse_data(){

		if(!parent::parse_data())
			return false;

		return true;
	}

	function bind_news_list()
	{
		$page = InUri('page', 1);
		$page = str_replace('page-', '', $page);


		if($page < 1)
			$page = 1;

		$this->tv['news_uri'] = InUri('news_uri', false);
		$uri = $this->tv['news_uri'];


		$this->News = $this->Application->get_module('Articles');
		$news_rs = $this->News->get_news($uri, $page, $this->on_page);

		$this->tv['news_found'] = false;
		$this->tv['news_arr'] = array();

		$cnt = 1;

		if ($news_rs != false && !$news_rs->eof())
		{
			$cnt = $news_rs->get_field('cnt');
			$this->tv['news_found'] = true;

			while(!$news_rs->eof())
			{
				row_to_vars($news_rs, $this->tv['news_arr'][count($this->tv['news_arr'])+1]);
				$news_rs->next();
			}
		}

		$Pager = new Pager('News', $page, $cnt, $this->on_page, true);
		$Pager->link = $this->HTTP.'news/';

        if ( empty($uri) ) {
            $this->PAGE_TITLE = $this->tv['meta_title'] = 'Статьи DiTi.by';
        }
        else {
            $news_rs->first();
            $this->PAGE_TITLE = $this->tv['meta_title'] = $news_rs->get_field('meta_title');
            $this->PAGE_DESCRIPTION = $this->tv['meta_description'] = $news_rs->get_field('meta_description');
        }
	}

	function bind_comment()
	{
		$this->Comment = $this->Application->get_module('Articles');

		$comment_rs = $this->Comment->get_comment($this->tv['news_arr'][1]['id']);


		$this->tv['comment_found'] = false;
		$this->tv['comment_arr'] = array();
		if ($comment_rs != false && !$comment_rs->eof())
		{
			$this->tv['comment_found'] = true;

			while(!$comment_rs->eof())
			{
				row_to_vars($comment_rs, $this->tv['comment_arr'][count($this->tv['comment_arr'])+1]);
				$comment_rs->next();
			}
		}

	}

	function on_add_comment_submit($action)
	{
		if(CForm::is_submit('add_comment'))
		{
			$this->tv['news_uri'] = InUri('news_uri', false);
			$uri = $this->tv['news_uri'];

            CValidator::add('name', VRT_TEXT, false, 0, 255);
			CValidator::add('description', VRT_TEXT, false, 0, 1000);

            $this->tv['comment_added'] = false;

			$this->Articles = $this->Application->get_module('Articles');
			$rs = $this->Articles->get_news($uri);
			$this->article_id = $rs->get_field('id');

			if(CValidator::validate_input() && ($_SESSION['text'] != $this->tv['description']))
			{
				$Comment = $this->Application->get_module('Articles');
				$this->tv['form_sent'] = true;
				if($Comment->add_article_comment(array( 'article_id' => $this->article_id, 'name' => $this->tv['name'], 'status' => 'not_active', 'description' => $this->tv['description'])))
				{
                    session_start();
                    $_SESSION['text'] = $this->tv['description'];
                    $this->article_id = '';
					$this->tv['name'] = '';
					$this->tv['description'] = '';
                    $this->tv['error_name'] = false;
                    $this->tv['error_text'] = false;
				}
				else {
					$this->tv['_errors'] = $Comment->get_last_error();
                }
				$this->tv['comment_added'] = true;
			}
			else {
				$this->tv['_errors'] = CValidator::get_errors();
                if ($this->tv['name'] == '') $this->tv['error_name'] = true;
                if ($this->tv['description'] == '') $this->tv['error_text'] = true;
            }

            $this->bind_data();
		}
	}
};
?>