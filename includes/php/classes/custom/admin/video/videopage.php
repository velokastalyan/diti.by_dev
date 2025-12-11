<?php
require_once(CUSTOM_CLASSES_PATH . 'admin/masterpage.php');

class CVideoPage extends CMasterPage
{
	/**
	 * The table name.
	 *
	 * @var string
	 */
	protected $_table = 'video';

	/**
	 * The columns array.
	 *
	 * @var array
	 */
	protected $_columns_arr = array('title' => 'Title');

	function CVideoPage(&$app, $template)
	{
		$this->IsSecure = true;
		parent::CMasterPage($app, $template);
		$this->DataBase = &$this->Application->DataBase;
		$this->_filters = array(
			'title' => array(
				'title' => $this->Application->Localizer->get_string('title'),
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
        		CONCAT('<a href=', link, ' target=_blank >', link, '</a>') as video
            FROM 
            	%prefix%video
            WHERE ".$this->_where;

		require_once(BASE_CLASSES_PATH . 'controls/dbnavigator.php');
		$nav = new DBNavigator($this->_table, $query, array('title'), 'id');
		$nav->title = 'Бренды';

		$header_num = $nav->add_header('title');
		$nav->headers[$header_num]->set_title('Заголовок');
		$nav->headers[$header_num]->set_width('60%');

		$header_num = $nav->add_header('video');
		$nav->headers[$header_num]->set_title('Видеозапись');
		$nav->headers[$header_num]->set_width('40%');
		$nav->headers[$header_num]->set_align('center');

		if($nav->size > 0)
			$this->tv['remove_btn_show'] = true;
		else
			$this->tv['remove_btn_show'] = false;
	}


}
?>