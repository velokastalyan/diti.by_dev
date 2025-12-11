<?php
require_once(CUSTOM_CLASSES_PATH . 'admin/masterpage.php');

class CCommentPage extends CMasterPage
{
	/**
	 * The table name.
	 *
	 * @var string
	 */
	protected $_table = 'comment';

	/**
	 * The columns array.
	 *
	 * @var array
	 */
	protected $_columns_arr = array('title');

	protected $_title = 'Comments';

	function CCommentPage(&$app, $template)
	{
		$this->IsSecure = true;
		parent::CMasterPage($app, $template);
		$this->DataBase = &$this->Application->DataBase;
		$Products = $this->Application->get_module('Products');
		$product_rs = $Products->get_all();
		if($product_rs == false) $product_rs = new CRecordSet();
		$product_rs->add_row(array('id' => '', 'title' => RECORDSET_FIRST_ITEM), INSERT_BEGIN);

		$rate_rs = new CRecordSet();
		$rate_rs->add_field('id');
		$rate_rs->add_field('title');
		$rate_rs->add_row(array('id' => '', 'title' => $this->Application->Localizer->get_string('recordset_first_item')), INSERT_BEGIN);
		$rate_rs->add_row(array('id' => 0, 'title' => $this->Application->Localizer->get_string('not_rated')));
		$rate_rs->add_row(array('id' => 1, 'title' => $this->Application->Localizer->get_string('one_star')));
		$rate_rs->add_row(array('id' => 2, 'title' => $this->Application->Localizer->get_string('two_stars')));
		$rate_rs->add_row(array('id' => 3, 'title' => $this->Application->Localizer->get_string('three_stars')));
		$rate_rs->add_row(array('id' => 4, 'title' => $this->Application->Localizer->get_string('four_stars')));
		$rate_rs->add_row(array('id' => 5, 'title' => $this->Application->Localizer->get_string('five_stars')));

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
			'c#title' => array(
				'title' => $this->Application->Localizer->get_string('title'),
				'type' => FILTER_TEXT,
				'data' => null,
				'condition' => CONDITION_LIKE
			),
			'c#name' => array(
				'title' => $this->Application->Localizer->get_string('name'),
				'type' => FILTER_TEXT,
				'data' => null,
				'condition' => CONDITION_LIKE
			),
			'c#rate' => array(
				'title' => $this->Application->Localizer->get_string('rate'),
				'type' => FILTER_SELECT,
				'data' => array($rate_rs, 'id', 'title'),
				'condition' => CONDITION_EQUAL
			),
			'c#status' => array(
				'title' => $this->Application->Localizer->get_string('status'),
				'type' => FILTER_SELECT,
				'data' => array($status_rs, 'id', 'title'),
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
        	c.id,
        	c.title as title,
        	c.product_id,
        	c.name,
        	IF(c.status = 'not_active', 'Не активный',
        		IF(c.status = 'active', 'Активный', '')
        	) as status,
        	IF(c.rate > 0,
        		IF(c.rate = 1, '<img src=\"".$this->tv['HTTP']."images/icons/star.gif\">',
        			IF(c.rate = 2, '<img src=\"".$this->tv['HTTP']."images/icons/star.gif\"><img src=\"".$this->tv['HTTP']."images/icons/star.gif\">',
        				IF(c.rate = 3, '<img src=\"".$this->tv['HTTP']."images/icons/star.gif\"><img src=\"".$this->tv['HTTP']."images/icons/star.gif\"><img src=\"".$this->tv['HTTP']."images/icons/star.gif\">',
        					IF(c.rate = 4, '<img src=\"".$this->tv['HTTP']."images/icons/star.gif\"><img src=\"".$this->tv['HTTP']."images/icons/star.gif\"><img src=\"".$this->tv['HTTP']."images/icons/star.gif\"><img src=\"".$this->tv['HTTP']."images/icons/star.gif\">',
        						'<img src=\"".$this->tv['HTTP']."images/icons/star.gif\"><img src=\"".$this->tv['HTTP']."images/icons/star.gif\"><img src=\"".$this->tv['HTTP']."images/icons/star.gif\"><img src=\"".$this->tv['HTTP']."images/icons/star.gif\"><img src=\"".$this->tv['HTTP']."images/icons/star.gif\">'
        					)
        				)
        			)
        		)
        		,'<img src=\"".$this->tv['HTTP']."images/icons/star_disable.png\"><img src=\"".$this->tv['HTTP']."images/icons/star_disable.png\"><img src=\"".$this->tv['HTTP']."images/icons/star_disable.png\"><img src=\"".$this->tv['HTTP']."images/icons/star_disable.png\"><img src=\"".$this->tv['HTTP']."images/icons/star_disable.png\">'
        	) as rate,
        	p.title as product
        FROM %prefix%comment c
        JOIN %prefix%product p on ((p.id = c.product_id))
        WHERE ".$this->_where;

		require_once(BASE_CLASSES_PATH . 'controls/dbnavigator.php');
		$nav = new DBNavigator($this->_table, $query, array('title'), 'id');
		$nav->title = 'Отзывы';

		$header_num = $nav->add_header('product');
		$nav->headers[$header_num]->set_title('Продукт');
		$nav->headers[$header_num]->set_width('30%');

		$header_num = $nav->add_header('title');
		$nav->headers[$header_num]->set_title('Заголовок');
		$nav->headers[$header_num]->set_width('30%');

		$header_num = $nav->add_header('name');
		$nav->headers[$header_num]->set_title('Имя');
		$nav->headers[$header_num]->set_width('25%');
		$nav->headers[$header_num]->set_align("center");

		$header_num = $nav->add_header('rate');
		$nav->headers[$header_num]->set_title('Рейтинг');
		$nav->headers[$header_num]->set_width('10%');
		$nav->headers[$header_num]->set_align("center");

		$header_num = $nav->add_header('status');
		$nav->headers[$header_num]->set_title('Статус');
		$nav->headers[$header_num]->set_width('10%');
		$nav->headers[$header_num]->set_align("center");

		if($nav->size > 0)
			$this->tv['remove_btn_show'] = true;
		else
			$this->tv['remove_btn_show'] = false;
	}
}
?>