<?php
require_once(CUSTOM_CLASSES_PATH . 'admin/masterpage.php');

class CRecommendEditPage extends CMasterPage
{
	/**
	 * The table name.
	 *
	 * @var string
	 */
	protected $_table = 'product_recommend';
	protected $_module = 'Products';

	/**
	 * The columns array.
	 *
	 * @var array
	 */
	protected $_columns_arr = array('title' => 'Title');

	function CRecommendEditPage(&$app, $template)
	{
		$this->IsSecure = true;
		parent::CMasterPage($app, $template);
		$this->Products = $this->Application->get_module('Products');

		if(!$this->product_id = InGet('product_id', false))
			$this->internalRedirect($this->Application->Navi->getUri('parent'));
        $this->id = InGet('product_id', false);
		$this->DataBase = &$this->Application->DataBase;

		$Categories = $this->Application->get_module('Categories');
		$category_rs = $Categories->get_categories();
		if($category_rs == false) $category_rs = new CRecordSet();
		$category_rs->add_row(array('id' => '', 'title' => RECORDSET_FIRST_ITEM), INSERT_BEGIN);

		$Brands = $this->Application->get_module('Brands');
		$brands_rs = $Brands->get_all();
		if($brands_rs == false)
			$brands_rs = new CRecordSet();

		$brands_rs->add_row(array('id' => '', 'title' => RECORDSET_FIRST_ITEM), INSERT_BEGIN);
		CInput::set_select_data('brand_id', $brands_rs);
		$brands_rs->first();

		$sex_rs = new CRecordSet();
		$sex_rs->add_field('id');
		$sex_rs->add_field('title');
		$sex_rs->add_row(array('id' => '', 'title' => $this->Application->Localizer->get_string('recordset_first_item')));
		$sex_rs->add_row(array('id' => 0, 'title' => $this->Application->Localizer->get_string('Uni')));
		$sex_rs->add_row(array('id' => 1, 'title' => $this->Application->Localizer->get_string('Men')));
		$sex_rs->add_row(array('id' => 2, 'title' => $this->Application->Localizer->get_string('Woman')));
		CInput::set_select_data('sex', $sex_rs);

		$this->_filters = array(
			'c#id' => array(
				'title' => 'Родительская категория',
				'type' => FILTER_SELECT,
				'data' => array($category_rs, 'id', 'title'),
				'condition' => CONDITION_EQUAL
			),
			'p#title' => array(
				'title' => $this->Application->Localizer->get_string('title'),
				'type' => FILTER_TEXT,
				'data' => null,
				'condition' => CONDITION_LIKE
			),
			'p#sex' => array(
				'title' => $this->Application->Localizer->get_string('sex'),
				'type' => FILTER_SELECT,
				'data' => array($sex_rs, 'id', 'title'),
				'condition' => CONDITION_EQUAL
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
		$query = "
		SELECT
			p.id as id,
			p.title as title,
			p.price as price,
			IF(c.parent_id > 0, CONCAT(c.parent_path, ' / ', c.title), c.title) as category
		FROM
			%prefix%product as p
			JOIN %prefix%category as c on ((p.category_id = c.id))
			WHERE p.id NOT IN (SELECT recommend_id FROM %prefix%product_recommend as r WHERE r.product_id = '".$this->product_id."' and r.recommend_id <> '".$this->product_id."')
		AND ".$this->_where;

		require_once(BASE_CLASSES_PATH . 'controls/dbnavigator.php');
		$nav = new DBNavigator($this->_table, $query, array('parent_path', 'title'), 'id');
		$nav->title = 'Продукты для рекомендации';

		$header_num = $nav->add_header('category');
		$nav->headers[$header_num]->set_title('Родительская категория');
		$nav->headers[$header_num]->set_width('30%');

		$header_num = $nav->add_header('title');
		$nav->headers[$header_num]->set_title('Название');
		$nav->headers[$header_num]->set_width('55%');

		$header_num = $nav->add_header('price');
		$nav->headers[$header_num]->set_title('Цена');
		$nav->headers[$header_num]->set_width('120px');
	}


	function on_product_recommend_submit($action)
	{
		$mod = $this->Application->get_module($this->_module);
		if (CForm::is_submit($this->_table)) {
			if (CValidator::validate_input()) {
				if ($this->id) {
					if ($mod->{'update_'.$this->_table}($this->id, $this->tv)) {
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
						$this->tv['_return_to'] =  $this->Application->Navi->getUri('this', true).'id='.$this->tv['id'];
					}
					else {
						$this->tv['_errors'] = $mod->get_last_error();
					}
				}
			}
			else {
				$this->tv['_errors'] = CValidator::get_errors();
			}
		}
		elseif (CForm::is_submit($this->_table, 'close')) {
			$this->Application->CurrentPage->internalRedirect($this->Application->Navi->getUri('parent', false));
		}
		elseif(CForm::is_submit($this->_table, 'add_product_recommend'))
		{
			$ids = InGetPost("product_recommend_res", array());
			if (strlen($ids) > 0 && $ids !== '[]') {
				if (substr_count($ids, ',') > 0)
				{
					$id_arr = explode (',', $ids);
					$sql = "INSERT INTO %prefix%product_recommend (product_id, recommend_id) select '".$this->product_id."', '".$id_arr[0]."' ";
					for ($i =1; $i< count($id_arr); $i++)
					{
						$sql .= " union select '".$this->product_id."', '".$id_arr[$i]."'";
					}
				}
				else
					$sql = "INSERT INTO %prefix%product_recommend (product_id, recommend_id) values ('".$this->product_id."', '".$ids."')";
				if($this->Application->DataBase->select_custom_sql($sql)) {
					$this->tv['_info'][] = $this->Application->Localizer->get_string('objects_added');
                    $this->tv['_return_to'] = $this->Application->Navi->getUri('../') . '&id='.$this->id;
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