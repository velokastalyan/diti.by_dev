<?
require_once(CUSTOM_CLASSES_PATH . 'admin/mastereditpage.php');
require_once(BASE_CONTROLS_PATH.'adminuploader.php');

class CBrandEditPage extends CMasterEditPage
{
	/**
	 * The table name.
	 *
	 * @var array
	 */
	protected $_table = 'brand';

	protected $_module = 'Brands';

	function CBrandEditPage(&$app, $template)
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
		CValidator::add('meta_title', VRT_TEXT, false, 0, 80);
		CValidator::add_nr('meta_keywords', VRT_TEXT, false, 0, 160);
		CValidator::add('meta_description', VRT_TEXT, false, 0, 160);
		CValidator::add_nr('link', VRT_TEXT, false, 0, 255);
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

		$uploader = new AdminUploader('image_filename',  array('output_dir' => 'pub/brands/'.$this->id.'/75x75/'));
		$uploader->bind();
	}


}
?>