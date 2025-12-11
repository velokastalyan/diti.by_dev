<?php
require_once(BASE_CLASSES_PATH. 'frontpage.php');

class CProductPage extends CFrontPage {

	protected $user_rs;
	protected $Products;
	protected $Comments;
	protected $Images;
	protected $c1_uri;
	protected $c2_uri;
	protected $c3_uri;
	protected $product_uri;
	protected $product_id;
    protected $Registry;

	function CProductPage(&$app, $template){
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

		$this->c1_uri = $this->tv['c1_uri'] = InUri('c1_uri', false);
		$this->c2_uri = $this->tv['c2_uri'] = InUri('c2_uri', false);
		$this->c3_uri = $this->tv['c3_uri'] = InUri('c3_uri', false);
		$this->product_uri = InUri('product_uri', false);

		if(!$this->c1_uri || !$this->c2_uri || !$this->product_uri)
			$this->page_not_found();

		$this->Categories = $this->Application->get_module('Categories');
		$this->Products = $this->Application->get_module('Products');
        $this->otlozh = InGet('otlozh');
		$this->bind_data();
	}

	function parse_data(){

		if(!parent::parse_data())
			return false;

		$this->product_comment();
		$this->product_image();

		return true;
	}


	function bind_data()
	{
        /*if (!empty($this->otlozh)){
            if (empty($_COOKIE['otlozh']))
            {
                setcookie('otlozh',$this->otlozh,time()+60*60*24*30,'/');
            } else {
                $otl_cookie = explode(',',$_COOKIE['otlozh']);
                $otlozh_found = false;
                foreach($otl_cookie as $key => $val)
                {
                    if ($val == $this->otlozh) {
                        $otlozh_found = true;
                    }
                }
                if (!$otlozh_found) {
                    setcookie('otlozh',$_COOKIE['otlozh'].','.$this->otlozh,time()+60*60*24*30,'/');
                }
            }
            if (!empty($this->c3_uri))
                $this->redirect($this->tv['HTTP'].$this->c1_uri.'/'.$this->c2_uri.'/'.$this->c3_uri.'/'.$this->product_uri.'.html');
            else
                $this->redirect($this->tv['HTTP'].$this->c1_uri.'/'.$this->c2_uri.'/'.$this->product_uri.'.html');
        }*/
		$rate_rs = new CRecordSet();
		$rate_rs->add_row(array('id' => '', 'title' => $this->Application->Localizer->get_string('recordset_first_item')));
		$rate_rs->add_row(array('id' => 1, 'title' => $this->Application->Localizer->get_string('poor')));
		$rate_rs->add_row(array('id' => 2, 'title' => $this->Application->Localizer->get_string('fair')));
		$rate_rs->add_row(array('id' => 3, 'title' => $this->Application->Localizer->get_string('good')));
		$rate_rs->add_row(array('id' => 4, 'title' => $this->Application->Localizer->get_string('very_good')));
		$rate_rs->add_row(array('id' => 5, 'title' => $this->Application->Localizer->get_string('excellent')));
		CInput::set_select_data('rate', $rate_rs);

		$this->product_detail();
		$this->product_comment();
		$this->product_image();
		$this->bind_static();
		$this->bind_menu();
        $this->add_history();
        $this->bind_brand();
		$this->bind_recommend();
		$this->bind_history();
	}



