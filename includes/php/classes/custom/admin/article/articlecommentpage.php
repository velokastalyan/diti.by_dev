<?php
require_once(CUSTOM_CLASSES_PATH . 'admin/masterpage.php');

class CArticleCommentPage extends CMasterPage
{
	/**
	 * The table name.
	 *
	 * @var string
	 */
	protected $_table = 'article_comment';



	/**
	 * The columns array.
	 *
	 * @var array
	 */

	protected $_title = 'Comments';

	function CArticleCommentPage(&$app, $template)
	{
		$this->IsSecure = true;
		parent::CMasterPage($app, $template);

		$Articles = $this->Application->get_module('Articles');
		$article_rs = $Articles->get_all();
		if($article_rs == false)
			$article_rs = new CRecordSet();

		$article_rs->add_row(array('id' => '', 'title' => RECORDSET_FIRST_ITEM), INSERT_BEGIN);
		CInput::set_select_data('article_id', $article_rs);
		$article_rs->first();

		$status_rs = new CRecordSet();
		$status_rs->add_field('id');
		$status_rs->add_field('title');
		$status_rs->add_row(array('id' => '', 'title' => $this->Application->Localizer->get_string('recordset_first_item')));
		$status_rs->add_row(array('id' => 'active', 'title' => $this->Application->Localizer->get_string('Active')));
		$status_rs->add_row(array('id' => 'not_active', 'title' => $this->Application->Localizer->get_string('Not_active')));

		$this->_filters = array(
			'c#name' => array(
				'title' => 'Имя',
				'type' => FILTER_TEXT,
				'data' => null,
				'condition' => CONDITION_LIKE
			),
			'c#description' => array(
				'title' => 'Текст',
				'type' => FILTER_TEXT,
				'data' => null,
				'condition' => CONDITION_LIKE
			),
			'c#status' => array(
				'title' => $this->Application->Localizer->get_string('status'),
				'type' => FILTER_SELECT,
				'data' => array($status_rs, 'id', 'title'),
				'condition' => CONDITION_EQUAL
			),
		);
	}

	function on_page_init()
	{
		parent::on_page_init();
		parent::page_actions();
	}

	function parse_data()
	{
		if (!parent::parse_data()) return false;

		$this->bind_data();

		return true;
	}

	function bind_data()
	{
		$query = "
        SELECT
        	c.id,
        	c.name,
        	c.description,
        	c.status,
        	c.article_id,
        	a.title as article
        FROM %prefix%article_comment as c
        JOIN %prefix%article as a on ((c.article_id = a.id))
        WHERE ".$this->_where;

		require_once(BASE_CLASSES_PATH . 'controls/dbnavigator.php');
		$nav = new DBNavigator($this->_table, $query, array('parent_path', 'title'), 'id');
		$nav->title = 'Комментарии';

		$header_num = $nav->add_header('name');
		$nav->headers[$header_num]->set_title('Название');
		$nav->headers[$header_num]->set_width('20%');

		$header_num = $nav->add_header('article');
		$nav->headers[$header_num]->set_title('Статья/Новость');
		$nav->headers[$header_num]->set_width('60%');

		$header_num = $nav->add_header('status');
		$nav->headers[$header_num]->set_title('Статус');
		$nav->headers[$header_num]->set_width('20%');



		if($nav->size > 0)
			$this->tv['remove_btn_show'] = true;
		else
			$this->tv['remove_btn_show'] = false;
	}
}
?>