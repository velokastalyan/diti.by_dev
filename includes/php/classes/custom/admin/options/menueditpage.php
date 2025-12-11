<?php
require_once(CUSTOM_CLASSES_PATH . 'admin/mastereditpage.php');

class CMenuEditPage extends CMasterEditPage
{
	/**
	 * The table name.
	 *
	 * @var array
	 */
	protected $_table = 'menu'; //такой таблицы конечно нет, просто чтобы удобнее было пользоваться функцией add_menu

	protected $_module = 'Menus';

	function CMenuEditPage(&$app, $template)
	{
		$this->IsSecure = true;
		parent::CMasterEditPage($app, $template);
	}

	function on_page_init()
	{
		parent::on_page_init();
	}

	function parse_data()
	{
		if(!parent::parse_data())
			return false;

		return true;
	}

	function bind_data()
    {
        $this->tv['_table'] = $this->_table;

        if(!$this->tv['id'])
            $this->tv['id'] = $this->id = (int)InGetPost('id');

        $lang_rs = $this->Application->Localizer->get_languages();
        if ($lang_rs !== false && !$lang_rs->eof()) {
			$this->tv['languages'] = responseToArr($lang_rs);
            CInput::set_select_data('menu_lang', $lang_rs, 'id', 'title');
        }

        if ($this->id) {
            $menu = $this->Application->get_module('Menus');
            $menu = $menu->get( $this->id );

            //Show data
            $this->tv['menu_title']	= $menu['title'];
            $this->tv['menu_path']	= $menu['path'];
            $this->tv['menu_child']	= $menu['child'];
            $this->tv['menu_lang'] = $menu['language_id'];
        }

        $this->tv['addPages'] = responseToArr($this->Application->get_module('Pages')->get_all());
        $this->tv['addCategories'] = responseToArr($this->Application->get_module('Categories')->get_all());
	}
}
?>