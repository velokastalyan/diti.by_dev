<?php
require_once(CUSTOM_CLASSES_PATH . 'admin/masterpage.php');

class CBannerPage extends CMasterPage
{
	/**
	 * The table name.
	 *
	 * @var string
	 */
	protected $_table = 'banner';



	/**
	 * The columns array.
	 *
	 * @var array
	 */

	protected $_title = 'Banners';

	function CBannerPage(&$app, $template)
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
        	id, description, title,
        	IF(LENGTH(image_filename) > 0, CONCAT('<img src=\"".$this->tv['HTTP']."pub/banners/', id, '/75x75/',image_filename,'\">'), 'Изображение отсутсвует') as image
        FROM %prefix%banner
        WHERE ".$this->_where;

		require_once(BASE_CLASSES_PATH . 'controls/dbnavigator.php');
		$nav = new DBNavigator($this->_table, $query, array('parent_path', 'title'), 'id');
		$nav->title = 'Баннеры';

		$header_num = $nav->add_header('title');
		$nav->headers[$header_num]->set_title($this->Application->Localizer->get_string('title'));
		$nav->headers[$header_num]->set_width('50%');

		$header_num = $nav->add_header('image');
		$nav->headers[$header_num]->set_title($this->Application->Localizer->get_string('image'));
		$nav->headers[$header_num]->set_width('50%');
		$nav->headers[$header_num]->set_align('center');


		if($nav->size > 0)
			$this->tv['remove_btn_show'] = true;
		else
			$this->tv['remove_btn_show'] = false;
	}
}
?>