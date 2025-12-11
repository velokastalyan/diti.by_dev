<?
require_once(CUSTOM_CLASSES_PATH . 'admin/mastereditpage.php');
require_once(BASE_CONTROLS_PATH.'adminuploader.php');

class CBannerEditPage extends CMasterEditPage
{
	/**
	 * The table name.
	 *
	 * @var array
	 */
	protected $_table = 'banner';

	protected $_module = 'Banners';

	function CBannerEditPage(&$app, $template)
	{
		$this->IsSecure = true;
		parent::CMasterEditPage($app, $template);

	}

	function on_page_init()
	{
		parent::on_page_init();

		CValidator::add('title', VRT_TEXT, false, 0, 255);
		CValidator::add('description', VRT_TEXT, false, 0, 255);
		CValidator::add('uri', VRT_TEXT, false, 0, 255);
		CValidator::add_nr('image_filename', VRT_TEXT, false, 0, 255);
		CValidator::add('position', VRT_NUMBER);
        CValidator::add('link', VRT_TEXT, false, 0, 255);
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

		$uploader = new AdminUploader('image_filename',  array('output_dir' => 'pub/banners/'.$this->id.'/75x75/'));
		$uploader->bind();
	}
}
?>