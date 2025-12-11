<?php
require_once(CUSTOM_CLASSES_PATH . 'admin/masterpage.php');

class CProductPage extends CMasterPage
{
	/**
	 * The table name.
	 *
	 * @var string
	 */
	protected $_table = 'product';

	function CProductPage(&$app, $template)
	{
		$this->IsSecure = true;
		parent::CMasterPage($app, $template);
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
			'p#id' => array(
				'title' => $this->Application->Localizer->get_string('code'),
				'type' => FILTER_TEXT,
				'data' => null,
				'condition' => CONDITION_LIKE
			),
			'c#id' => array(
				'title' => 'Родительская категория',
				'type' => FILTER_SELECT,
				'data' => array($category_rs, 'id', 'title'),
				'condition' => CONDITION_EQUAL
			),
			'p#brand_id' => array(
				'title' => $this->Application->Localizer->get_string('brand'),
				'type' => FILTER_SELECT,
				'data' => array($brands_rs, 'id', 'title'),
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
			'p#is_recommend' => array(
				'title' => 'Рекомендуемые',
				'type' => FILTER_SELECT,
				'data' => array(array('' => 'Все', 0 => 'нет', 1 => 'да')),
				'condition' => CONDITION_EQUAL

			),
		);
	}

	function on_page_init()
	{
		parent::on_page_init();
		$this->page_actions();
	}

	function page_actions() {
		if (CForm::is_submit($this->_table, 'add')) {
			$this->Application->CurrentPage->internalRedirect($this->Application->Navi->getUri('./'.$this->_table.'_edit/', false));
		}

		if (CForm::is_submit($this->_table, 'delete_selected_objects')) {
			$ids = InGetPost("{$this->_table}_res", '');
			$where="WHERE 1=1 ";
			if (strlen($ids) > 0 && $ids !== '[]') {
				require_once(FUNCTION_PATH .'functions.files.php');
				$where .= " AND id in ({$ids}) ";
				$sql = 'DELETE FROM %prefix%'.$this->_table.' ' . $where;
				$ids_arr = explode(',', $ids);
				if($this->Application->DataBase->select_custom_sql($sql)) {
					$this->tv['_info'][] = $this->Application->Localizer->get_string('objects_deleted');
					foreach ($ids_arr as $id)
						@deldir(ROOT .'pub/products_images/'. $id .'/');
				}
				else $this->tv['_errors'][] = $this->Application->Localizer->get_string('internal_error');
			}
			else $this->tv['_info'][] = $this->Application->Localizer->get_string('noitem_selected');
		}
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
        	IF(c.parent_id > 0, CONCAT(c.parent_path, ' / ', c.title), c.title) as category,
        	b.title as brand,
        	p.title as title,
        	p.brand_id as brand_id,
        	p.category_id as category_id,
        	p.price as price,
        	p.sex,
        	p.is_recommend,
        	(SELECT i.product_id FROM %prefix%product_image as i WHERE i.product_id = p.id LIMIT 1) as p_id,
        	IF(LENGTH((SELECT i.image_filename FROM %prefix%product_image as i WHERE i.product_id = p.id AND is_core = 1 LIMIT 1)) > 0, CONCAT('<img src=\"".$this->tv['HTTP']."pub/products/',p.id,'/75x75/',(SELECT i.image_filename FROM %prefix%product_image as i WHERE i.product_id = p.id AND is_core = 1 LIMIT 1),'\">'), 'Изображение отсутсвует') AS image_filename
        FROM
        	%prefix%product p
        	LEFT JOIN %prefix%category c on ((p.category_id = c.id))
        	LEFT JOIN %prefix%brand b on ((p.brand_id = b.id))
        WHERE ".$this->_where;
		$cnt_query = "SELECT count(p.id) as cnt FROM %prefix%product p JOIN %prefix%category c on ((p.category_id = c.id)) JOIN %prefix%brand b on ((p.brand_id = b.id)) WHERE ".$this->_where;

		require_once(BASE_CLASSES_PATH . 'controls/dbnavigator.php');
		$nav = new DBNavigator($this->_table, array($query, $cnt_query), array('parent_path', 'title'), 'id');
		$nav->title = 'Продукты';

		$header_num = $nav->add_header('p_id');
		$nav->headers[$header_num]->set_title('Код');
		$nav->headers[$header_num]->set_width('50px');
		$nav->headers[$header_num]->set_align("center");

		$header_num = $nav->add_header('category');
		$nav->headers[$header_num]->set_title('Родительская категория');
		$nav->headers[$header_num]->set_width('30%');

		$header_num = $nav->add_header('title');
		$nav->headers[$header_num]->set_title('Название');
		$nav->headers[$header_num]->set_width('45%');

		$header_num = $nav->add_header('brand');
		$nav->headers[$header_num]->set_title('Бренд');
		$nav->headers[$header_num]->set_width('20%');

		$header_num = $nav->add_header('price');
		$nav->headers[$header_num]->set_title('Цена');
		$nav->headers[$header_num]->set_width('120px');

		$header_num = $nav->add_header('image_filename');
		$nav->headers[$header_num]->set_title('Изображение');
		$nav->headers[$header_num]->set_width('120px');
		$nav->headers[$header_num]->set_align("center");

		if($nav->size > 0)
			$this->tv['remove_btn_show'] = true;
		else
			$this->tv['remove_btn_show'] = false;
	}
}
?>