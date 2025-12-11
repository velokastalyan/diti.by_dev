<?php
require_once(CUSTOM_CLASSES_PATH . 'admin/mastereditpage.php');

class COrderEditPage extends CMasterEditPage
{
	/**
	 * The table name.
	 *
	 * @var array
	 */
	protected $_table = 'shipping_info';

	protected $_module = 'Orders';

	function COrderEditPage(&$app, $template)
	{
		parent::CMasterEditPage($app, $template);

	}

	function on_page_init()
	{
		parent::on_page_init();

	}

	function parse_data()
	{
		parent::parse_data();
	}

	function bind_data()
	{
		parent::bind_data();
		if(!$this->id) $this->Application->CurrentPage->internalRedirect($this->tv['HTTP'].'admin/');

		$status_rs = new CRecordSet();
		$status_rs->add_row(array('id' => 0, 'title' => $this->Application->Localizer->get_string('pending_approve')));
		$status_rs->add_row(array('id' => 1, 'title' => $this->Application->Localizer->get_string('active')));
        $status_rs->add_row(array('id' => 2, 'title' => 'Заказ оплачен в Ассист'));
        $status_rs->add_row(array('id' => 3, 'title' => 'Неудачная попытка оплаты'));
		CInput::set_select_data('status', $status_rs, 'id', 'title');

        $payment_rs = new CRecordSet();
        $payment_rs->add_row(array('id' => '0', 'title' => 'не выбрано'));
        $payment_rs->add_row(array('id' => 'cash', 'title' => 'наличными курьеру'));
        $payment_rs->add_row(array('id' => 'no_cash', 'title' => 'безналичный расчет'));
        $payment_rs->add_row(array('id' => 'card', 'title' => 'пластиковой картой курьеру'));
        $payment_rs->add_row(array('id' => 'halva', 'title' => 'пластиковой картой рассрочки курьеру'));
        $payment_rs->add_row(array('id' => 'visa', 'title' => 'онлайн оплата картой Visa, MasterCard, Белкарт'));
        $payment_rs->add_row(array('id' => 'credit', 'title' => 'в кредит'));
		CInput::set_select_data('payment', $payment_rs, 'id', 'title');
                
		$this->Application->DataBase->select_custom_sql("set @N = 0;");
		$query_nav_prod = "
        SELECT
        	o.id,
        	o.count,
        	(
        		SELECT p.id FROM %prefix%product as p WHERE p.id = o.product_id LIMIT 1
        	) as p_id,
        	(
        		SELECT p.title FROM %prefix%product as p WHERE p.id = o.product_id LIMIT 1
        	) as p_title,
        	(
        		SELECT IF ( p.discount > 0, p.discount, p.price ) FROM %prefix%product as p WHERE p.id = o.product_id LIMIT 1
        	) as p_price,
        	((SELECT IF ( p.discount > 0, p.discount, p.price ) FROM %prefix%product as p WHERE p.id = o.product_id LIMIT 1) * o.count) as price,
        	(
        		SELECT CONCAT('<img src=\"".$this->tv['HTTP']."pub/products/',o.product_id,'/79x79/',i.image_filename,'\">') as img FROM %prefix%product_image as i WHERE i.product_id = o.product_id AND is_core = 1 LIMIT 1
        	) as image
        FROM `%prefix%order` as o
        WHERE
        	o.shipping_id = ".$this->id."
        	";
		require_once(BASE_CLASSES_PATH . 'controls/dbnavigator.php');
		$nav = new DBNavigator('products', $query_nav_prod, array('count', 'p_title', 'p_price'), 'id');

		$rs = $this->Application->DataBase->select_custom_sql('SELECT sum(o.count * (SELECT IF ( p.discount > 0, p.discount, p.price ) FROM %prefix%product as p WHERE p.id = o.product_id LIMIT 1)) as total_price FROM `%prefix%order` as o WHERE o.shipping_id='.$this->id);
		if (!$rs->eof() && $rs != false) {
			$this->tv['total_price'] = $rs->get_field('total_price');
		}

		$nav->title = 'Продукты';
		$nav->id_field = 'id';

		$header_num = $nav->add_header('p_id');
		$nav->headers[$header_num]->set_title('Код товара');
		$nav->headers[$header_num]->set_width('10%');
		$nav->headers[$header_num]->set_align('center');
		$nav->headers[$header_num]->set_click(false, $this->tv['HTTP'].'admin/?r=production.products.product_edit&id=');

		$header_num = $nav->add_header('p_title');
		$nav->headers[$header_num]->set_title('Название');
		$nav->headers[$header_num]->set_width('45%');
		$nav->headers[$header_num]->set_click(false, $this->tv['HTTP'].'admin/?r=production.products.product_edit&id=');

		$header_num = $nav->add_header('count');
		$nav->headers[$header_num]->set_title('Количество');
		$nav->headers[$header_num]->set_align('center');
		$nav->headers[$header_num]->set_width('15%');
		$nav->headers[$header_num]->set_click(false, $this->tv['HTTP'].'admin/?r=production.products.product_edit&id=');

		$header_num = $nav->add_header('price');
		$nav->headers[$header_num]->set_title('Цена');
		$nav->headers[$header_num]->set_align('center');
		$nav->headers[$header_num]->set_width('15%');
		$nav->headers[$header_num]->set_click(false, $this->tv['HTTP'].'admin/?r=production.products.product_edit&id=');

		$header_num = $nav->add_header('image');
		$nav->headers[$header_num]->set_title('Изображение');
		$nav->headers[$header_num]->set_align('center');
		$nav->headers[$header_num]->set_width('15%');
		$nav->headers[$header_num]->set_click(false, $this->tv['HTTP'].'admin/?r=production.products.product_edit&id=');
	}
}
?>