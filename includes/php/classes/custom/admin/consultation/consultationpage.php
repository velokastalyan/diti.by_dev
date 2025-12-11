<?php
require_once(CUSTOM_CLASSES_PATH . 'admin/masterpage.php');

class CConsultationPage extends CMasterPage
{
	/**
	 * The table name.
	 *
	 * @var string
	 */
	protected $_table = 'consultation';
	protected $_module = 'Consultation';

	/**
	 * The columns array.
	 *
	 * @var array
	 */

	function CConsultationPage(&$app, $template)
	{
		$this->IsSecure = true;
		parent::CMasterPage($app, $template);
		$this->DataBase = &$this->Application->DataBase;

		$Products = $this->Application->get_module('Products');
		$product_rs = $Products->get_all();
		if($product_rs == false) $product_rs = new CRecordSet();
		$product_rs->add_row(array('id' => '', 'title' => RECORDSET_FIRST_ITEM), INSERT_BEGIN);

		$status_rs = new CRecordSet();
		$status_rs->add_field('id');
		$status_rs->add_field('title');
		$status_rs->add_row(array('id' => '', 'title' => $this->Application->Localizer->get_string('recordset_first_item')));
		$status_rs->add_row(array('id' => 'active', 'title' => $this->Application->Localizer->get_string('Active')));
		$status_rs->add_row(array('id' => 'not_active', 'title' => $this->Application->Localizer->get_string('Not_active')));

		$this->_filters = array(
			'c#product_id' => array(
				'title' => $this->Application->Localizer->get_string('product'),
				'type' => FILTER_SELECT,
				'data' => array($product_rs, 'id', 'title'),
				'condition' => CONDITION_EQUAL
			),
			'name' => array(
				'title' => $this->Application->Localizer->get_string('Name'),
				'type' => FILTER_TEXT,
				'data' => null,
				'condition' => CONDITION_LIKE
			),
		);
	}

	function on_page_init()
	{
		parent::on_page_init();
		parent::page_actions();
	}

	function parse_data()
	{
		if (!parent::parse_data()) return false;

		$this->bind_data();

		return true;
	}

	function bind_data()
	{
		$query = "SELECT
        		c.id as id,
        		c.name as name,
        		c.phone as phone,
        		c.date_time as date_time,
        		IF(c.status = 0, '<img src=".$this->tv['HTTP']."images/icons/apply_disabled.gif />',
        		IF(c.status = 1, '<img src=".$this->tv['HTTP']."images/icons/apply.gif />', '')
        		) as status,
        		p.id as product_id,
        		p.title as product
            FROM 
            	%prefix%consultation as c
            	JOIN %prefix%product as p on ((p.id = c.product_id))
            WHERE ".$this->_where;

		require_once(BASE_CLASSES_PATH . 'controls/dbnavigator.php');
		$nav = new DBNavigator($this->_table, $query, array('title'), 'date_time', 'DESC');
		$nav->title = 'Запросы на консультацию';

		$header_num = $nav->add_header('product_id');
		$nav->headers[$header_num]->set_title('Код продукта');
		$nav->headers[$header_num]->set_width('10%');
		$nav->headers[$header_num]->set_align('center');

		$header_num = $nav->add_header('product');
		$nav->headers[$header_num]->set_title('Продукт');
		$nav->headers[$header_num]->set_width('25%');
		$nav->headers[$header_num]->set_align('center');

		$header_num = $nav->add_header('name');
		$nav->headers[$header_num]->set_title('Клиент');
		$nav->headers[$header_num]->set_width('25%');
		$nav->headers[$header_num]->set_align('center');

		$header_num = $nav->add_header('phone');
		$nav->headers[$header_num]->set_title('Телефон');
		$nav->headers[$header_num]->set_width('15%');
		$nav->headers[$header_num]->set_align('center');

		$header_num = $nav->add_header('date_time');
		$nav->headers[$header_num]->set_title('Дата');
		$nav->headers[$header_num]->set_width('15%');
		$nav->headers[$header_num]->set_align('center');

		$header_num = $nav->add_header('status');
		$nav->headers[$header_num]->set_title('Статус');
		$nav->headers[$header_num]->set_width('10%');
		$nav->headers[$header_num]->set_align('center');

		if($nav->size > 0)
			$this->tv['remove_btn_show'] = true;
		else
			$this->tv['remove_btn_show'] = false;
	}

	function on_consultation_submit($action)
	{

		$mod = $this->Application->get_module($this->_module);

		if(CForm::is_submit($this->_table, 'check'))
		{
			$ids = InGetPost("consultation_res", array());
			if (strlen($ids) > 0 && $ids !== '[]') {
				/*if (substr_count($ids, ',') > 0)
				{
					$id_arr = explode (',', $ids);
echo '11'; exit;
					//$sql = "UPDATE %prefix%consultation SET status = 1 WHERE id ='".$id_arr[0]."'";
				}
				else
					$id_arr = explode (',', $ids);
//print_arr($id_arr); exit;
					$sql = "UPDATE %prefix%consultation SET status = 1";*/


				$sql = "UPDATE %prefix%consultation SET status = 1  WHERE id IN(".$ids.")"; //Если один ID, то не работает!!!!

				if($this->Application->DataBase->select_custom_sql($sql)) {
					$this->tv['_info'][] = $this->Application->Localizer->get_string('objects_updated');
				}
				else $this->tv['_errors'][] = $this->Application->Localizer->get_string('internal_error');
			}
			else $this->tv['_info'][] = $this->Application->Localizer->get_string('noitem_selected');
		}
		elseif(CForm::is_submit($this->_table, 'add_recommend'))
		{
			$this->Application->CurrentPage->internalRedirect($this->Application->Navi->getUri('./recommend_edit/', true) . 'product_id=' . $this->id);
		}
		$this->bind_data();
	}
}
?>