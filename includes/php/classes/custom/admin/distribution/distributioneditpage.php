<?
require_once(CUSTOM_CLASSES_PATH . 'admin/mastereditpage.php');

class CDistributionEditPage extends CMasterEditPage
{
	/**
	 * The table name.
	 *
	 * @var array
	 */
	protected $_table = 'distribution';

	protected $_module = 'Distribution';

	function CDistributionEditPage(&$app, $template)
	{
		$this->IsSecure = true;
		parent::CMasterEditPage($app, $template);
	}

	function on_page_init()
	{
		parent::on_page_init();
		CValidator::add_nr('was_sent', VRT_NUMBER, false, 0, 1);
		CValidator::add('subject', VRT_TEXT, false, 0, 255);
		CValidator::add('message', VRT_TEXT);
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
				$translate_rs = $this->Application->DataBase->select_sql($this->_table, array('id' => $this->id));
				if($this->lang_rs !== false && $translate_rs !== false && !$translate_rs->eof())
				{
					while (!$this->lang_rs->eof())
					{

						$this->lang_rs->next();
					}
					$this->lang_rs->first();
				}
			}
		}
	}
}
?>