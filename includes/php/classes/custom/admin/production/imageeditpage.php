<?
require_once(CUSTOM_CLASSES_PATH . 'admin/mastereditpage.php');
require_once(BASE_CONTROLS_PATH.'adminuploader.php');

class CImageEditPage extends CMasterEditPage

{
	/**
	 * The table name.
	 *
	 * @var array
	 */
	protected $_table = 'product_image';

	protected $_module = 'Products';

	function CImageEditPage(&$app, $template)
	{
		$this->IsSecure = true;
		parent::CMasterEditPage($app, $template);
		$this->Products = $this->Application->get_module('Products');
		if(!$this->product_id = InGet('product_id', false))
			$this->internalRedirect($this->Application->Navi->getUri('parent'));
		$this->bind_product();
	}


	function on_page_init()
	{
		parent::on_page_init();
		if($this->id)
			CValidator::add_nr('image_filename', VRT_TEXT);
		else
			CValidator::add('image_filename', VRT_TEXT);
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
		$uploader = new AdminUploader('image_filename',  array('output_dir' => 'pub/products/'.$this->tv['product_id'].'/'));
		$uploader->bind();
	}

	function bind_product()
	{
		$product_rs = $this->Products->get($this->product_id);
		if($product_rs == false || $product_rs->eof())
		{
			$this->tv['_errors'][] = $this->Application->Localizer->get_string('product_not_exists');
			return false;
		}
		row_to_vars($product_rs, $this->tv, false, 'product_');
	}

}

?>