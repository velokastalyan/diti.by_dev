<?php
require_once(CUSTOM_CLASSES_PATH . 'admin/masterpage.php');

class CSubscribersPage extends CMasterPage
{
	/**
	 * The table name.
	 *
	 * @var string
	 */
	protected $_table = 'subscriber';

	function CSubscribersPage(&$app, $template)
	{
		$this->IsSecure = true;
		parent::CMasterPage($app, $template);
		$this->DataBase = &$this->Application->DataBase;
		$this->_filters = array(
			's#email' => array(
				'title' => $this->Application->Localizer->get_string('email'),
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
        		s.id,
        		s.email
            FROM 
            	%prefix%subscriber s
            WHERE ".$this->_where. "";

		require_once(BASE_CLASSES_PATH . 'controls/dbnavigator.php');
		$nav = new DBNavigator($this->_table, $query, array('title'), 'id');
		$nav->title = $this->Application->Localizer->get_string('subscribers');

		$header_num = $nav->add_header('email');
		$nav->headers[$header_num]->set_title($this->Application->Localizer->get_string('email'));
		$nav->headers[$header_num]->set_width('95%');
		$nav->headers[$header_num]->set_mail(true);

		if($nav->size > 0)
			$this->tv['remove_btn_show'] = true;
		else
			$this->tv['remove_btn_show'] = false;
	}
}
?>