<?
define('USER_LEVEL_GUEST', 1);
define('USER_LEVEL_USER', 10);
define('USER_LEVEL_ADMIN', 100);
define('USER_LEVEL_GLOBAL_ADMIN', 255);

class CUser
{
	const GUEST = 1;
	const MEMBER = 10;
	const ADMIN = 100;
	const GLOBAL_ADMIN = 255;

    protected $Application;
    protected $DataBase;
    protected $tv;
	protected $last_error;

	/**
	 * @var array
	 */
	public $UserData;

    function CUser(CApp &$app)
    {
        $this->Application = &$app;
        $this->tv = &$app->tv;
        $this->DataBase = &$this->Application->DataBase;
		if (!array_key_exists('UserData', $_SESSION)) $_SESSION['UserData'] = array();
		$this->UserData = &$_SESSION['UserData'];
        if (InCookie('usd_id') != '')
        {
            $bf = &$this->Application->get_module('BF');
            $this->UserData['id'] = intval(@$bf->getbyid(InCookie('usd_id')), 10);
            if (!is_numeric($this->UserData['id']))
                $this->UserData['id'] = -1;
        }

		$this->synchronize();
    }

    public function get_last_error()
    {
        return $this->last_error;
    }

    public function get_by_id($user_id)
    {
		if(!is_numeric($user_id) || $user_id < 1)
		{
			$this->last_error = $this->Application->Localizer->get_string('invalid_input');
			return false;
		}

        $rs = $this->DataBase->select_sql('user', array('id' => intval($user_id)));

        if ( $rs === false ) {
            $this->last_error = $this->Application->Localizer->get_string('database_error');
            return false;
        }

        $this->last_error = '';
        return $rs;
    }
    
    public function get_role_by_id($user_role_id)
    {
		if(!is_numeric($user_role_id) || $user_role_id < 1)
		{
			$this->last_error = $this->Application->Localizer->get_string('invalid_input');
			return false;
		}

        $rs = $this->DataBase->select_sql('user_role', array('id' => intval($user_role_id)));

        if ( $rs === false ) {
            $this->last_error = $this->Application->Localizer->get_string('database_error');
            return false;
        }

        $this->last_error = '';
        return $rs;
    }

    public function get_user_name_by_id($user_id)
    {
		if(!is_numeric($user_id) || $user_id < 1)
		{
			$this->last_error = $this->Application->Localizer->get_string('invalid_input');
			return false;
		}

        $rs = $this->DataBase->select_sql('user', array('id' => intval($user_id)));

        if ( $rs === false ) {
            $this->last_error = $this->Application->Localizer->get_string('database_error');
            return false;
        }

        $this->last_error = '';
        return $rs->get_field('user_name');
    }
    
    public function get_user_roles()
    {
    	$roles_rs = $this->DataBase->select_sql('user_role', array(), array('title' => 'ASC'));
    	return ( ($roles_rs !== false && !$roles_rs->eof()) ? $roles_rs : false );
    }

    public function get_states()
    {
    	$states_rs = $this->DataBase->select_sql('state', array(), array('title' => 'ASC'));
    	return ( ($states_rs !== false && !$states_rs->eof()) ? $states_rs : false );
    }

    public function set_logged_vars(&$tv)
    {
		if(!is_array($tv))
		{
			$this->last_error = $this->Application->Localizer->get_string('invalid_input');
			return false;
		}
		$tv['Member'] = new stdClass();
        if ($this->is_logged())
        {
            $tv['is_logged'] = $tv['Member']->is_logged = true;

            foreach($this->UserData as $k => $v)
                $tv['logged_user_'.$k] = $tv['Member']->$k = $v;

            require_once(FUNCTION_PATH . 'functions.format.php');
            $tv['logged_user_formatted_name'] = $tv['Member']->formatted_name = format_name($this->UserData['name'], '', $this->UserData['name']);
            $tv['is_global_admin'] = $tv['Member']->is_global_admin = ($this->UserData['user_role_id'] == USER_LEVEL_GLOBAL_ADMIN);
        }
        else
        {
            $tv['is_logged'] = false;
            $tv['logged_user_id'] = $tv['Member']->id = false;
        }
    }

