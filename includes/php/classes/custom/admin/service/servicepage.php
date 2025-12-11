<?php
require_once(CUSTOM_CLASSES_PATH . 'admin/masterpage.php');

class CServicePage extends CMasterPage
{
	/**
	 * The table name.
	 *
	 * @var string
	 */
	protected $_table = 'service';

	/**
	 * The columns array.
	 *
	 * @var array
	 */
	protected $_columns_arr = array('title');

	protected $_title = 'Service';

	function CServicePage(&$app, $template)
	{
		$this->IsSecure = true;
		parent::CMasterPage($app, $template);
		$this->DataBase = &$this->Application->DataBase;
		$Services = $this->Application->get_module('Service');
		$service_rs = $Services->get_services();
		if($service_rs == false) $service_rs = new CRecordSet();
		$service_rs->add_row(array('id' => '', 'title' => RECORDSET_FIRST_ITEM), INSERT_BEGIN);

		$status_rs = new CRecordSet();
		$status_rs->add_field('id');
		$status_rs->add_field('title');
		$status_rs->add_row(array('id' => '', 'title' => $this->Application->Localizer->get_string('recordset_first_item')));
		$status_rs->add_row(array('id' => 'active', 'title' => $this->Application->Localizer->get_string('Active')));
		$status_rs->add_row(array('id' => 'not_active', 'title' => $this->Application->Localizer->get_string('Not_active')));

		$this->_filters = array(
			'c#category_id' => array(
				'title' => $this->Application->Localizer->get_string('category'),
				'type' => FILTER_SELECT,
				'data' => array($service_rs, 'id', 'title'),
				'condition' => CONDITION_EQUAL
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
        	s.id,
        	s.category_id,
        	s.description,
        	IF(s.status = 'not_active', 'Не активный',
        		IF(s.status = 'active', 'Активный', '')
        	) as status,
        	c.title as category

        FROM %prefix%service s
        JOIN %prefix%category c on (s.category_id = c.id)
        WHERE ".$this->_where;

		require_once(BASE_CLASSES_PATH . 'controls/dbnavigator.php');
		$nav = new DBNavigator($this->_table, $query, array('category'), 's.id');
		$nav->title = 'Сервис';

		$header_num = $nav->add_header('category');
		$nav->headers[$header_num]->set_title('Категория');
		$nav->headers[$header_num]->set_width('40%');

		$header_num = $nav->add_header('description');
		$nav->headers[$header_num]->set_title('Описание');
		$nav->headers[$header_num]->set_width('40%');

		$header_num = $nav->add_header('status');
		$nav->headers[$header_num]->set_title('Статус');
		$nav->headers[$header_num]->set_width('20%');
		$nav->headers[$header_num]->set_align("center");

		if($nav->size > 0)
			$this->tv['remove_btn_show'] = true;
		else
			$this->tv['remove_btn_show'] = false;
	}
}
?>