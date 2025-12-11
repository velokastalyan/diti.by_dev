<?php
require_once(CUSTOM_CLASSES_PATH . 'admin/masterpage.php');

class CDistributionPage extends CMasterPage
{
	/**
	 * The table name.
	 *
	 * @var string
	 */
	protected $_table = 'distribution';

	function CDistributionPage(&$app, $template)
	{
		$this->IsSecure = true;
		parent::CMasterPage($app, $template);
		$this->DataBase = &$this->Application->DataBase;
		$this->_filters = array(
			'dd#subject' => array(
				'title' => $this->Application->Localizer->get_string('subject'),
				'type' => FILTER_TEXT,
				'data' => null,
				'condition' => CONDITION_LIKE
			),
			'dd#was_sent' => array(
				'title' => $this->Application->Localizer->get_string('was_sent'),
				'type' => FILTER_SELECT,
				'data' => array(array( '' => $this->Application->Localizer->get_string('any_status'), OBJECT_NOT_ACTIVE => $this->Application->Localizer->get_string('not'), OBJECT_ACTIVE => $this->Application->Localizer->get_string('yes'))),
				'condition' => CONDITION_EQUAL
			),
			'dd#dispatch_date' => array(
				'title' => $this->Application->Localizer->get_string('dispatch_date_from'),
				'type' => FILTER_DATE
			),
			'd#create_date' => array(
				'title' => $this->Application->Localizer->get_string('create_date_from'),
				'type' => FILTER_DATE
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
        		d.id,
        		d.subject as subject,
				CASE d.send WHEN 1 THEN '<img src=\"".$this->tv['IMAGES']."icons/apply.gif\">' ELSE '<img src=\"".$this->tv['IMAGES']."icons/apply_disabled.gif\">' END AS send,
        		DATE_FORMAT(d.dispatch_date, '%b %d, %Y %h:%i %p') AS dispatch_date
            FROM
            	%prefix%distribution d
            WHERE ".$this->_where. "";

		require_once(BASE_CLASSES_PATH . 'controls/dbnavigator.php');
		$nav = new DBNavigator($this->_table, $query, array('title','status','public_date','create_date'), 'id');
		$nav->title = $this->Application->Localizer->get_string('distribution');

		$header_num = $nav->add_header('subject');
		$nav->headers[$header_num]->set_title($this->Application->Localizer->get_string('subject'));
		$nav->headers[$header_num]->set_width('75%');

		$header_num = $nav->add_header('send');
		$nav->headers[$header_num]->set_title($this->Application->Localizer->get_string('was_sent'));
		$nav->headers[$header_num]->set_width('5%');
		$nav->headers[$header_num]->set_align('center');

		$header_num = $nav->add_header('dispatch_date');
		$nav->headers[$header_num]->set_title($this->Application->Localizer->get_string('public_date'));
		$nav->headers[$header_num]->set_width('10%');
		$nav->headers[$header_num]->set_align('center');

		if($nav->size > 0)
			$this->tv['remove_btn_show'] = true;
		else
			$this->tv['remove_btn_show'] = false;
	}
}
?>