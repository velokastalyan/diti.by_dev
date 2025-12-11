<?php
require_once(BASE_CLASSES_PATH. 'frontpage.php');
require_once(CUSTOM_CLASSES_PATH. 'controls/pager.php');

class CArticlesPage extends CFrontPage {

	protected $user_rs;
	protected $Articles;
	protected $Comments;
	protected $on_page = 20;

	function CArticlesPage(&$app, $template){
		parent::__construct($app, $template);
	}

	function on_page_init(){
		parent::on_page_init();
		$this->bind_articles_list();
		$this->bind_comment();

	}

	function parse_data(){

		if(!parent::parse_data())
			return false;

		return true;
	}

	function bind_data()
	{
		$this->bind_articles_list();
		$this->bind_static();
		$this->bind_menu();
		$this->bind_brand();
		$this->bind_history();
	}

	function bind_articles_list()
	{
		$page = InUri('page', 1);
		$page = str_replace('page-', '', $page);
		if($page < 1)
			$page = 1;
		$this->tv['article_uri'] = InUri('article_uri', false);
		$uri = $this->tv['article_uri'];

		$this->Articles = $this->Application->get_module('Articles');
		$articles_rs = $this->Articles->get_articles($uri,$page, $this->on_page);

		$this->tv['article_found'] = false;
		$this->tv['article_arr'] = array();

		$cnt = 0;

		if ($articles_rs != false && !$articles_rs->eof())
		{
			$cnt = $articles_rs->get_field('cnt');
			$this->tv['article_found'] = true;

			while(!$articles_rs->eof())
			{
				row_to_vars($articles_rs, $this->tv['article_arr'][count($this->tv['article_arr'])+1]);
				$articles_rs->next();
			}
		}

		if (count($this->tv['article_arr']) == 1)
		{
			$this->tv['meta_title'] = $this->tv['article_arr'][1]['meta_title'];
			$this->tv['meta_description'] = $this->tv['article_arr'][1]['meta_description'];
			$this->tv['meta_keywords'] = $this->tv['article_arr'][1]['meta_keywords'];
		}

		$Pager = new Pager('Articles', $page, $cnt, $this->on_page);
		$Pager->link = $this->HTTP.'articles/';
	}


	function bind_comment()
	{
		$this->Comment = $this->Application->get_module('Articles');

		$comment_rs = $this->Comment->get_comment($this->tv['article_arr'][1]['id']);

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
			$this->tv['article_uri'] = InUri('article_uri', false);
			$uri = $this->tv['article_uri'];

			CValidator::add('name', VRT_TEXT, false, 0, 255);
			CValidator::add('description', VRT_TEXT, false, 0, 1000);

            $this->tv['comment_added'] = false;

			$this->Articles = $this->Application->get_module('Articles');
			$rs = $this->Articles->get_articles($uri);
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