    public function is_logged($role_id = null)
    {
		if(!is_null($role_id) && (!is_numeric($role_id) || $role_id < 1))
		{
			$this->last_error = $this->Application->Localizer->get_string('invalid_input');
			return false;
		}

        if (is_null($role_id))
            return ( isset($this->UserData['id']) && $this->UserData['id'] > 0 );
        else
            return ( isset($this->UserData['id']) && $this->UserData['id'] > 0 && $role_id === $this->UserData['user_role_id']);
    }

    public function login($l, $p, $store = true)
    {
		if(!is_string($l) || !strlen($l) || !is_string($p) || !strlen($p))
		{
			$this->last_error = $this->Application->Localizer->get_string('invalid_input');
			return false;
		}

        $this->last_error = '';

        $rs = $this->Application->DataBase->select_sql('user', array('email' => $l, 'password' => md5($p)));
        if ( (is_object($rs)) && (!$rs->eof()) )
        {
            if ((int)$rs->get_field('status') === 1)
            {
                $this->set_user_from_db($r);
                $bf = &$this->Application->get_module('BF');
                if ($store)
                        setcookie('usd_id', $bf->makeid($rs->get_field('id')), (time()+60*60*24*30), '/');
                else
                        setcookie('usd_id', $bf->makeid($rs->get_field('id')), null, '/');

                $this->set_logged_vars($this->Application->tv);
                //update last logeed time
                $this->Application->DataBase->update_sql('user', array('last_login_date' => date('Y-m-d H:i:s')), array('id' => $rs->get_field('id')));
                return true;
            }
            elseif ((int)$rs->get_field('status') === 2) {
                $loc = &$this->Application->get_module('Localizer');
                $this->last_error = $loc->get_string('login_user_not_confirmed');
            }
            else
            {
                $loc = &$this->Application->get_module('Localizer');
                $this->last_error = $loc->get_string('login_user_disabled');
            }
        }
        else
        {
            $loc = &$this->Application->get_module('Localizer');
            $this->last_error = $loc->get_string('login_no_such_user');
        }

        return false;
    }

    public function logout()
    {
        $this->UserData = array();
        setcookie('usd_id', '', time(), '/');
    }

