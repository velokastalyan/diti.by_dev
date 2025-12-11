<?
require_once(CUSTOM_CLASSES_PATH . 'admin/mastereditpage.php');

class CSubscriberEditPage extends CMasterEditPage
{
	/**
	 * The table name.
	 *
	 * @var array
	 */
	protected $_table = 'subscriber';

	protected $_module = 'Distribution';

	function CSubscriberEditPage(&$app, $template)
	{
		$this->IsSecure = true;
		parent::CMasterEditPage($app, $template);

	}

	function on_page_init()
	{
		parent::on_page_init();

		CValidator::add('email', VRT_EMAIL, false, 0, 255);

	}

	function parse_data()
	{
		parent::parse_data();
	}
}
?>