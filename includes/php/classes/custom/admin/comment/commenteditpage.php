<?
require_once(CUSTOM_CLASSES_PATH . 'admin/mastereditpage.php');

class CCommentEditPage extends CMasterEditPage
{
	/**
	 * The table name.
	 *
	 * @var array
	 */
	protected $_table = 'comment';

	protected $_module = 'Comments';

	function CCommentEditPage(&$app, $template)
	{
		$this->IsSecure = true;
		parent::CMasterEditPage($app, $template);

	}

	function on_page_init()
	{
		parent::on_page_init();
		$Products = $this->Application->get_module('Products');
		$product_rs = $Products->get_all('', $this->id);
		if($product_rs == false)
		{
			$product_rs = new CRecordSet();
			$product_rs->add_field('id');
			$product_rs->add_field('title');
			$this->tv['_errors'] = $Products->get_last_error();
		}
		$product_rs->add_row(array('id' => '', 'title' => RECORDSET_FIRST_ITEM), INSERT_BEGIN);


		$rate_rs = new CRecordSet();
		$rate_rs->add_row(array('id' => '', 'title' => $this->Application->Localizer->get_string('recordset_first_item')));
		$rate_rs->add_row(array('id' => 1, 'title' => $this->Application->Localizer->get_string('one_star')));
		$rate_rs->add_row(array('id' => 2, 'title' => $this->Application->Localizer->get_string('two_stars')));
		$rate_rs->add_row(array('id' => 3, 'title' => $this->Application->Localizer->get_string('three_stars')));
		$rate_rs->add_row(array('id' => 4, 'title' => $this->Application->Localizer->get_string('four_stars')));
		$rate_rs->add_row(array('id' => 5, 'title' => $this->Application->Localizer->get_string('five_stars')));

		$status_rs = new CRecordSet();
		$status_rs->add_row(array('id' => '', 'title' => $this->Application->Localizer->get_string('recordset_first_item')));
		$status_rs->add_row(array('id' => 'active', 'title' => $this->Application->Localizer->get_string('Active')));
		$status_rs->add_row(array('id' => 'not_active', 'title' => $this->Application->Localizer->get_string('Not_active')));

		CInput::set_select_data('rate', $rate_rs, 'id', 'title');
		CInput::set_select_data('status', $status_rs, 'id', 'title');
		CInput::set_select_data('product_id', $product_rs);
		CInput::set_select_data('type', $this->Application->list_types);


		$product_rs->first();
		CValidator::add('product_id', VRT_ENUMERATION, false, $product_rs, 'id');
		CValidator::add('title', VRT_TEXT, false, 0, 255);
		CValidator::add('name', VRT_TEXT, false, 0, 255);
		CValidator::add('rate', VRT_NUMBER, false, 0);
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