<?
require_once(CUSTOM_CLASSES_PATH . 'admin/mastereditpage.php');

class CServiceEditPage extends CMasterEditPage
{
	/**
	 * The table name.
	 *
	 * @var array
	 */
	protected $_table = 'service';

	protected $_module = 'Service';

	function CServiceEditPage(&$app, $template)
	{
		$this->IsSecure = true;
		parent::CMasterEditPage($app, $template);

	}

	function on_page_init()
	{
		parent::on_page_init();
		$Services = $this->Application->get_module('Service');
        $Categories = $this->Application->get_module('Categories');
        $list_rs = $Categories->get_categories();
		$service_rs = $Services->get_services($this->id);
		if($service_rs == false)
		{
			$service_rs = new CRecordSet();
            $service_rs->add_field('id');
            $service_rs->add_field('title');
			$this->tv['_errors'] = $Services->get_last_error();
		}
        $service_rs->add_row(array('id' => '', 'title' => RECORDSET_FIRST_ITEM), INSERT_BEGIN);
        $list_rs->add_row(array('id' => '', 'title' => RECORDSET_FIRST_ITEM), INSERT_BEGIN);
		$status_rs = new CRecordSet();
		$status_rs->add_row(array('id' => '', 'title' => $this->Application->Localizer->get_string('recordset_first_item')));
		$status_rs->add_row(array('id' => 'active', 'title' => $this->Application->Localizer->get_string('Active')));
		$status_rs->add_row(array('id' => 'not_active', 'title' => $this->Application->Localizer->get_string('Not_active')));

		CInput::set_select_data('status', $status_rs, 'id', 'title');
        CInput::set_select_data('category_id', $list_rs);
		CInput::set_select_data('type', $this->Application->list_types);

        $service_rs->first();
		CValidator::add('category_id', VRT_ENUMERATION, false, $list_rs, 'id');
		CValidator::add('description', VRT_TEXT);
		CValidator::add('status', VRT_TEXT);
		CValidator::add('data', VRT_DATETIME);
	}

	function parse_data()
	{
		if(!parent::parse_data())
			return false;

		return true;
	}
}
?>