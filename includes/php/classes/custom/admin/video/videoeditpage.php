<?
require_once(CUSTOM_CLASSES_PATH . 'admin/mastereditpage.php');

class CVideoEditPage extends CMasterEditPage
{
	/**
	 * The table name.
	 *
	 * @var array
	 */
	protected $_table = 'video';

	protected $_module = 'Videos';

	function CVideoEditPage(&$app, $template)
	{
		$this->IsSecure = true;
		parent::CMasterEditPage($app, $template);

	}

	function on_page_init()
	{
		parent::on_page_init();


		CValidator::add('title', VRT_TEXT, false, 0, 255);
		CValidator::add('link', VRT_TEXT, false, 0, 255);
		CValidator::add('position', VRT_TEXT, false, 0);

	}

	function parse_data()
	{
		if(!parent::parse_data())
			return false;

		return true;
	}
}
?>