	function product_detail()
	{
		$this->Products = $this->Application->get_module('Products');

		if (!empty($this->c3_uri))
			$rs = $this->Products->get_product_by_uri($this->product_uri, array($this->c1_uri, $this->c2_uri, $this->c3_uri));
		else
			$rs = $this->Products->get_product_by_uri($this->product_uri, array($this->c1_uri, $this->c2_uri));
		$this->tv['product_found'] = false;

		$this->tv['product_arr'] = array();

		if($rs !== false && !$rs->eof())
		{
			$this->tv['product_found'] = true;

			while(!$rs->eof())
			{
				row_to_vars($rs, $this->tv['product_arr'][count($this->tv['product_arr'])+1]);
				$rs->next();
			}
		}
		$this->tv['meta_title'] = $this->tv['product_arr'][1]['meta_title'];
		$this->tv['meta_description'] = $this->tv['product_arr'][1]['meta_description'];
		$this->tv['meta_keywords'] = $this->tv['product_arr'][1]['meta_keywords'];
        $parent = $this->Products->get_parent_id($this->tv['product_arr'][1]['c_id']);
        if($parent !== false && !$parent->eof())
        {
            while(!$parent->eof())
            {
                row_to_vars($parent, $this->tv['service']);
                $parent->next();
            }
        }
        $label = $this->Products->get_service_label($this->tv['service']['parent_id']);
        if($label !== false && !$label->eof())
        {
            while(!$label->eof())
            {
                row_to_vars($label, $this->tv['label']);
                $label->next();
            }
        }
        if ($this->tv['label'])
        {
        $this->tv['product_arr'][1]['service'] = '1';
        $this->tv['product_arr'][1]['description_service'] = $this->tv['label']['description'];
        }
	}


	function product_image()
	{
		$this->Images = $this->Application->get_module('Images');
		$image_rs = $this->Images->get_product_image($this->tv['product_arr'][1]['id']);

		$this->tv['image_found'] = false;
		$this->tv['image_arr'] = array();


		if ($image_rs != false && !$image_rs->eof())
		{
			$this->tv['image_found'] = true;

			while(!$image_rs->eof())
			{
				row_to_vars($image_rs, $this->tv['image_arr'][count($this->tv['image_arr'])+1]);

				$image_rs->next();
			}
		}
	}

	function product_comment()
	{
		$this->Comments = $this->Application->get_module('Comments');

		$comm_rs = $this->Comments->get_product_comments($this->tv['product_arr'][1]['id']);

		$this->tv['comment_found'] = false;
		$this->tv['comment_arr'] = array();
		if ($comm_rs != false && !$comm_rs->eof())
		{
			$this->tv['comment_found'] = true;

			while(!$comm_rs->eof())
			{
				row_to_vars($comm_rs, $this->tv['comment_arr'][count($this->tv['comment_arr'])+1]);
				$comm_rs->next();
			}
		}
	}


	function on_add_comment_submit($action)
	{
		if(CForm::is_submit('add_comment'))
		{
            $result = $_SESSION['bf_r1'];
            unset($_SESSION['bf_r1']);
            $bf = &$this->Application->get_module('BF');
            $result = intval($bf->getbyid($result), 10);

			CValidator::add('name', VRT_TEXT, false, 0, 255);
			CValidator::add('rate', VRT_ENUMERATION, false, array(1,2,3,4,5));
			CValidator::add('description', VRT_TEXT, false, 0, 1000);
			CValidator::add('title', VRT_TEXT, false, 0, 255);

            $this->tv['comment_added'] = false;

			$this->Products = $this->Application->get_module('Products');
			$rs = $this->Products->get_product_by_uri($this->product_uri, array($this->c1_uri, $this->c2_uri, $this->c3_uri));
			//$this->product_id = $rs->get_field('id');

			if(CValidator::validate_input() && ($_SESSION['text'] != $this->tv['description']))
			{
                if(!is_numeric($result) || $result < 1 || $result !== intval($this->tv['answer']))
                {
                    $this->tv['answer'] = '';
                    $this->tv['error_answer'] = true;
                    return false;
                }
				$Comment = $this->Application->get_module('Comments');
				$this->tv['form_sent'] = true;

				if($Comment->add_comment(array( 'product_id' => $this->tv['product_arr'][1]['id'], 'name' => $this->tv['name'], 'rate' => $this->tv['rate'], 'status' => 'not_active', 'title' => $this->tv['title'], 'description' => $this->tv['description'], 'answer'=>'')))
				{
                    session_start();
                    $_SESSION['text'] = $this->tv['description'];
                    $this->tv['name'] = '';
                    $this->tv['title'] = '';
                    $this->tv['rate'] = '';
                    $this->tv['answer'] = '';
					$this->tv['description'] = '';
                    $this->tv['error_name'] = false;
                    $this->tv['error_text'] = false;
                    $this->tv['error_title'] = false;
                    $this->tv['error_answer'] = false;
				}
				else
					$this->tv['_errors'] = $Comment->get_last_error();

                $this->tv['comment_added'] = true;
			}
			else {
				$this->tv['_errors'] = CValidator::get_errors();
                if ($this->tv['name'] == '') $this->tv['error_name'] = true;
                if ($this->tv['description'] == '') $this->tv['error_text'] = true;
                if ($this->tv['title'] == '') $this->tv['error_title'] = true;
                if ($this->tv['rate'] == '') $this->tv['error_rate'] = true;
            }
            $this->bind_data();
		}
	}

