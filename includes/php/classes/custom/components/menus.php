<?php
class CMenus
{
	var $Application;
	var $DataBase;
	var $Localizer;
	var $tv;
	var $last_error;

	protected $navMenu = 9; //Main id nav-menu in table registry_path

	function CMenus(&$app)
	{
		$this->Application = &$app;
		$this->tv = &$app->tv;
		$this->DataBase = &$this->Application->DataBase;

		//Nav menu is in the registry data (table: registry_path, id: 3 , parent_id : -1)
		//New menus belong to parent field(parent_id = 3), for example id = 4, parent_id = 3, path = 'test_menu', title = 'Test Menu'
		//Menu items related to the field path_id, for example id = 1, path_id = 3, path = 'item1', title = 'Item1'

		/*
		 * Example:
		 *
		 * table: registry_path:
		 * Main(for all menu): id = 3 ($navMenu), parent_id = -1 (that not show in registry-static_data), language_id = 1(any value), path = 'nav_menus', title = 'Menus'(any value)
		 * Menu1(for a particular menu): id = 4, parent_id = 3 ($navMenu), language_id = 1(for example), path = 'test_menu'(unique_value), title = 'Test_menu'(for_example)
		 *
		 * table: registry_value:
		 * id = 3, path_id = 4(Menu1), path = '0'(id parent menu item), type = 1(any value), required = 0(any value), title = 'Item 1' (for example), value = 'item 1 val'(for example)
		 */
	}

	function get_last_error()
	{
		return $this->last_error;
	}

