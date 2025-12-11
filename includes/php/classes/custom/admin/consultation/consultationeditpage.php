<?
require_once(CUSTOM_CLASSES_PATH . 'admin/mastereditpage.php');
require_once(BASE_CONTROLS_PATH.'adminuploader.php');

class CConsultationEditPage extends CMasterEditPage
{
	/**
	 * The table name.
	 *
	 * @var array
	 */
	protected $_table = 'consultation';

	protected $_module = 'Consultation';

	function CConsultationEditPage(&$app, $template)
	{
		$this->IsSecure = true;
		parent::CMasterEditPage($app, $template);
	}

	function on_page_init()
	{
		parent::on_page_init();

		$Products = $this->Application->get_module('Products');
		$product_rs = $Products->get_product_by_id($this->tv['product_id']);

		$this->tv['r_product_arr'] = array();
		if ($product_rs != false)
		{
			while(!$product_rs->eof())
			{
				row_to_vars($product_rs, $this->tv['r_product_arr'][count($this->tv['r_product_arr'])+1]);
				$product_rs->next();
			}
		}
		$this->tv['r_title'] = $this->tv['r_product_arr'][1]['title'].' - '.$this->tv['r_product_arr'][1]['b_title'].' (Код товара: '.$this->tv['r_product_arr'][1]['id'].')';

		$status_rs = new CRecordSet();
		$status_rs->add_field('id');
		$status_rs->add_field('title');
		$status_rs->add_row(array('id' => '', 'title' => $this->Application->Localizer->get_string('recordset_first_item')));
		$status_rs->add_row(array('id' => 1, 'title' => $this->Application->Localizer->get_string('Active')));
		$status_rs->add_row(array('id' => 0, 'title' => $this->Application->Localizer->get_string('Not_active')));

		CInput::set_select_data('status', $status_rs, 'id', 'title');

		CValidator::add('product_id', VRT_TEXT, false, 0, 255);
		CValidator::add('name', VRT_TEXT, false, 0, 255);
		CValidator::add('phone', VRT_TEXT, false, 0, 255);
		CValidator::add('status', VRT_TEXT, false, 0, 255);

	}

	function parse_data()
	{
		if(!parent::parse_data())
			return false;

		return true;
	}


}
?>