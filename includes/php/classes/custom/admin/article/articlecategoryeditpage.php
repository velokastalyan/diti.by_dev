<?
require_once(CUSTOM_CLASSES_PATH . 'admin/mastereditpage.php');
require_once(BASE_CONTROLS_PATH.'adminuploader.php');

class CArticleCategoryEditPage extends CMasterEditPage
{
	/**
	 * The table name.
	 *
	 * @var array
	 */
	protected $_table = 'article_category';

	protected $_module = 'Articles';

	function CArticleCategoryEditPage(&$app, $template)
	{
		$this->IsSecure = true;
		parent::CMasterEditPage($app, $template);

	}

	function on_page_init()
	{
		parent::on_page_init();

		CValidator::add('title', VRT_TEXT, false, 0, 255);
		CValidator::add('uri', VRT_TEXT, false, 0, 255);
		CValidator::add_nr('position', VRT_TEXT, false, 0, 255);
		CValidator::add('meta_title', VRT_TEXT, false, 0, 255);
		CValidator::add('meta_description', VRT_TEXT, false, 0, 255);

	}

	function parse_data()
	{
		if(!parent::parse_data())
			return false;

		return true;
	}

	function bind_data()
	{
		parent::bind_data();
	}
}
?>