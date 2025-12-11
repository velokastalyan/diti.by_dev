<?php
require_once(CUSTOM_CLASSES_PATH . 'admin/masterpage.php');

class CVkUsersPage extends CMasterPage
{
    /**
     * The table name.
     *
     * @var array
     */
    protected $_table = 'vk_user';
    /**
     * The table name.
     *
     * @var array
     */
    protected $_filters = array();

	function CVkUsersPage(&$app, $template)
	{
		$this->IsSecure = true;
		parent::CMasterPage($app, $template);
		$this->DataBase = &$this->Application->DataBase;
		$roles_rs = $this->Application->User->get_user_roles();
		$roles_rs->add_row(array('id' => '', 'title' => $this->Application->Localizer->get_string('any')), INSERT_BEGIN);
	    $this->_filters = array(
	    	'm#last_name' => array(
	    		'title' => $this->Application->Localizer->get_string('last_name'),	
	    		'type' => FILTER_TEXT,
	    		'data' => null,
	    		'condition' => CONDITION_LIKE
	    	),
	    	'm#first_name' => array(
	    		'title' => $this->Application->Localizer->get_string('first_name'),	
	    		'type' => FILTER_TEXT,
	    		'data' => null,
	    		'condition' => CONDITION_LIKE
	    	),
	    	'm#user_role_id' => array(
	    		'title' => $this->Application->Localizer->get_string('user_role_id'),	
	    		'type' => FILTER_SELECT,
	    		'data' => array($roles_rs, 'id', 'title'),
	    		'condition' => CONDITION_EQUAL
	    	),
	    	'm#create_date' => array(
	    		'title' => $this->Application->Localizer->get_string('create_date_from'),	
	    		'type' => FILTER_DATE,
	    	)
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
        $query = "SELECT m.id as id,
            CASE m.status WHEN 1 THEN '<img src=\"".$this->tv['HTTP']."images/icons/user.gif\">' ELSE '<img src=\"".$this->tv['HTTP']."images/icons/user_.gif\">' END AS status,
            CONCAT(m.last_name, ' ', m.first_name) as name,
            ml.title AS user_role,
            DATE_FORMAT(m.create_date, '%b %d, %Y %h:%i %p') AS create_date_formatted
            FROM %prefix%vk_user AS m LEFT JOIN %prefix%user_role ml ON (m.user_role_id = ml.id)
            WHERE ".$this->_where."";
		
        require_once(BASE_CLASSES_PATH . 'controls/dbnavigator.php');
        $nav = new DBNavigator('vk_user', $query, array('status', 'company', 'name', 'email'), 'id');
        $nav->title = 'Users List';
        
        $header_num = $nav->add_header('status');
        $nav->headers[$header_num]->set_title($this->Application->Localizer->get_string('status'));
        $nav->headers[$header_num]->set_align( "center" );
        
        $header_num = $nav->add_header('name');
        $nav->headers[$header_num]->set_title($this->Application->Localizer->get_string('name'));
        $nav->headers[$header_num]->set_width( "70%" );
        
        $header_num = $nav->add_header('user_role');
        $nav->headers[$header_num]->set_title($this->Application->Localizer->get_string('user_role_id'));
        $nav->headers[$header_num]->set_width( "12%" );
        $nav->headers[$header_num]->set_align( "center" );
        
        $header_num = $nav->add_header('create_date_formatted');
        $nav->headers[$header_num]->set_title($this->Application->Localizer->get_string('create_date'));
        $nav->headers[$header_num]->set_width( "12%" );
        $nav->headers[$header_num]->set_align( "center" );
        
        if($nav->size > 0)
        	$this->tv['remove_btn_show'] = true;
        else
        	$this->tv['remove_btn_show'] = false;
	}
}
?>