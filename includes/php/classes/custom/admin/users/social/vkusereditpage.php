<?
require_once(CUSTOM_CLASSES_PATH . 'admin/mastereditpage.php');

class CVkUserEditPage extends CMasterEditPage
{
    /**
     * The table name.
     *
     * @var array
     */
    protected $_table = 'vk_user';

    protected $_module = 'VKapi';

    function CVkUserEditPage(&$app, $template)
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

	function parse_data()
	{
		if(!parent::parse_data()) return false;
		
		return true;
	}
	
	function on_vk_user_submit($action)
	{
		if(CForm::is_submit('vk_user'))
		{
			if(CValidator::validate_input())
			{
				$update_arr = array('status' => $this->tv['status']);
				if(!$this->Application->DataBase->update_sql('vk_user', $update_arr, array('id' => $this->id)))
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