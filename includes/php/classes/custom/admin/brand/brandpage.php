<?php
require_once(CUSTOM_CLASSES_PATH . 'admin/masterpage.php');

class CBrandPage extends CMasterPage
{
	/**
	 * The table name.
	 *
	 * @var string
	 */
	protected $_table = 'brand';

	/**
	 * The columns array.
	 *
	 * @var array
	 */
	protected $_columns_arr = array('title' => 'Title');

	function CBrandPage(&$app, $template)
	{
		$this->IsSecure = true;
		parent::CMasterPage($app, $template);
		$this->DataBase = &$this->Application->DataBase;
		$this->_filters = array(
			'title' => array(
				'title' => $this->Application->Localizer->get_string('Title'),
				'type' => FILTER_TEXT,
				'data' => null,
				'condition' => CONDITION_LIKE
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
		$query = "SELECT
        		id,
        		title,
        		IF(LENGTH(image_filename) > 0, CONCAT('<img src=\"".$this->tv['HTTP']."pub/brands/', id, '/75x75/',image_filename,'\">'), 'Изображение отсутсвует') as image
            FROM 
            	%prefix%brand
            WHERE ".$this->_where;

		require_once(BASE_CLASSES_PATH . 'controls/dbnavigator.php');
		$nav = new DBNavigator($this->_table, $query, array('title'), 'id');
		$nav->title = 'Бренды';

		$header_num = $nav->add_header('title');
		$nav->headers[$header_num]->set_title('Название');
		$nav->headers[$header_num]->set_width('99%');

		$header_num = $nav->add_header('image');
		$nav->headers[$header_num]->set_title('Изображение');
		$nav->headers[$header_num]->set_width('200');
		$nav->headers[$header_num]->set_align('center');

		if($nav->size > 0)
			$this->tv['remove_btn_show'] = true;
		else
			$this->tv['remove_btn_show'] = false;
	}
}
?>