	function get($id)
	{
		if (intval($id) < 1) {
			$this->last_error = $this->Application->Localizer->get_string('invalid_input');
			return false;
		}

		$rs = dbGet('registry_path', array('id', 'language_id', 'path', 'title'), array('id' => $id));

		if (!$rs) return false;

		$rs['child'] = dbQuery("
			SELECT
			m.id as id,
			m.path_id as path_id,
			m.path as path,
			m.title as title,
			m.value as value
		FROM %prefix%registry_value as m
		WHERE path_id = ".intval($id)."
		ORDER BY path
		", false);

		return $rs;
	}

	function get_by_path($path)
	{
		if (strlen($path) < 1 || !is_string($path)) {
			$this->last_error = $this->Application->Localizer->get_string('invalid_input');
			return false;
		}

		$rs = dbGet('registry_path', array('id', 'language_id', 'path', 'title'), array('path' => addslashes($path)));

		if (!$rs) return false;

		$rs['child'] = dbQuery("
			SELECT
			m.id as id,
			m.path_id as path_id,
			m.path as path,
			m.title as title,
			m.value as value
		FROM %prefix%registry_value as m
		WHERE path_id = ".intval($rs['id'])."
		", false);

		return $rs;
	}

	function get_items_nav_menu($menu, $lang_id = null)
	{
		if ( (!is_numeric($menu) && !is_string($menu) && (!is_null($lang_id) && !is_numeric($lang_id))) && ((is_numeric($menu) && $menu < 0) || (is_string($menu) && strlen($menu) < 1)) ) {
			$this->last_error = $this->Application->Localizer->get_string('invalid_input');
			return false;
		}

		if (is_null($lang_id)) {
			$lang_id = intval($this->tv['language_id']);
		}

		if (is_numeric($menu)) {
			$cond = 'id='.$menu;
		} else {
			$cond = 'path="'.addslashes($menu).'"';
			$cond .= ' AND language_id='.$lang_id;
		}

		$rs = dbGet('registry_path', array('id', 'language_id', 'path', 'title'), $cond);
		if (!$rs) return false;

		$this->tv['rsChild'] = dbQuery("
			SELECT
			m.id as id,
			m.path_id as path_id,
			m.path as path,
			m.title as title,
			m.value as value
		FROM %prefix%registry_value as m
		WHERE path_id = ".intval($rs['id'])."
		");

		if ($this->tv['rsChild']) {
			$i = 0;
			$sizeOf = sizeof($this->tv['rsChild']);
			for(; $i < $sizeOf; $i++) {
				$this->restructure_items($this->tv['rsChild'][$i], $this->tv['rsChild']);
				if ($this->tv['rsChild'][$i]['path'] != 0) {
					unset($this->tv['rsChild'][$i]);
				}
			}
		}
		$rs['child'] = $this->tv['rsChild'];

		return $rs;
	}

	private function restructure_items($item, &$rsChild)
	{
		if ($item['path'] != 0) {

			$sizeOf = sizeof($rsChild);
			for($i = 0; $i < $sizeOf; $i++) {
				if ($rsChild[$i]['id'] == $item['path']) {
					$rsChild[$i]['child'][] = $item;
					continue;
				}
			}

			for($i = 0; $i < $sizeOf; $i++) {
				if (!empty($rsChild[$i]['child'])) {
					$this->restructure_items($item, $rsChild[$i]['child']);
				}
			}
		}
	}

	function get_nav_menu($menu, $lang_id = null)
	{
		$rs = $this->get_items_nav_menu($menu, $lang_id);

		if ($rs != false && !empty($rs['child'])) {
			$return = '<ul id="nav-menu-'.$rs['id'].'" class="nav-menu '.$rs['path'].'">';
			$return .= $this->print_item($rs['child']);
			$return .= '</ul>';
			return $return;
		}
		return $rs;
	}

	private function print_item($item)
	{
		$return = '';
		if (!empty($item) && is_array($item)) {
			$curr_url = substr($this->tv['HTTP'], 0, (strlen($this->tv['HTTP'])-1)) . $_SERVER['REQUEST_URI'];
			foreach($item as $key => $val) {
				$return .= '<li class="item-menus'.(($item[$key]['value'] == $curr_url) ? ' active' : null).'" id="menu-item-'.$item[$key]['id'].'"><a href="'.$item[$key]['value'].'">'.$item[$key]['title'].'</a>'. (( is_array($item[$key]['child']) ) ? '<ul>'.$this->print_item($item[$key]['child']).'</ul>' : null) .'</li>';
			}
		}
		return $return;
	}

    function add_menu($arr)
    {
        $insert_arr_menu = array(
            'parent_id' => $this->navMenu,
            'language_id' => intval($arr['menu_lang']),
            'path' => $arr['menu_path'],
            'title' => $arr['menu_title'],
        );

        if (!$id = dbInsert('registry_path', $insert_arr_menu)) {
            $this->last_error = $this->Application->Localizer->get_string('database_error');
            return false;
        }

        foreach($arr['item-menu-data'] as $key =>  &$item) {
            $insert_arr_items = array(
                'path_id' => $id,
                'path' => (($item['parent']) ? $arr['item-menu-data'][$item['parent']]['id'] : 0),
                'type' => 1,
                'required' => 0,
                'title' => $item['label'],
                'value' => $item['url'],
            );

            if (!$item_id = dbInsert('registry_value', $insert_arr_items)) {
                $this->last_error = $this->Application->Localizer->get_string('database_error');
                return false;
            }
            $arr['item-menu-data'][$key]['id'] = $item_id;
        }

        return $id;
    }

    function update_menu($id, $arr)
    {
        $update_arr_menu = array(
            'parent_id' => $this->navMenu,
            'language_id' => intval($arr['menu_lang']),
            'path' => $arr['menu_path'],
            'title' => $arr['menu_title'],
        );

        if (!dbUpdate('registry_path', $update_arr_menu, array('id' => $id))) {
            $this->last_error = $this->Application->Localizer->get_string('database_error');
            return false;
        }

        dbDelete('registry_value', array('path_id' => $id));


        foreach($arr['item-menu-data'] as $key =>  &$item) {
            $insert_arr_items = array(
                'path_id' => $id,
                'path' => (($item['parent']) ? $arr['item-menu-data'][$item['parent']]['id'] : 0),
                'type' => 1,
                'required' => 0,
                'title' => $item['label'],
                'value' => $item['url'],
            );

            if (!$item_id = dbInsert('registry_value', $insert_arr_items)) {
                $this->last_error = $this->Application->Localizer->get_string('database_error');
                return false;
            }
            $arr['item-menu-data'][$key]['id'] = $item_id;
        }

        return $id;
    }
}