<?php
require_once(BASE_CLASSES_PATH . 'frontpage.php');

class CPagesPage extends CFrontPage
{
	protected $Registry;

	protected $static_page;


	function CPagesPage(&$app, $template){
		parent::__construct($app, $template);
	}

	function on_page_init(){
		parent::on_page_init();
		$this->Registry = $this->Application->get_module('Registry');
        $rs = $this->Registry->get_pathes_values('static_data');
        if($rs !== false && !$rs->eof())
        {
            while(!$rs->eof())
            {
                $this->tv['sd_'.$rs->get_field('value_path')] = $rs->get_field('value');
                $rs->next();
            }
        }

		$this->static_page = InUri('static_page', false);

		$this->static_page = $this->tv['static_page'] = InUri('static_page', false);

		if(!$this->static_page)
			$this->page_not_found();
        $this->bind_static_page();
		
		if ($this->static_page == 'shopping-cart') {
			$remove = inGet('remove', false);
			if ($remove) {
				unset($_SESSION['cart'][$remove]);
				$this->redirect( $this->tv['HTTP'] . 'shopping-cart.html');
			} elseif ( inGet('payment', false) == 'visa' && inGet( 'id', false ) ) {
                $order_info = $this->Application->DataBase->select_custom_sql( "SELECT * from `shipping_info` WHERE id=".inGet( 'id', false ) );

                $rs = $this->Application->DataBase->select_custom_sql('SELECT sum(o.count * (SELECT IF ( p.discount > 0, p.discount, p.price ) FROM %prefix%product as p WHERE p.id = o.product_id LIMIT 1)) as total_price FROM `%prefix%order` as o WHERE o.shipping_id='.inGet( 'id', false ));
                if (!$rs->eof() && $rs != false) {
                    $this->tv['total_price'] = $rs->get_field('total_price');
                }
                if (!$order_info->eof() && $order_info != false) {
                    $this->tv['name'] = $order_info->get_field('name');
                    $this->tv['phone'] = $order_info->get_field('phone');
                    $this->tv['address'] = $order_info->get_field('address');
                    $this->tv['comment'] = $order_info->get_field('comment');
                    $this->tv['create_date'] = $order_info->get_field('create_date');
                    $this->tv['order_id'] = $order_info->get_field('id');

                }
            } else {
				$status = InGet('status', false);
				if ($status == 'ok') {
					$this->tv['status_ok'] = true;
				}
			}
		} elseif ( $this->static_page == 'success' ) {
            if ( $order = inGet('ordernumber', false) ) {
                $this->Application->DataBase->update_sql( 'shipping_info', array( 'status' => 2 ), array( 'id' => $order ) );
            }
        } elseif ( $this->static_page == 'fail' ) {
            if ( $order = inGet('ordernumber', false) ) {
                $this->Application->DataBase->update_sql( 'shipping_info', array( 'status' => 3 ), array( 'id' => $order ) );
            }
        }


        $this->tv['number_capthca1'] = rand(1,9);
        $this->tv['number_capthca2'] = rand(1,9);



        session_start();

        if (isset($_SESSION['sum_captcha']))
            $this->old_captcha = $_SESSION['sum_captcha'];

        $_SESSION['sum_captcha'] = $this->tv['number_capthca1'] + $this->tv['number_capthca2'];
	}

	function parse_data(){

		if(!parent::parse_data())
			return false;


		return true;
	}

	function bind_static_page()
	{
		$this->Static_page = $this->Application->get_module('Pages');
		$page_rs = $this->Static_page->get_page_by_uri($this->static_page);

		$this->tv['page_found'] = false;
		$this->tv['page_arr'] = array();

		if ($page_rs !=false && !$page_rs->eof())
		{
			$this->tv['page_found'] = true;

			row_to_vars($page_rs, $this->tv['page_arr'][count($this->tv['page_arr'])+1]);
		}
		$this->tv['meta_title'] = $this->tv['page_arr'][1]['meta_title'];
		$this->tv['meta_description'] = $this->tv['page_arr'][1]['meta_description'];
		$this->tv['meta_keywords'] = $this->tv['page_arr'][1]['meta_keywords'];

        if ($this->static_page == 'otlozhennoe') {
            $this->tv['otlozh_arr'] = array();
            $this->tv['otlozh_found'] = false;

            if (!empty($_COOKIE['otlozh'])) {
                $otlozh_arr = explode(',', $_COOKIE['otlozh']);
                $this->Products = $this->Application->get_module('Products');

                $temp_otloz = array();
                foreach($otlozh_arr as $key => $value) {
                    $rs = $this->Products->get_product_by_id($value);
                    if ($rs != false && !$rs->eof()) {
                        $this->tv['otlozh_found'] = true;
                        recordset_to_vars($rs, $temp_otloz);
                    }
                    $this->tv['otlozh_arr'][] = $temp_otloz[0];
                }
            }
        }
		elseif ($this->static_page == 'shopping-cart') {
			$this->tv['cart_arr'] = array();
			$Products = $this->Application->get_module('Products');

			$rs = $Products->get_products_in_cart();

			if ($rs != false && !$rs->eof()) {
				recordset_to_vars($rs, $this->tv['cart_arr']);
			}
		}
	}


