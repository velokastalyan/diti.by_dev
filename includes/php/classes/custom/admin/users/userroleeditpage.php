<?
require_once(CUSTOM_CLASSES_PATH . 'admin/mastereditpage.php');

class CUserRoleEditPage extends CMasterEditPage
{
    /**
     * The table name.
     *
     * @var array
     */
    protected $_table = 'user_role';

    protected $_module = 'User';

    function CUserRoleEditPage(&$app, $template)
	{
		$this->IsSecure = true;
		parent::CMasterEditPage($app, $template);
	}

	function on_page_init()
	{
		parent::on_page_init();
		CValidator::add('_id', VRT_NUMBER, false, 1, 255);
		CValidator::add('title', VRT_TEXT, false, 0, 255);
		CValidator::add_nr('description', VRT_TEXT, false, 0, 255);

		$this->tv['_id'] = $this->id;
	}

	function parse_data()
	{
		if(!parent::parse_data())
			return false;

		return true;
	}

	function on_user_role_submit($action)
	{
		$mod = $this->Application->get_module($this->_module);
		if (CForm::is_submit($this->_table)) {
			if (CValidator::validate_input()) {
				if ($this->id) {
					if ($this->tv['id'] = $mod->{'update_'.$this->_table}($this->id, $this->tv)) {
						$this->tv['_info'] = $this->Application->Localizer->get_string('object_updated');
						$this->tv['_return_to'] =  $this->Application->Navi->getUri('parent', false);
					}
					else {
						$this->tv['_errors'] = $mod->get_last_error();
					}
				}
				else {
					if ($this->tv['id'] = $mod->{'add_'.$this->_table}($this->tv)) {
						$this->tv['_info'] = $this->Application->Localizer->get_string('object_added');
						$this->tv['_return_to'] =  $this->Application->Navi->getUri('parent', false);
					}
					else {
						$this->tv['_errors'] = $mod->get_last_error();
					}
				}
				$this->bind_data();
			}
			else {
				$this->tv['_errors'] = CValidator::get_errors();
			}
		}
		elseif (CForm::is_submit($this->_table, 'close')) {
			$this->Application->CurrentPage->internalRedirect($this->Application->Navi->getUri('parent', false));
		}
	}
}
?>