    public function add_user($arr)
    {
		if(!is_array($arr))
		{
			$this->last_error = $this->Application->Localizer->get_string('invalid_input');
			return false;
		}

        $rs = $this->DataBase->select_custom_sql('
        SELECT
        	count(id) as cnt
        FROM %prefix%user
        WHERE
        	email = "'.$this->DataBase->internalEscape($arr['email']).'"');
        if ($rs === false)
        {
            $this->last_error = $this->Application->Localizer->get_string('database_error');
            return false;
        }
		if($rs->get_field('cnt') > 0 )
		{
			$this->last_error = $this->Application->Localizer->get_string('email_exists');
			return false;
		}

        $insert_arr = array(
            'user_role_id' => $arr['user_role_id'],
            'status' => $arr['status'],
            'email' => $arr['email'],
            'password' => md5($arr['password']),
            'name' => $arr['name'],
            'address' => $arr['address'],
            'city' => $arr['city'],
            'state_id' => (strlen($arr['state_id']) > 0) ? $arr['state_id'] : null,
            'zip' => $arr['zip'],
            'company' => $arr['company'],
            'create_date' => "now()",
         );

        if (!$id = $this->DataBase->insert_sql('user', $insert_arr))
        {
            $this->last_error = $this->Application->Localizer->get_string('internal_error');
            return false;
        }

        return $id;
    }

    public function update_user($id, $arr)
    {
		if(!is_numeric($id) || !is_array($arr))
		{
			$this->last_error = $this->Application->Localizer->get_string('invalid_input');
			return false;
		}

    	$old_user_rs = $this->get_by_id($id);
		if($old_user_rs === false)
			return false;

		if($old_user_rs->eof())
		{
			$this->last_error = $this->Application->Localizer->get_string('internal_error');
			return false;
		}

    	$rs = $this->DataBase->select_custom_sql('
        SELECT
        	count(id) cnt
        FROM
        	%prefix%user
        WHERE
        	email = "'.$this->DataBase->internalEscape($arr['email']).'" and id <> '.intval($id).'
        ');

		if($rs === false)
		{
			$this->last_error = $this->Application->Localizer->get_string('database_error');
			return false;
		}

        if ($rs->get_field('cnt') > 0)
        {
            $this->last_error = $this->Application->Localizer->get_string('email_exists');
            return false;
        }
		
        $update_arr = array(
            'email' => $arr['email'],
            'name' => $arr['name'],
            'address' => $arr['address'],
            'city' => $arr['city'],
            'state_id' => (strlen($arr['state_id']) > 0) ? $arr['state_id'] : null,
            'zip' => $arr['zip'],
            'company' => $arr['company'],
        );

        if(is_numeric($arr['user_role_id']))
			$update_arr['user_role_id'] = intval($arr['user_role_id']);

		if(strlen($arr['password']))
		$update_arr['password'] = md5($arr['password']);

        if(strlen($arr['status'])) {
			$update_arr['status'] = $arr['status'];
		}

		if (!$this->DataBase->update_sql('user', $update_arr, array('id' => intval($id))))
        {
            $this->last_error = $this->Application->Localizer->get_string('database_error');
            return false;
        }

        return $id;
    }

    public function add_user_role($arr)
    {
		if(!is_array($arr))
		{
			$this->last_error = $this->Application->Localizer->get_string('invalid_input');
			return false;
		}

        $rs = $this->DataBase->select_custom_sql('
        SELECT
        	count(id) as cnt
        FROM
        	%prefix%user_role
        WHERE
       		id = "'.intval($arr['_id']).'" OR title = "'.$this->DataBase->internalEscape($arr['title']).'"
       	');

		if($rs === false)
		{
			$this->last_error = $this->Application->Localizer->get_string('database_error');
			return false;
		}

		if ($rs->get_field('cnt') > 0)
		{
			$this->last_error = $this->Application->Localizer->get_string('id_exists');
			return false;
		}
        
        $insert_arr = array(
            'id' => intval($arr['_id']),
            'title' => $arr['title'],
            'description' => $arr['description'],
        );

		$this->DataBase->insert_sql('user_role', $insert_arr);
        return intval($arr['_id']);
    }

    public function update_user_role($id, $arr)
    {
		if(!is_numeric($id) || !is_array($arr))
		{
			$this->last_error = $this->Application->Localizer->get_string('invalid_input');
			return false;
		}

    	$old_user_role_rs = $this->get_role_by_id($id);
		if($old_user_role_rs === false)
			return false;

		if($old_user_role_rs->eof())
		{
			$this->last_error = $this->Application->Localizer->get_string('internal_error');
			return false;
		}

    	$rs = $this->DataBase->select_custom_sql('
        SELECT
        	count(id) cnt
        FROM
        	%prefix%user_role
        WHERE
        	id = "'.intval($arr['_id']).'" AND title <> "'.$this->DataBase->internalEscape($arr['title']).'"
        ');

		if($rs === false)
		{
			$this->last_error = $this->Application->Localizer->get_string('database_error');
			return false;
		}

        if ($rs->get_field('cnt') > 0)
        {
            $this->last_error = $this->Application->Localizer->get_string('id_exists');
            return false;
        }

        $update_arr = array(
            'id' => intval($arr['_id']),
            'title' => $arr['title'],
            'description' => $arr['description'],
		);

        if (!$id = $this->DataBase->update_sql('user_role', $update_arr, array('id'=> intval($id))))
        {
            $this->last_error = $this->Application->Localizer->get_string('database_error');
            return false;
        }

        return intval($arr['_id']);
    }

    public function delete_user($id)
    {
		if(!is_numeric($id))
		{
			$this->last_error = $this->Application->Localizer->get_string('invalid_input');
			return false;
		}

        if(!$this->DataBase->delete_sql('user', array('id' => intval($id))))
		{
			$this->last_error = $this->Application->Localizer->get_string('database_error');
			return false;
		}
        return true;
    }

    public function is_email_unique($email, $id = null)
    {
		if(!is_string($email) || !strlen($email) || (!is_null($id) && (!is_numeric($id) || $id < 1)))
		{
			$this->last_error = $this->Application->Localizer->get_string('invalid_input');
			return false;
		}

    	if (is_null($id))
    	{
    		$user_rs = $this->DataBase->select_sql('user', array('email' => $email));
    		return !( $user_rs !== false && !$user_rs->eof() );
    	}
    	else 
    	{
    		$user_rs = $this->DataBase->select_custom_sql("SELECT * FROM %prefix%user WHERE email = '" . $this->DataBase->internalEscape($email) . "' AND id <> '" . intval($id) . "'");
    		return !( $user_rs !== false && !$user_rs->eof() );
    	}
    }

    private function synchronize() 
    {
        if (isset($this->UserData['id']) && is_numeric($this->UserData['id']))
        {
            $rs = $this->DataBase->select_sql('user', array('id' => intval($this->UserData['id'])));
            if ( $rs !== false && !$rs->eof() )
                $this->set_user_from_db($rs);
            else
                $this->UserData = array();
        }
        else
            $this->UserData = array();
    }

    private function set_user_from_db($db_row) 
    {
        $this->UserData = array();
        foreach($db_row->Rows[0]->Fields as $k => $v)
            if (strcmp($k, 'password') !== 0)
                $this->UserData[$k] = $v;
    }

    function check_install() {
    	return ( 
			($this->DataBase->is_table('user_role')) &&
			($this->DataBase->is_table('user_session')) &&
			($this->DataBase->is_table('user_group')) &&
			($this->DataBase->is_table('user')) &&
			($this->DataBase->is_table('user_group_user_role_link'))
    	);
    }
    
    function install() {
    	$this->DataBase->custom_sql("DROP TABLE IF EXISTS `user_role`");
    	$this->DataBase->custom_sql("DROP TABLE IF EXISTS `user_session`");
    	$this->DataBase->custom_sql("DROP TABLE IF EXISTS `user_group`");
    	$this->DataBase->custom_sql("DROP TABLE IF EXISTS `user`");
    	$this->DataBase->custom_sql("DROP TABLE IF EXISTS `user_group_user_role_link`");
    	
		$this->DataBase->custom_sql("
			CREATE TABLE `user_role` (
				`id` INTEGER(11) NOT NULL,
				`title` VARCHAR(60) COLLATE utf8_general_ci DEFAULT NULL,
				`description` VARCHAR(200) COLLATE utf8_general_ci DEFAULT NULL,
			PRIMARY KEY (`id`)
			)ENGINE=InnoDB
			CHARACTER SET 'utf8' COLLATE 'utf8_general_ci'
		");
    	
		$this->DataBase->custom_sql("
			CREATE TABLE `user_session` (
				`session_id` CHAR(32) COLLATE utf8_general_ci NOT NULL DEFAULT '',
				`user_id` INTEGER(11) NOT NULL,
				`session_start` INTEGER(11) NOT NULL DEFAULT '0',
				`session_time` INTEGER(11) NOT NULL DEFAULT '0',
				`session_ip` VARCHAR(64) COLLATE utf8_general_ci DEFAULT NULL,
			PRIMARY KEY (`session_id`),
			KEY `user_id` (`user_id`),
			KEY `session_id_ip_user_id` (`session_id`, `session_ip`, `user_id`)
			)ENGINE=HEAP
			AUTO_INCREMENT=0 
			CHARACTER SET 'utf8' COLLATE 'utf8_general_ci'
		");
    	
		$this->DataBase->custom_sql("
			CREATE TABLE `user_group` (
				`id` INTEGER(11) NOT NULL AUTO_INCREMENT,
				`title` VARCHAR(255) COLLATE utf8_general_ci NOT NULL DEFAULT '',
				`description` VARCHAR(255) COLLATE utf8_general_ci DEFAULT NULL,
			PRIMARY KEY (`id`)
			)ENGINE=InnoDB
			CHARACTER SET 'utf8' COLLATE 'utf8_general_ci'
		");
    	
		$this->DataBase->custom_sql("
			CREATE TABLE `user` (
				`id` INTEGER(11) NOT NULL AUTO_INCREMENT,
				`user_role_id` INTEGER(11) NOT NULL,
				`status` INTEGER(11) NOT NULL DEFAULT '1',
				`email` VARCHAR(255) COLLATE utf8_general_ci NOT NULL DEFAULT '',
				`password` VARCHAR(255) COLLATE utf8_general_ci NOT NULL DEFAULT '',
				`name` VARCHAR(255) COLLATE utf8_general_ci DEFAULT NULL,
				`company` VARCHAR(255) COLLATE utf8_general_ci DEFAULT NULL,
				`address` VARCHAR(255) COLLATE utf8_general_ci DEFAULT NULL,
				`city` VARCHAR(255) COLLATE utf8_general_ci DEFAULT NULL,
				`state_id` INTEGER(11) DEFAULT NULL,
				`zip` VARCHAR(6) COLLATE utf8_general_ci DEFAULT NULL,
				`create_date` TIMESTAMP(0) NOT NULL DEFAULT CURRENT_TIMESTAMP,
				`last_login_date` DATETIME DEFAULT NULL,
			PRIMARY KEY (`id`),
			UNIQUE KEY `email` (`email`),
			KEY `id_level` (`user_role_id`),
			KEY `state_id` (`state_id`),
			CONSTRAINT `ibs_user_fk` FOREIGN KEY (`user_role_id`) REFERENCES `user_role` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
			CONSTRAINT `user_fk` FOREIGN KEY (`state_id`) REFERENCES `state` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
			)ENGINE=InnoDB
			CHARACTER SET 'utf8' COLLATE 'utf8_general_ci'
		");
		
		$this->DataBase->custom_sql("
			CREATE TABLE `user_group_user_role_link` (
				`user_group_id` INTEGER(11) NOT NULL,
				`user_role_id` INTEGER(11) NOT NULL,
			KEY `user_role_id` (`user_role_id`),
			KEY `user_group_id` (`user_group_id`),
			CONSTRAINT `user_group_user_role_link_fk` FOREIGN KEY (`user_role_id`) REFERENCES `user_role` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
			CONSTRAINT `user_group_user_role_link_fk1` FOREIGN KEY (`user_group_id`) REFERENCES `user_group` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
			)ENGINE=InnoDB
			CHARACTER SET 'utf8' COLLATE 'utf8_general_ci'
		");
		
		$this->DataBase->custom_sql("INSERT INTO `user_role` (`id`, `title`, `description`) VALUES (1,'Guest','Guest account'), (10,'User','User account'), (100,'Administrator','Administrator account'), (255,'Global Administrator','Global Administrator account')");
		$this->DataBase->custom_sql("INSERT INTO `user` (`id`, `user_role_id`, `status`, `email`, `password`, `name`, `company`, `address`, `city`, `state_id`, `zip`, `create_date`, `last_login_date`) VALUES (1,255,1,'admin@admin.com','21232f297a57a5a743894a0e4a801fc3','Admin',NULL,'LLA',NULL,NULL,NULL,'2009-05-10 19:09:24','2010-07-16 09:47:05')");
    }
};