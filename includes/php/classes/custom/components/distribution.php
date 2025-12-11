<?
class CDistribution
{
	var $Application;
	var $DataBase;
	var $tv;
	var $last_error;

	function CDistribution(CApp &$app)
	{
		$this->Application = &$app;
		$this->tv = &$app->tv;
		$this->DataBase = &$this->Application->DataBase;
	}

	function get_last_error()
	{
		return $this->last_error;
	}

	function get_subscribers($return_array = true)
	{
		$rs = $this->DataBase->select_sql('subscriber');
		if($return_array)
		{
			$subscribers = array();
			while(!$rs->eof())
			{
				$subscribers[] = $rs->get_field('email');
				$rs->next();
			}

			return join(';',$subscribers);
		}
		else return ( ($rs !== false && !$rs->eof()) ? $rs : false );
	}

	function add_distribution($arr)
	{
		if(!is_array($arr))
		{
			$this->last_error = $this->Application->Localizer->get_string('invalid_input');
			return false;
		}
					$insert_arr = array(
						'subject' => $arr['subject'],
						'message' => $arr['message'],
						'send' => $arr['send'],
						'dispatch_date' => date("Y-m-d G:i:s")
					);

					if(!$id = $this->DataBase->insert_sql('distribution', $insert_arr))
					{
						$this->last_error = $this->Application->Localizer->get_string('database_error');
						return false;
					}



            if(intval($arr['send']))
            {
                $Registry = $this->Application->get_module('Registry');
                $et_rs = $Registry->get_pathes_values('distribution_email');
                if($et_rs !== false)
                {
                    $sender = $template = $subject = '';
                    if($et_rs->find('value_path', 'sender_email'))
                        $sender = $et_rs->get_field('value');

                    if($et_rs->find('value_path', 'template'))
                        $template = $et_rs->get_field('value');

                    $receivers = $this->get_subscribers();
                    $subject = 'Новости DITI.by: '.$arr['subject'];
                    $message = $arr['message'];
                    $template = CTemplate::parse_string(convert_template($template), array('message' => $message));


                    if(strlen($receivers))
                    {
                        //CUtils::smtp_mail($sender, $receivers, $subject, convert_template($template));
                        $r = CUtils::send_email($sender, $receivers, $subject, convert_template($template));
                    }
                }
            }

		return $id;
	}

	function update_distribution($id, $arr)
	{
		if(!is_array($arr) || !is_numeric($id) || !intval($id))
		{
			$this->last_error = $this->Application->Localizer->get_string('invalid_input');
			return false;
		}

            $update_arr = array(
                    'subject' => $arr['subject'],
                    'message' => $arr['message'],
                    'send' => $arr['send'],
                    'dispatch_date' => date("Y-m-d G:i:s")
                );

            if(!$this->DataBase->update_sql('distribution', $update_arr, array('id' => $id)))
            {
                $this->last_error = $this->Application->Localizer->get_string('database_error');
                return false;
            }

        if(intval($arr['send']))
        {
            $Registry = $this->Application->get_module('Registry');
            $et_rs = $Registry->get_pathes_values('distribution_email');
            if($et_rs !== false)
            {
                $sender = $template = $subject = '';
                if($et_rs->find('value_path', 'sender_email'))
                    $sender = $et_rs->get_field('value');

                if($et_rs->find('value_path', 'template'))
                    $template = $et_rs->get_field('value');

                $receivers = $this->get_subscribers();
                $subject = $arr['subject'];
                $message = $arr['message'];
                $template = CTemplate::parse_string(convert_template($template), array('message' => $message));


                if(strlen($receivers))
                {
                    //CUtils::smtp_mail($sender, $receivers, $subject, convert_template($template));
                    $r = CUtils::send_email($sender, $receivers, $subject, convert_template($template));
                }
            }
        }

		return $id;
	}

	function add_subscriber($arr)
	{
		if(!is_array($arr))
		{
			$this->last_error = $this->Application->Localizer->get_string('invalid_input');
			return false;
		}

		$double_rs = $this->DataBase->select_sql('subscriber', array('email' => $this->DataBase->internalEscape($arr['email'])));
		if($double_rs == false)
		{
			$this->last_error = $this->Application->Localizer->get_string('database_error');
			return false;
		}
		elseif(!$double_rs->eof())
		{
			$this->last_error = $this->Application->Localizer->get_string('subscriber_exists');
			return false;
		}

		$insert_arr = array(
			'email' => $arr['email']
		);

		if(!$id = $this->DataBase->insert_sql('subscriber', $insert_arr))
		{
			$this->last_error = $this->Application->Localizer->get_string('database_error');
			return false;
		}


		/*if($this->Application->User->UserData['user_role_id'] < 255)
		{
			$Registry = $this->Application->get_module('Registry');
			$et_rs = $Registry->get_pathes_values('new_subscriber_email');
			if($et_rs !== false)
			{
				$receiver = $sender = $template = $subject = '';
				if($et_rs->find('value_path', 'receiver_email'))
					$receiver = $et_rs->get_field('value');

				if($et_rs->find('value_path', 'sender_email'))
					$sender = $et_rs->get_field('value');

				if($et_rs->find('value_path', 'subject'))
					$subject = $et_rs->get_field('value');

				if($et_rs->find('value_path', 'template'))
					$template = $et_rs->get_field('value');

				//CUtils::smtp_mail($sender, $receiver, $subject, convert_template($template), array('subscriber_email' => $arr['email']));
				CUtils::send_email($sender, $receiver, $subject, convert_template($template), array('subscriber_email' => $arr['email']));
			}
		}*/

		return $id;
	}

	function update_subscriber($id, $arr)
	{
		if(!is_array($arr) || !is_numeric($id) || $id < 1)
		{
			$this->last_error = $this->Application->Localizer->get_string('invalid_input');
			return false;
		}

		$double_rs = $this->DataBase->select_custom_sql("
		SELECT
			count(id) as cnt
		FROM
			%prefix%subscriber
		WHERE
			email = '".$this->DataBase->internalEscape($arr['email'])."' AND id <> '".intval($id)."'
		");
		if($double_rs == false)
		{
			$this->last_error = $this->Application->Localizer->get_string('database_error');
			return false;
		}
		elseif($double_rs->get_field('cnt') > 0)
		{
			$this->last_error = $this->Application->Localizer->get_string('subscriber_exists');
			return false;
		}

		$update_arr = array(
			'email' => $arr['email']
		);

		if(!$this->DataBase->update_sql('subscriber', $update_arr, array('id' => intval($id))))
		{
			$this->last_error = $this->Application->Localizer->get_string('database_error');
			return false;
		}

		return $id;
	}
}
?>