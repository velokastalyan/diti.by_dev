<?php
require_once(CUSTOM_CLASSES_PATH . 'admin/masterpage.php');

class CArticlePage extends CMasterPage
{
	/**
	 * The table name.
	 *
	 * @var string
	 */
	protected $_table = 'article';



	/**
	 * The columns array.
	 *
	 * @var array
	 */

	protected $_title = 'Articles';

	function CArticlePage(&$app, $template)
	{
		$this->IsSecure = true;
		parent::CMasterPage($app, $template);

		$Category = $this->Application->get_module('Articles');
		$category_rs = $Category->get_category();
		if($category_rs == false)
			$category_rs = new CRecordSet();

		$category_rs->add_row(array('id' => '', 'title' => RECORDSET_FIRST_ITEM), INSERT_BEGIN);
		CInput::set_select_data('article_id', $article_rs);
		$category_rs->first();

		$this->_filters = array(
			'a#title' => array(
				'title' => 'Название',
				'type' => FILTER_TEXT,
				'data' => null,
				'condition' => CONDITION_LIKE
			),
			'a#id_cat' => array(
			'title' => $this->Application->Localizer->get_string('category'),
			'type' => FILTER_SELECT,
			'data' => array($category_rs, 'id', 'title'),
			'condition' => CONDITION_EQUAL
			)
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
        	a.id,
        	a.title,
        	a.public_date,
        	a.status,
        	a.id_cat,
        	IF(LENGTH(c.title) > 0, c.title, 'Без категории') as category
        FROM %prefix%article as a
        LEFT JOIN %prefix%article_category as c on ((a.id_cat = c.id))
        WHERE ".$this->_where;

		require_once(BASE_CLASSES_PATH . 'controls/dbnavigator.php');
		$nav = new DBNavigator($this->_table, $query, array('title','public_date'), 'id');
		$nav->title = 'Статьи';

		$header_num = $nav->add_header('title');
		$nav->headers[$header_num]->set_title('Название');
		$nav->headers[$header_num]->set_width('55%');

		$header_num = $nav->add_header('category');
		$nav->headers[$header_num]->set_title('Категории');
		$nav->headers[$header_num]->set_width('45%');

		$header_num = $nav->add_header('public_date');
		$nav->headers[$header_num]->set_title('Дата');
		$nav->headers[$header_num]->set_width('85px');
		$nav->headers[$header_num]->set_align("center");

		if($nav->size > 0)
			$this->tv['remove_btn_show'] = true;
		else
			$this->tv['remove_btn_show'] = false;
	}
}
?>