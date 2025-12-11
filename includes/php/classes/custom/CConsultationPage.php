<?php
require_once(BASE_CLASSES_PATH. 'frontpage.php');

class CConsultationPage extends CFrontPage  {

	protected $Product;

	function CConsultationPage(&$app, $template){
		parent::__construct($app, $template);
	}

	function on_page_init(){
		parent::on_page_init();
		$this->tv['product_id'] = InGet('product', '');

		$this->bind_product();
	}

	function parse_data(){

		if(!parent::parse_data())
			return false;

		return true;
	}

	function bind_data()
	{
		$this->bind_static();
		$this->bind_menu();
		$this->bind_brand();
		$this->bind_history();
	}

	function bind_product()
	{
		$this->Product = $this->Application->get_module('Products');
		$product_rs = $this->Product->get_product_by_id($this->tv['product_id']);

		$this->tv['product_found'] = false;
		$this->tv['product_arr'] = array();

		if ($product_rs != false)
		{
			$this->tv['product_found'] = true;

			while(!$product_rs->eof())
			{
				row_to_vars($product_rs, $this->tv['product_arr'][count($this->tv['product_arr'])+1]);
				$product_rs->next();
			}
		}
	}

	function on_consultation_submit()
	{
		if ($this->tv['product_arr'][1]['title'] != '')
		{
			$name = InPost('name', '');
			$phone = InPost('phone', '');
            $comment = InPost('comment', '');

			$this->tv['error_name'] = 0;
			$this->tv['error_phone'] = 0;
            $this->tv['error_comment'] = 0;

			if (trim($name) == '')
			{
				$this->tv['error_name'] = 1;
			}
			if (trim($phone) == '')
			{
				$this->tv['error_phone'] = 1;
			}
			/*elseif(!is_numeric(str_replace(array('-','+',' ','(',')'),'',$phone)))
			{
				$this->tv['error_phone'] = 1;
			}*/


			//Переменный для возврата в инпуты
			$this->tv['r_name'] = $name;
			$this->tv['r_phone'] = $phone;
            $this->tv['r_comment'] = $comment;

			if (!$this->tv['error_name'] && !$this->tv['error_phone'])
			{
				$to = 'timistkas@gmail.com';
				//$to = 'deriglazov-nikita@yandex.ru';

				$subject = 'Запрос на консультацию по товару';

				$text = '
				Код товара: '.$this->tv['product_arr'][1]['id'].'
				Наименование товара: '.$this->tv['product_arr'][1]['title'].'
				Имя клиента: '.$name.'
				Телефон клиента: '.$phone.'
				Коментарий клиента: '.$comment.'
				';

				mail($to, $subject, $text);

				/*************************ДОБАВЛЕНИЕ В БАЗУ*******************/
					$Consultation = $this->Application->get_module('Consultation');

					if($Consultation->add_consultation(array('product_id' => $this->tv['product_arr'][1]['id'], 'name' => $name, 'phone' => $phone, 'status' => 0, 'comment' => $comment)))
					{
						$this->tv['r_name'] = '';
						$this->tv['r_phone'] = '';
                        $this->tv['r_comment'] = '';
					}
					else
						$this->tv['_errors'] = $Consultation->get_last_error();

				header('location: '.$HTTP.'thank.html');
			}
		}
		$this->bind_data();
	}
};
?>