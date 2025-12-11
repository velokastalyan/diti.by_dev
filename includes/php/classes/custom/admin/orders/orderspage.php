<?php
require_once(CUSTOM_CLASSES_PATH . 'admin/masterpage.php');

class COrdersPage extends CMasterPage
{
	/**
	 * The table name.
	 *
	 * @var string
	 */
	protected $_table = 'order';

	function COrdersPage(&$app, $template)
	{
		$this->IsSecure = true;
		parent::CMasterPage($app, $template);
		$this->DataBase = &$this->Application->DataBase;
		$this->_filters = array(
			'name' => array(
				'title' => 'Имя клиента',
				'type' => FILTER_TEXT,
				'data' => null,
				'condition' => CONDITION_LIKE
			),
			'address' => array(
				'title' => 'Адрес клиента',
				'type' => FILTER_TEXT,
				'data' => null,
				'condition' => CONDITION_LIKE
			),
			'create_date' => array(
				'title' => $this->Application->Localizer->get_string('create_date_from'),
				'type' => FILTER_DATE
			),
		);
	}

	function on_page_init()
	{
		parent::on_page_init();
	}

	function parse_data()
	{
		if (!parent::parse_data()) return false;

		$this->bind_data();

		return true;
	}

	function bind_data()
	{
		$query = "
                    SELECT
                            s.id,s.name,s.address,s.phone,s.create_date, CASE status WHEN ".OBJECT_ACTIVE." THEN '<img src=\"".$this->tv['HTTP']."images/icons/apply.gif\">' ELSE '<img src=\"".$this->tv['HTTP']."images/icons/apply_disabled.gif\">' END AS status,
                            (
                    SELECT GROUP_CONCAT(o.product_id)
                    FROM `%prefix%order` as o
                    WHERE o.shipping_id = s.id
                    GROUP BY s.id
				) as product_id,
				(
                    SELECT GROUP_CONCAT(p.title)
                    FROM `%prefix%product` as p
                    JOIN `%prefix%order` o on (p.id = o.product_id)
                    WHERE o.shipping_id = s.id
                    GROUP BY s.id
				) as title
			FROM %prefix%shipping_info as s
            WHERE ".$this->_where;

		require_once(BASE_CLASSES_PATH . 'controls/dbnavigator.php');
		$nav = new DBNavigator($this->_table, $query, array('name','address','create_date'), 'id', 'DESC');
		$nav->title = 'Заказы';

        $header_num = $nav->add_header('title');
        $nav->headers[$header_num]->set_title('Названия товаров');
        $nav->headers[$header_num]->set_width('15%');

		$header_num = $nav->add_header('name');
		$nav->headers[$header_num]->set_title('Имя клиента');
		$nav->headers[$header_num]->set_width('15%');

		$header_num = $nav->add_header('address');
		$nav->headers[$header_num]->set_title('Адрес клиента');
		$nav->headers[$header_num]->set_width('20%');

		$header_num = $nav->add_header('phone');
		$nav->headers[$header_num]->set_title('Телефон');
		$nav->headers[$header_num]->set_width('15%');

		$header_num = $nav->add_header('status');
		$nav->headers[$header_num]->set_title('Статус');
		$nav->headers[$header_num]->set_width('5%');
		$nav->headers[$header_num]->set_align('center');

		$header_num = $nav->add_header('create_date');
		$nav->headers[$header_num]->set_title('Дата создания');
		$nav->headers[$header_num]->set_width('15%');

        $header_num = $nav->add_header('product_id');
        $nav->headers[$header_num]->set_title('Код товаров');
        $nav->headers[$header_num]->set_width('15%');

		if($nav->size > 0)
			$this->tv['remove_btn_show'] = true;
		else
			$this->tv['remove_btn_show'] = false;
	}
	
	function on_order_submit()
	{
		if (CForm::is_submit($this->_table, 'delete_selected_objects')) {
			$ids = InGetPost("{$this->_table}_res", '');
			$where="WHERE 1=1 ";
			if (strlen($ids) > 0 && $ids !== '[]') {
				$sql = 'DELETE FROM `%prefix%shipping_info` WHERE `id` IN('.$ids.')';
				if($this->Application->DataBase->select_custom_sql($sql)) {
					$where = 'WHERE `shipping_id` in ('.$ids.')';
					$sql = 'DELETE FROM `%prefix%'.$this->_table.'` '.$where;
					$this->Application->DataBase->select_custom_sql($sql);
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