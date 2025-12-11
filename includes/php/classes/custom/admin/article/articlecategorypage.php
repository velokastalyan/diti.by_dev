<?php
require_once(CUSTOM_CLASSES_PATH . 'admin/masterpage.php');

class CArticleCategoryPage extends CMasterPage
{
	/**
	 * The table name.
	 *
	 * @var string
	 */
	protected $_table = 'article_category';



	/**
	 * The columns array.
	 *
	 * @var array
	 */

	protected $_title = 'Category';

	function CArticleCategoryPage(&$app, $template)
	{
		$this->IsSecure = true;
		parent::CMasterPage($app, $template);

		$this->_filters = array(
			'title' => array(
				'title' => 'Название',
				'type' => FILTER_TEXT,
				'data' => null,
				'condition' => CONDITION_LIKE
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
        	id,
        	title,
        	position
        FROM %prefix%article_category
        WHERE ".$this->_where;

		require_once(BASE_CLASSES_PATH . 'controls/dbnavigator.php');
		$nav = new DBNavigator($this->_table, $query, array('parent_path', 'title'), 'id');
		$nav->title = 'Категории';

		$header_num = $nav->add_header('title');
		$nav->headers[$header_num]->set_title('Название');
		$nav->headers[$header_num]->set_width('80%');

		$header_num = $nav->add_header('position');
		$nav->headers[$header_num]->set_title('Позиция');
		$nav->headers[$header_num]->set_width('20%');



		if($nav->size > 0)
			$this->tv['remove_btn_show'] = true;
		else
			$this->tv['remove_btn_show'] = false;
	}
}
?>