    function on_distribution_submit()
    {
        $email = InPost('email');
        $captcha = InPost('captcha');

        $this->tv['error_email'] = false;
        $this->tv['error_captcha'] = false;
        $this->tv['subscriber_good'] = false;

        if (trim($email) != '')
        {
            if (!preg_match("|^[-0-9a-z_\.]+@[-0-9a-z_^\.]+\.[a-z]{2,6}$|i", $email))
            {
                $this->tv['error_email'] = true;
            }
        }
        else $this->tv['error_email'] = true;
        if (trim($captcha) != '')
        {
            if (!is_numeric($captcha))
                $this->tv['error_captcha'] = true;
            elseif ($captcha != $this->old_captcha)
                $this->tv['error_captcha'] = true;
        }
        else $this->tv['error_captcha'] = true;

        $this->tv['user_email'] = $email;

        $Distribution = $this->Application->get_module('Distribution');

        if (($this->tv['error_email'] == false) && ($this->tv['error_captcha'] == false))
            if($Distribution->add_subscriber(array('email' => $email, )))
            {
                $this->tv['user_email'] = '';
                $this->tv['subscriber_good'] = true;
            }
            else
                $this->tv['_errors'] = $Distribution->get_last_error();
        $this->bind_static();
        $this->bind_brand();
        $this->bind_history();
        $this->bind_menu();
    }
	
	function on_order_submit()
	{
		if (!empty($_POST['productCount'])) {
			foreach($_POST['productCount'] as $key => $val) {
				if (is_numeric($val)) {
					if ($val > 0) {
						$_SESSION['cart'][$key] = $val;
					} else {
						unset($_SESSION['cart'][$key]);
					}
				}
			}
		}

		$this->bind_static();
		$this->bind_brand();
		$this->bind_history();
		$this->bind_menu();
	}

	function on_shipping_info_submit()
	{
		$this->tv['name'] = $name = addslashes( htmlspecialchars( trim( $_POST['name'] ) ));
		$this->tv['address'] = $address = addslashes( htmlspecialchars( trim( $_POST['address'] ) ) );
		$this->tv['phone'] = $phone = addslashes( htmlspecialchars( trim( $_POST['phone'] ) ) );
		$this->tv['comment'] = $comment = addslashes( htmlspecialchars( trim( $_POST['comment'] ) ) );
        $this->tv['payment'] = $payment = addslashes( htmlspecialchars( trim( $_POST['payment'] ) ) );

		$this->tv['errors'] = false;
		if (empty($name)) {
			$this->tv['errors'][] = 'name';
		}
		if (empty($address)) {
			$this->tv['errors'][] = 'address';
		}
		if (empty($phone)) {
			$this->tv['errors'][] = 'phone';
		}
                if (empty($payment)) {
			$this->tv['errors'][] = 'payment';
		}

		if (!$this->tv['errors'] && !empty($_SESSION['cart'])) {
			$insert_arr = array(
				'name' => $name,
				'address' => $address,
				'phone' => $phone,
				'comment' => $comment,
                'payment' => $payment,
				'create_date' => date('Y-m-d H:i:s'),
				'status' => 0
			);
			if (!$shipping_id = $this->Application->DataBase->insert_sql('shipping_info', $insert_arr)) {
				return false;
			}
			foreach($_SESSION['cart'] as $key => $val) {
				$insert_arr = array(
					'shipping_id' => $shipping_id,
					'product_id' => $key,
					'count' => $val,
				);
				//$this->Application->DataBase->insert_sql('order', $insert_arr);
				$this->Application->DataBase->select_custom_sql("INSERT INTO `order` (`shipping_id`, `product_id`, `count`) VALUES (".$shipping_id.", ".$key.", ".$val.")");
			}

			
			$to = $this->tv['sd_send_email'];
			$subject = 'Новый заказ';
			$message = 'На сайте DITI.by новый заказ! Посмотреть можно тут: '. $this->tv['HTTP'].'admin/?r=orders.order_edit&id='.$shipping_id;
			mail($to, $subject, $message);
			
			unset($_SESSION['cart']);

            if ( $payment == 'visa' ) {
                $this->redirect( $this->tv['HTTP'] . 'shopping-cart.html?payment=visa&id='.$shipping_id );
            } else {
                $this->redirect( $this->tv['HTTP'] . 'shopping-cart.html?status=ok' );
            }
		}

		$this->bind_static();
		$this->bind_brand();
		$this->bind_history();
		$this->bind_menu();
	}

}
?>