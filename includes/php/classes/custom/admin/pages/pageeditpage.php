<?
require_once(CUSTOM_CLASSES_PATH . 'admin/mastereditpage.php');

class CPageEditPage extends CMasterEditPage
{
    /**
     * The table name.
     *
     * @var array
     */
    protected $_table = 'page';

    protected $_module = 'Pages';

    function CPageEditPage(&$app, $template)
	{
		$this->IsSecure = true;
		parent::CMasterEditPage($app, $template);
	}

	function on_page_init()
	{
		parent::on_page_init();
		CInput::set_select_data('status', array( '' => $this->Application->Localizer->get_string('select_from_list'), OBJECT_NOT_ACTIVE => $this->Application->Localizer->get_string('not_active'), OBJECT_ACTIVE => $this->Application->Localizer->get_string('active'), OBJECT_SUSPENDED => $this->Application->Localizer->get_string('pending_publish')));
		
		CValidator::add('status', VRT_ENUMERATION, false, array(OBJECT_NOT_ACTIVE, OBJECT_ACTIVE, OBJECT_SUSPENDED));
		CValidator::add_nr('public_date', VRT_ODBCDATE);
		CValidator::add_nr('hours', VRT_NUMBER, false, 0, 24);
		CValidator::add_nr('minutes', VRT_NUMBER, false, 0, 59);
		CValidator::add_nr('seconds', VRT_NUMBER, false, 0, 59);

		if($this->lang_rs !== false)
		{
			while (!$this->lang_rs->eof())
			{
				if(InGetPost('data_'.$this->lang_rs->get_field('abbreviation').'_is_ready', false) || InGetPost('data_'.$this->lang_rs->get_field('abbreviation').'_save_data', false))
				{
					CValidator::add_nr('data_'.$this->lang_rs->get_field('abbreviation').'_save_data', VRT_NUMBER, false, 0, 1);
					CValidator::add_nr('data_'.$this->lang_rs->get_field('abbreviation').'_is_ready', VRT_NUMBER, false, 0, 1);
					CValidator::add('data_'.$this->lang_rs->get_field('abbreviation').'_title', VRT_TEXT, false, 0, 255);
					CValidator::add('data_'.$this->lang_rs->get_field('abbreviation').'_uri', VRT_TEXT, false, 0, 255);
					CValidator::add_nr('data_'.$this->lang_rs->get_field('abbreviation').'_description', VRT_TEXT);
					CValidator::add('data_'.$this->lang_rs->get_field('abbreviation').'_meta_title', VRT_TEXT, false, 0, 80);
					CValidator::add_nr('data_'.$this->lang_rs->get_field('abbreviation').'_meta_keywords', VRT_TEXT, false, 0, 160);
					CValidator::add('data_'.$this->lang_rs->get_field('abbreviation').'_meta_description', VRT_TEXT, false, 0, 160);
				}
				$this->lang_rs->next();
			}
		}
	}

	function parse_data()
	{
		if(!parent::parse_data())
			return false;
			
		return true;
	}
	
	function bind_data() {
		$this->tv['_table'] = $this->_table;

		if(!$this->tv['id'])
			$this->tv['id'] = (int)InGetPost('id');
			
		$this->lang_rs = $this->Application->Localizer->get_languages();
		if($this->lang_rs !== false)
		{
			while (!$this->lang_rs->eof())
			{
				row_to_vars($this->lang_rs, $this->tv['languages'][$this->lang_rs->current_row]);
				$this->tv['data_'.$this->lang_rs->get_field('abbreviation').'_language_id'] = $this->lang_rs->get_field('id');
				$this->lang_rs->next();
			}
			$this->lang_rs->first();
		}
		
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
				
				$translate_rs = $this->Application->DataBase->select_sql($this->_table.'_data', array($this->_table.'_id' => $this->id));
				if($this->lang_rs !== false && $translate_rs !== false && !$translate_rs->eof())
				{
					while (!$this->lang_rs->eof())
					{
						if($translate_rs->find('language_id', $this->lang_rs->get_field('id')))
						{
							row_to_vars($translate_rs, $this->tv, false, 'data_'.$this->lang_rs->get_field('abbreviation').'_');
							$translate_rs->next();
							
						}
						$this->lang_rs->next();
					}
					$this->lang_rs->first();
				}
			}
		}
	}
}
?>