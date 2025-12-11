<?php
require_once(CUSTOM_CLASSES_PATH . 'admin/masterpage.php');

class CCategoryPage extends CMasterPage
{
	/**
	 * The table name.
	 *
	 * @var string
	 */
	protected $_table = 'category';



	/**
	 * The columns array.
	 *
	 * @var array
	 */

	protected $_title = 'Categories';

	function CCategoryPage(&$app, $template)
	{
		$this->IsSecure = true;
		parent::CMasterPage($app, $template);
		$Categories = $this->Application->get_module('Categories');
		$category_rs = $Categories->get_categories_list(2);
		if($category_rs == false) $category_rs = new CRecordSet();
		$category_rs->add_row(array('id' => 0, 'title' => 'Главная категория'), INSERT_BEGIN);
		$category_rs->add_row(array('id' => '', 'title' => RECORDSET_FIRST_ITEM), INSERT_BEGIN);

		$this->_filters = array(
			'parent_id' => array(
				'title' => 'Родительская категория',
				'type' => FILTER_SELECT,
				'data' => array($category_rs, 'id', 'title'),
				'condition' => CONDITION_EQUAL
			),
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
        	IF(LENGTH(parent_path) > 0, parent_path, 'Главная категория') as parent_path,
        	title,
        	IF(LENGTH(image_filename) > 0, CONCAT('<img src=\"".$this->tv['HTTP']."pub/categories/',id,'/75x75/',image_filename,'\">'), 'Изображение отсутсвует') AS image_filename
        FROM %prefix%category
        WHERE ".$this->_where;

		require_once(BASE_CLASSES_PATH . 'controls/dbnavigator.php');
		$nav = new DBNavigator($this->_table, $query, array('parent_path', 'title'), 'id');
		$nav->title = 'Категории';

		$header_num = $nav->add_header('parent_path');
		$nav->headers[$header_num]->set_title('Родительская категория');
		$nav->headers[$header_num]->set_width('30%');

		$header_num = $nav->add_header('title');
		$nav->headers[$header_num]->set_title('Name');
		$nav->headers[$header_num]->set_width('65%');

		$header_num = $nav->add_header('image_filename');
		$nav->headers[$header_num]->set_title('Изображение');
		$nav->headers[$header_num]->set_width('85px');
		$nav->headers[$header_num]->set_align("center");

		if($nav->size > 0)
			$this->tv['remove_btn_show'] = true;
		else
			$this->tv['remove_btn_show'] = false;
	}
}
?>