    function add_history()
    {
        if (empty($_COOKIE['history_str']))
        {
                $history_arr = array(0 => $this->tv['product_arr'][1]['id']);
                setcookie('history_str', '0,'.$this->tv['product_arr'][1]['id'], time()+60*60*24, '/');
        }
        else
        {
            $history_arr = explode(',', $_COOKIE['history_str']);
            array_unshift($history_arr, $this->tv['product_arr'][1]['id']);
            $history_arr = array_unique($history_arr);
            $this->tv['history_str'] = implode(',', $history_arr);
            setcookie('history_str', $this->tv['history_str'], time()+60*60*24, '/');
        }
    }

	function bind_recommend()
	{
		$this->Recommends = $this->Application->get_module('Products');

		$recommend_rs = $this->Recommends->get_recommend_product($this->tv['product_arr'][1]['id']);

		$this->tv['recommend_found'] = false;
		$this->tv['recommend_arr'] = array();

		if ($recommend_rs != false && !$recommend_rs->eof())
		{
			$this->tv['recommend_found'] = true;
			while(!$recommend_rs->eof())
			{
				row_to_vars($recommend_rs,$this->tv['recommend_arr'][count($this->tv['recommend_arr'])+1]);
				$recommend_rs->next();
			}
			shuffle($this->tv['recommend_arr']);
		} else {
			$recommend_rs = $this->Recommends->get_rand_product($this->tv['product_arr'][1]['id'], $this->tv['product_arr'][1]['c_id']);
			if ($recommend_rs != false && !$recommend_rs->eof())
			{
				$this->tv['recommend_found'] = true;
				recordset_to_vars($recommend_rs, $this->tv['recommend_arr']);
			}
		}
	}

    function on_consultation_submit()
    {
        if ($this->tv['product_arr'][1]['title'] != '')
        {
            $phone = InPost('phone', '');

            $this->tv['error_phone'] = 0;

            if (trim($phone) == '')
            {
                $this->tv['error_phone'] = 1;
            }
            /*elseif(!is_numeric(str_replace(array('-','+',' ','(',')'),'',$phone)))
            {
                $this->tv['error_phone'] = 1;
            }*/


            //Переменный для возврата в инпуты
            $this->tv['r_phone'] = $phone;

            if (!$this->tv['error_phone'])
            {
                $to = $this->tv['sd_send_email'];
                //$to = 'deriglazov-nikita@yandex.ru';

                $subject = 'Запрос на консультацию по товару';

                $text = '
				Код товара: '.$this->tv['product_arr'][1]['id'].'
				Наименование товара: '.$this->tv['product_arr'][1]['title'].'
				Телефон клиента: '.$phone.'
				';

                mail($to, $subject, $text);

                /*************************ДОБАВЛЕНИЕ В БАЗУ*******************/
                $Consultation = $this->Application->get_module('Consultation');

                if($Consultation->add_consultation(array('product_id' => $this->tv['product_arr'][1]['id'], 'phone' => $phone, 'status' => 0)))
                {
                    $this->tv['r_phone'] = '';
                }
                else
                    $this->tv['_errors'] = $Consultation->get_last_error();

                $this->redirect( $this->tv['HTTP'] . 'thank.html');
                //header('location: '.$HTTP.'thank.html');
            }
        }
    }


};
?>