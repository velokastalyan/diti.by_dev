<?
require_once(CUSTOM_CLASSES_PATH . 'admin/mastereditpage.php');
require_once(BASE_CONTROLS_PATH.'adminuploader.php');

class CBrandFooterEditPage extends CMasterEditPage
{
	/**
	 * The table name.
	 *
	 * @var array
	 */
	protected $_table = 'brand_footer';

	protected $_module = 'Brands';

	function CBrandFooterEditPage(&$app, $template)
	{
		$this->IsSecure = true;
		parent::CMasterEditPage($app, $template);

	}

	function on_page_init()
	{
		parent::on_page_init();
		CValidator::add('title', VRT_TEXT, false, 0, 255);
		CValidator::add('uri', VRT_TEXT, false, 0, 255);
		if(!$this->id)
		{
			CValidator::add('image_filename', VRT_TEXT);
		}
		else
		{
			CValidator::add_nr('image_filename', VRT_TEXT);
		}

		CValidator::add_nr('description', VRT_TEXT, false, 0, 50000);
		CValidator::add_nr('link', VRT_TEXT, false, 0, 255);
		CValidator::add_nr('is_display', VRT_TEXT, false, 0);
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

		$uploader = new AdminUploader('image_filename',  array('output_dir' => 'pub/brands/footer/'.$this->id.'/75x75/'));
		$uploader->bind();
	}


}
?>