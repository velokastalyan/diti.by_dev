<?
require_once(CUSTOM_CLASSES_PATH . 'admin/masterpage.php');

class CSocialNetworkPage extends CAdminPage
{
	function CSocialNetworkPage(&$app, $template)
	{
		parent::CAdminPage($app, $template);
	}

	function on_page_init() 
	{
		parent::on_page_init();
	}

	function parse_data()
	{
		if (!parent::parse_data()) return false;
        return true;
	}
}
?>