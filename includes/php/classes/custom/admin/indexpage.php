<?
require_once(CUSTOM_CLASSES_PATH . 'admin/masterpage.php');

class CIndexPage extends CAdminPage
{
	function CIndexPage(&$app, $template)
	{
		parent::CAdminPage($app, $template);
		$this->IsSecure = true;

		require_once(BASE_CONTROLS_PATH .'adminfilters.php');

		$lang_rs = $this->Application->Localizer->get_languages();
		if($lang_rs == false) $lang_rs = new CRecordSet();
		$lang_rs->add_row(array('id' => '', 'title' => $this->Application->Localizer->get_string('any')), INSERT_BEGIN);
		$this->_filters = array(
			'pcd#language_id' => array(
				'title' => $this->Application->Localizer->get_string('language'),
				'type' => FILTER_SELECT,
				'data' => array($lang_rs, 'id', 'title'),
				'condition' => CONDITION_EQUAL,
				'default' => $this->tv['language_id']
			),
			'pcd#title' => array(
				'title' => $this->Application->Localizer->get_string('title'),
				'type' => FILTER_TEXT,
				'data' => null,
				'condition' => CONDITION_LIKE
			),
			'pcd#alt' => array(
				'title' => $this->Application->Localizer->get_string('alt'),
				'type' => FILTER_TEXT,
				'data' => null,
				'condition' => CONDITION_LIKE
			),
		);
	}

	function on_page_init()
	{
		new AdminFilters($this->_filters, 'test1', 'test');
		global $_where;
		$this->_where = $_where['images'];
		parent::on_page_init();
		$this->bind_data();
	}

	function parse_data()
	{
		if (!parent::parse_data()) return false;

        return true;
	}

	function test()
	{
		require_once(BASE_CONTROLS_PATH.'adminuploader.php');
		$uploader = new AdminUploader('test', array('output_dir' => 'pub/'));
		$uploader->bind();
	}

	function on_test_submit($action)
	{
		if(CForm::is_submit('test'))
		{
			if(CValidator::validate_input())
			{
				if($filename = AdminUploader::upload('test', 'pub/'))
				{
					SetCacheVar('uploaded_test_image', $filename);
					$this->tv['_info'] = 'uploaded';
				}
				else
					$this->tv['_errors'] = 'not uploaded';
			}
			else
				$this->tv['_errors'] = CValidator::get_errors();

			$this->bind_data();
		}
	}

	function bind_data()
	{
		$this->tv['test'] = InCache('uploaded_test_image', false);
		$this->test();
	}
}
?>