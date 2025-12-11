<?
require_once(CUSTOM_CLASSES_PATH . 'admin/mastereditpage.php');
require_once(BASE_CONTROLS_PATH.'adminuploader.php');

class CArticleEditPage extends CMasterEditPage
{
	/**
	 * The table name.
	 *
	 * @var array
	 */
	protected $_table = 'article';

	protected $_module = 'Articles';

	function CArticleEditPage(&$app, $template)
	{
		$this->IsSecure = true;
		parent::CMasterEditPage($app, $template);

	}

	function on_page_init()
	{
		parent::on_page_init();
		$status_rs = new CRecordSet();
		$status_rs->add_row(array('id' => '', 'title' => $this->Application->Localizer->get_string('recordset_first_item')));
		$status_rs->add_row(array('id' => 0, 'title' => $this->Application->Localizer->get_string('not_active')));
		$status_rs->add_row(array('id' => 1, 'title' => $this->Application->Localizer->get_string('active')));
		$status_rs->add_row(array('id' => 2, 'title' => $this->Application->Localizer->get_string('awaiting_publication')));
		CInput::set_select_data('status', $status_rs);

		$Categories = $this->Application->get_module('Articles');
		$list_rs = $Categories->get_category();
		if($list_rs == false)
		{
			$list_rs = new CRecordSet();
			$list_rs->add_field('id');
			$list_rs->add_field('title');
			$this->tv['_errors'] = $Categories->get_last_error();
		}
		$list_rs->add_row(array('id' => '', 'title' => RECORDSET_FIRST_ITEM), INSERT_BEGIN);
		CInput::set_select_data('id_cat', $list_rs);

		$status_rs->first();
		$list_rs->first();

		CValidator::add_nr('status', VRT_ENUMERATION, false, $status_rs, 'id');
		CValidator::add_nr('id_cat', VRT_ENUMERATION, false, $list_rs, 'id');
		CValidator::add('title', VRT_TEXT, false, 0, 255);
		CValidator::add('uri', VRT_TEXT, false, 0, 255);
		CValidator::add_nr('image_filename', VRT_TEXT);
		CValidator::add_nr('description', VRT_TEXT);
		CValidator::add('meta_title', VRT_TEXT, false, 0, 80);
		CValidator::add('meta_keywords', VRT_TEXT, false, 0, 160);
		CValidator::add_nr('meta_description', VRT_TEXT, false, 0, 160);

		CValidator::add_nr('public_date', VRT_ODBCDATE);
		CValidator::add_nr('hours', VRT_NUMBER, false, 0, 24);
		CValidator::add_nr('minutes', VRT_NUMBER, false, 0, 59);
		CValidator::add_nr('seconds', VRT_NUMBER, false, 0, 59);
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

		$uploader = new AdminUploader('image_filename',  array('output_dir' => 'pub/articles/'.$this->id.'/75x75/'));
		$uploader->bind();



		$this->id = $this->tv['id'];
		if ($this->id) {
			if (!$this->_object_rs = $this->is_object_exists($this->id))
			{
				$this->tv['_errors'] = $this->Application->Localizer->get_string('object_not_exist');
				$this->h_content = '';
			}
			else {
				row_to_vars($this->_object_rs, $this->tv);
				$this->tv['hours'] = date('H', strtotime($this->_object_rs->get_field('public_date')));
				$this->tv['minutes'] = date('i', strtotime($this->_object_rs->get_field('public_date')));
				$this->tv['seconds'] = date('s', strtotime($this->_object_rs->get_field('public_date')));
				$this->tv['public_date'] = date('Y-m-d', strtotime($this->_object_rs->get_field('public_date')));
			}
		}
	}

}
?>