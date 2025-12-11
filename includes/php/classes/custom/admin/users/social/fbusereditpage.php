<?
require_once(CUSTOM_CLASSES_PATH . 'admin/mastereditpage.php');

class CFbUserEditPage extends CMasterEditPage
{
    /**
     * The table name.
     *
     * @var array
     */
    protected $_table = 'fb_user';

    protected $_module = 'FBapi';

    function CFbUserEditPage(&$app, $template)
	{
		$this->IsSecure = true;
		parent::CMasterEditPage($app, $template);
		
	}

	function on_page_init()
	{
		parent::on_page_init();
		if(!$this->id)
			$this->Application->CurrentPage->internalRedirect($this->Application->Navi->getUri('parent'));
			
		CInput::set_select_data('status', $this->get_user_status_array());
	}
	
	function bind_data() {
		$this->tv['_table'] = $this->_table;

		if(!$this->tv['id'])
		{
			$this->tv['id'] = InGetPost('id');
			if(intval($this->tv['id']) == 0)
				$this->id = false;
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
			}
		}
	}

	function parse_data()
	{
		if(!parent::parse_data()) return false;
		
		return true;
	}
	
	function on_fb_user_submit($action)
	{
		if(CForm::is_submit('fb_user'))
		{
			if(CValidator::validate_input())
			{
				$update_arr = array('status' => $this->tv['status']);
				if(!$this->Application->DataBase->update_sql('fb_user', $update_arr, array('id' => $this->id)))
				{
					$this->tv['_errors'][] = $this->Application->Localizer->get_string('database_error');
					return false;
				}
				$this->tv['_info'] = $this->Application->Localizer->get_string('object_updated');
				$this->tv['_return_to'] =  $this->Application->Navi->getUri('parent', false);
			}
			else 
				$this->tv['_errors'][] = CValidator::get_errors();
		}
		elseif (CForm::is_submit($this->_table, 'close')) {
			$this->Application->CurrentPage->internalRedirect($this->Application->Navi->getUri('parent', false));
		} 
	}
}
?>