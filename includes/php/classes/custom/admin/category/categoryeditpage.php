<?
require_once(CUSTOM_CLASSES_PATH . 'admin/mastereditpage.php');
require_once(BASE_CONTROLS_PATH.'adminuploader.php');

class CCategoryEditPage extends CMasterEditPage
{
	/**
	 * The table name.
	 *
	 * @var array
	 */
	protected $_table = 'category';

	protected $_module = 'Categories';

	function CCategoryEditPage(&$app, $template)
	{
		$this->IsSecure = true;
		parent::CMasterEditPage($app, $template);

	}

	function on_page_init()
	{
		parent::on_page_init();
		$Categories = $this->Application->get_module('Categories');
		$list_rs = $Categories->get_categories_list(2, $this->id);
		if($list_rs == false)
		{
			$list_rs = new CRecordSet();
			$list_rs->add_field('id');
			$list_rs->add_field('title');
			$this->tv['_errors'] = $Categories->get_last_error();
		}
		$list_rs->add_row(array('id' => '', 'title' => RECORDSET_FIRST_ITEM), INSERT_BEGIN);
		CInput::set_select_data('parent_id', $list_rs);
		CInput::set_select_data('type', $this->Application->list_types);

		$list_rs->first();
		CValidator::add_nr('parent_id', VRT_ENUMERATION, false, $list_rs, 'id');
		CValidator::add('title', VRT_TEXT, false, 0, 255);
        CValidator::add_nr('h1_text', VRT_TEXT, false, 0, 255);
		CValidator::add('uri', VRT_TEXT, false, 0, 255);
		CValidator::add_nr('image_filename', VRT_TEXT);
		CValidator::add_nr('description', VRT_TEXT);
		CValidator::add('position', VRT_NUMBER, false, 1);
		CValidator::add('meta_title', VRT_TEXT, false, 0, 80);
		CValidator::add('meta_keywords', VRT_TEXT, false, 0, 160);
		CValidator::add('meta_description', VRT_TEXT, false, 0, 160);
        CValidator::add('position', VRT_NUMBER, false, 1);
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

		$uploader = new AdminUploader('image_filename',  array('output_dir' => 'pub/categories/'.$this->id.'/75x75/'));
		$uploader->bind();
	}
}
?>