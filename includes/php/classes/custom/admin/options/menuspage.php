<?php
require_once(CUSTOM_CLASSES_PATH . 'admin/masterpage.php');

class CMenusPage extends CMasterPage
{
	/**
	 * The table name.
	 *
	 * @var string
	 */
	protected $_table = 'menus'; //Конечно такой таблицы нет, нужна лишь для инициализации страницы редактирования
    protected $navMenu = 9; //ID в registry_path для навигационного меню

	function CMenusPage(&$app, $template)
	{
		parent::CMasterPage($app, $template);
		$this->DataBase = &$this->Application->DataBase;
	}

	function on_page_init()
	{
		parent::on_page_init();

        /*if (!empty($_POST['param2']) && $_POST['param2'] == 'delete_selected_objects')
            $this->menu_delete();*/
	}

	function parse_data()
	{
		if (!parent::parse_data()) return false;
        parent::page_actions();

		$this->bind_data();

		return true;
	}

	function bind_data()
	{
		$query = "
			SELECT
				m.id,
				m.path,
				m.title,
				(
					SELECT count(r.id) as cnt FROM %prefix%registry_value as r WHERE r.path_id = m.id
				) as items_count
			FROM %prefix%registry_path as m
			WHERE m.parent_id = ".$this->navMenu." AND ". $this->_where ."
		"; //m.parent_id - ID в registry_path для навигационного меню

		require_once(BASE_CLASSES_PATH . 'controls/dbnavigator.php');
		$nav = new DBNavigator($this->_table, $query, array('title','status','public_date','create_date'), 'id');
		$nav->title = $this->Application->Localizer->get_string('nav_menus');

		$header_num = $nav->add_header('title');
		$nav->headers[$header_num]->set_title($this->Application->Localizer->get_string('title'));
		$nav->headers[$header_num]->set_width('60%');

		$header_num = $nav->add_header('lang_title');
		$nav->headers[$header_num]->set_title($this->Application->Localizer->get_string('language'));
		$nav->headers[$header_num]->set_width('30%');

		$header_num = $nav->add_header('items_count');
		$nav->headers[$header_num]->set_title($this->Application->Localizer->get_string('item_count'));
		$nav->headers[$header_num]->set_width('10%');

		if($nav->size > 0)
			$this->tv['remove_btn_show'] = true;
		else
			$this->tv['remove_btn_show'] = false;
	}

    function on_menus_submit()
    {
        if (CForm::is_submit($this->_table, 'add')) {
            $this->Application->CurrentPage->internalRedirect($this->Application->Navi->getUri('./'.$this->_table.'_edit/', false));
        }

        if (CForm::is_submit($this->_table, 'delete_selected_objects')) {
            $ids = InGetPost("{$this->_table}_res", '');
            $where="WHERE 1=1 ";
            if (strlen($ids) > 0 && $ids !== '[]') {
                $where .= " AND id in ({$ids}) ";
                $sql = 'DELETE FROM %prefix%registry_path ' . $where;
                if($this->Application->DataBase->select_custom_sql($sql)) {
                    $this->tv['_info'][] = $this->Application->Localizer->get_string('objects_deleted');
                }
                else $this->tv['_errors'][] = $this->Application->Localizer->get_string('internal_error');
            }
            else $this->tv['_info'][] = $this->Application->Localizer->get_string('noitem_selected');
        }
        $this->bind_data();
    }
}
?>