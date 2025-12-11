<?php
require_once(CUSTOM_CLASSES_PATH . 'admin/masterpage.php');

class CPagesPage extends CMasterPage
{
    /**
     * The table name.
     *
     * @var string
     */
    protected $_table = 'page';

    function CPagesPage(&$app, $template)
	{
		$this->IsSecure = true;
		parent::CMasterPage($app, $template);
		$this->DataBase = &$this->Application->DataBase;
		$lang_rs = $this->Application->Localizer->get_languages();
		if($lang_rs == false) $lang_rs = new CRecordSet();
		$lang_rs->add_row(array('id' => '', 'title' => $this->Application->Localizer->get_string('any')), INSERT_BEGIN);
		$this->_filters = array(	
	    	'pd#title' => array(
	    		'title' => $this->Application->Localizer->get_string('value'),	
	    		'type' => FILTER_TEXT,
	    		'data' => null,
	    		'condition' => CONDITION_LIKE
	    	),
	    	'p#status' => array(
	    		'title' => $this->Application->Localizer->get_string('status'),	
	    		'type' => FILTER_SELECT,
	    		'data' => array(array( '' => $this->Application->Localizer->get_string('any_status'), OBJECT_NOT_ACTIVE => $this->Application->Localizer->get_string('not_active'), OBJECT_ACTIVE => $this->Application->Localizer->get_string('active'))),
	    		'condition' => CONDITION_EQUAL
	    	),
	    	'p#public_date' => array(
	    		'title' => $this->Application->Localizer->get_string('public_date_from'),	
	    		'type' => FILTER_DATE
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
        $query = "SELECT
        		p.id,
        		IF(pd.id > 0, pd.title, '".$this->Application->Localizer->get_string('translate_not_ready')."') as title,
        		IF(p.status = ".OBJECT_NOT_ACTIVE.", '<img src=\"".$this->tv['IMAGES']."icons/apply_disabled.gif \" />', IF(p.status = ".OBJECT_ACTIVE.", '<img src=\"".$this->tv['IMAGES']."icons/apply.gif \" />', '<img src=\"".$this->tv['IMAGES']."icons/calendar.gif \" />')) as status,
				CASE pd.is_ready WHEN 1 THEN '<img src=\"".$this->tv['IMAGES']."icons/apply.gif\">' ELSE '<img src=\"".$this->tv['IMAGES']."icons/apply_disabled.gif\">' END AS is_ready,
        		DATE_FORMAT(p.public_date, '%b %d, %Y %h:%i %p') AS public_date,
				DATE_FORMAT(p.create_date, '%b %d, %Y %h:%i %p') AS create_date
            FROM 
            	%prefix%page p LEFT JOIN %prefix%page_data pd on (pd.page_id = p.id)
            WHERE ".$this->_where. "";

        require_once(BASE_CLASSES_PATH . 'controls/dbnavigator.php');
        $nav = new DBNavigator($this->_table, $query, array('title','status','public_date','create_date'), 'id');
        $nav->title = $this->Application->Localizer->get_string('pages');
        
        $header_num = $nav->add_header('title');
        $nav->headers[$header_num]->set_title($this->Application->Localizer->get_string('title'));
        $nav->headers[$header_num]->set_width('75%');

        $header_num = $nav->add_header('status');
        $nav->headers[$header_num]->set_title($this->Application->Localizer->get_string('status'));
        $nav->headers[$header_num]->set_align('center');
        
        $header_num = $nav->add_header('is_ready');
        $nav->headers[$header_num]->set_title($this->Application->Localizer->get_string('is_ready'));
        $nav->headers[$header_num]->set_width('5%');
        $nav->headers[$header_num]->set_align('center');
        
        $header_num = $nav->add_header('public_date');
        $nav->headers[$header_num]->set_title($this->Application->Localizer->get_string('public_date'));
        $nav->headers[$header_num]->set_width('10%');
        $nav->headers[$header_num]->set_align('center');

        $header_num = $nav->add_header('create_date');
        $nav->headers[$header_num]->set_title($this->Application->Localizer->get_string('create_date'));
        $nav->headers[$header_num]->set_width('10%');
        $nav->headers[$header_num]->set_align('center');
		
        if($nav->size > 0)
        	$this->tv['remove_btn_show'] = true;
        else
        	$this->tv['remove_btn_show'] = false;
	}
}
?>