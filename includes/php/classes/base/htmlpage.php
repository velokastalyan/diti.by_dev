<?php
require_once(BASE_CLASSES_PATH. 'object.php');
require_once(BASE_CONTROLS_PATH . 'simplearrayoutput.php');

class CHTMLPage extends CObject {
	
	protected $Application;
	protected $tv;
	protected $h_header = '_header.tpl';
	protected $h_body = '_body.tpl';
        protected $h_footer = '_footer.tpl';
        protected $template;
        public $no_html;
        public $is_secure = false;
        public $IsSecure = false;
        public $m_Controls = array();
        public $IMAGES;
        public $last_modified;
        protected $PAGE_TITLE;
        protected $PAGE_KEYWORDS;
        protected $PAGE_DESCRIPTION;
        public $HTTP;
        public $HTTPS;
        protected $state = HTML_PAGE_DEFAULT;
        protected $state_info = '';
	
	function CHTMLPage(CApp $app, $content = ''){
		parent::CObject();
		$this->Application = &$app;
		$this->Application->CurrentPage = &$this;
		$this->tv = &$app->tv;
		$this->template = $content;
		global $CSSPath, $JSPath, $ImagesPath;
		$this->tv['CSS'] = $CSSPath;
		$this->tv['JS'] = $JSPath;
		$this->tv['IMAGES'] = $ImagesPath;
		$this->PAGE_TITLE = &$this->tv['PAGE_TITLE'];
		$this->PAGE_KEYWORDS = &$this->tv['PAGE_KEYWORDS'];
		$this->PAGE_DESCRIPTION = &$this->tv['PAGE_DESCRIPTION'];
	}
	
	function parse_data(){

		global $RootPath, $ssl_root, $JSPath, $CSSPath, $ImagesPath, $HttpName, $HttpPort, $SHttpName, $SHttpPort, $SiteUrl, $HTTPSSiteUrl, $SiteName;

                if (intval($HttpPort) != 80)
                        $http_port = ':'.$HttpPort;
                else
                        $http_port = '';
                if (intval($SHttpPort) != 443)
                        $shttp_port = ':'.$SHttpPort;
                else
                        $shttp_port = '';

                $http_port_effective = $http_port;
                $shttp_port_effective = $shttp_port;
                if (preg_match('~:\\d+$~', $SiteUrl)) {
                        $http_port_effective = '';
                }
                if (preg_match('~:\\d+$~', $HTTPSSiteUrl)) {
                        $shttp_port_effective = '';
                }

		if ( (isset($_SERVER['HTTPS'])) && (strcasecmp($_SERVER['HTTPS'],'on')==0) )
		{
                        $this->tv['ROOT'] = $SHttpName.'://'.$HTTPSSiteUrl.$shttp_port_effective.$ssl_root;
                        $this->tv['IS_SSL'] = true;
                }
                else
                        $this->tv['IS_SSL'] = false;

                $this->tv['SITE_NAME'] = $SiteName;
                $this->tv['HTTP'] = $this->HTTP = $HttpName.'://'.$SiteUrl.$http_port_effective.$RootPath;
                $this->tv['HTTPS'] = $this->HTTPS = $SHttpName.'://'.$HTTPSSiteUrl.$shttp_port_effective.$ssl_root;

		if ($this->IsSecure)
			if (!$this->Application->User->is_logged())
			{
				$this->redirect($this->tv['HTTP'] . 'admin/?r=login');
			}

		$this->check_logout_form_submit();
		$this->Application->User->set_logged_vars($this->tv);
		if(is_object($this->Application->VKauth))
			$this->Application->VKauth->set_logged_vars($this->tv);
		if(is_object($this->Application->FBauth))
			$this->Application->FBauth->set_logged_vars($this->tv);

		$return_value = true;
		$r_v = $this->Application->on_page_init(); // global on_page_init event
		if (!is_bool($r_v)) $r_v = true;
		$return_value &= $r_v;
		if ($return_value)
		{
			$r_v = $this->on_page_init(); // local on_page_init event
			if (!is_bool($r_v)) $r_v = true;
			$return_value &= $r_v;

			if ($return_value)
			{
				$ctrl = array_keys($this->m_Controls);
				foreach ($ctrl as $k)
					if ($return_value)
						if (!$this->m_Controls[$k]->is_inited)
						{
							$r_v = $this->m_Controls[$k]->on_page_init();
							$this->m_Controls[$k]->is_inited = true;
							if (!is_bool($r_v)) $r_v = true;
							$return_value &= $r_v;
						}
				if ($return_value)
					$return_value = $this->_handle_forms(); // handle submitted forms
			}

            //$this->tv['HTTP'] = 'http://'.$_SERVER['HTTP_HOST'].'/'; //TEMPORARY
            //$this->tv['ROOT'] = 'http://'.$_SERVER['HTTP_HOST'].'/'; //TEMPORARY
			//$this->tv['Global_uri'] = str_replace('sportmax/','',$_SERVER['REQUEST_URI']);
			$this->tv['Global_uri'] = $_SERVER['REQUEST_URI'];
			//$this->tv['Global_uri'] = $_SERVER['REQUEST_URI'];
		}

		return $return_value;
		
	}
	
	function check_logout_form_submit()
	{
		if(CForm::is_submit('logout_form')){
			$this->Application->User->logout();
			if($this->tv['admin_page']) $this->internalRedirect($this->tv['HTTP'] .'admin/');
			$this->tv['_info'] = $this->Application->Localizer->get_string('logout_success');
		}
	}
	
	function parse_state(){
		
		switch ($this->state)
		{
			case HTML_PAGE_ERROR: // error 500
			{
				$this->tv['state_info'] = $this->state_info;
				$this->h_content = BASE_TEMPLATE_PATH.'_error.tpl';
				break;
			}
			case HTML_PAGE_DEFAULT:
			{
				break;
			}
			case HTML_PAGE_REDIRECT: // redirect
			{
				$this->internalRedirect($this->state_info);
				break;
			}
			case HTML_PAGE_LOGIN: // login
			{
				if ($this->tv['admin_page'])
					$this->h_content = BASE_TEMPLATE_PATH.'_admin_login.tpl';
				else
					$this->h_content = BASE_TEMPLATE_PATH.'_login.tpl';
				break;
			}
			case HTML_PAGE_MESSAGE_INFO: // info messages
			{
				$this->tv['state_info'] = $this->state_info;
				$this->h_content = BASE_TEMPLATE_PATH.'_info.tpl';
				break;
			}
			case HTML_PAGE_MESSAGE_ERROR: // error message
			{
				$this->tv['state_info'] = $this->state_info;
				$this->h_content = BASE_TEMPLATE_PATH.'_error.tpl';
				break;
			}
		}
		
	}
	
	function on_page_init(){
		$this->currency();
		return true;
	}
	
	function output_page(){
		$this->draw_header();
		$this->get_content();
		$this->draw_footer();
		$this->DebugInfo->OutPut();
	}
	
	function get_content(){
		if(strlen($this->template) > 0)
			$this->process_template($this->tv, CUSTOM_TEMPLATE_PATH.$this->template);
	}
	
	function draw_header(){
		if($this->no_html) return true;
		$this->process_template($this->tv, array(BASE_TEMPLATE_PATH.$this->h_header, BASE_TEMPLATE_PATH.$this->h_body));
	}
	
	function draw_footer(){
		if($this->no_html) return true;
		$this->process_template($this->tv, BASE_TEMPLATE_PATH.$this->h_footer);		
	}
	
	function process_template($tv, $template){
		foreach ($tv as $key => $value){${$key} = $value;}
		$tv = false;
		if(is_array($template))
			foreach ($template as $temp)
				if(file_exists($temp)){
					require_once($temp);
				}
				else
					system_die("Page Invalid template path {$temp}");
		else 
			if(file_exists($template)){
				require_once($template);
			}
			else
				system_die("Page Invalid template path {$template}");
	}
	
	function _handle_forms()
	{
		$form = InPostGet('formname', '');
		$action = InPostGet('param2', '');
		if (strlen($form))
		{
			$method = 'on_'.$form.'_submit';
			if (@method_exists($this, $method))
				return call_user_func(array(&$this, $method), $action);
			$ctrl = array_keys($this->m_Controls);
			foreach ($ctrl as $k)
				if (@method_exists($this->m_Controls[$k], $method))
					return call_user_func(array(&$this->m_Controls[$k], $method), $action);
		}
		return true;
	}
	
	function no_html()
	{
		$this->no_html = true;
	}
	
	function redirect($url)
	{
		$this->state = HTML_PAGE_REDIRECT;
		$this->state_info = $url;
	}

    function internalRedirect($url)

    {
        @header('HTTP/1.0 301 Moved Permanently');
        @header('Status: 301 Moved Permanently');
        @header('Location: '.$url);
        echo '<?xml version="1.0" encoding="windows-1252"?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html><head><title>Automatic redirection page</title><meta http-equiv="Refresh" content="0;URL='.htmlspecialchars($url).'" /></head><body><a href="'.htmlspecialchars($url).'">click here if your browser doesn\'t support automatic redirection</a></body></html>';
        $this->IsRedirect = true;
        exit();
    }

	function currency()
	{
		//Курс валты
		$this->Registry = $this->Application->get_module('Registry');
		$rs = $this->Registry->get_pathes_values('rate');
		if($rs !== false && !$rs->eof())
		{
			$this->tv['sd_found'] = true;
			while(!$rs->eof())
			{
				$this->tv['sd_'.$rs->get_field('value_path')] = $rs->get_field('value');
				$rs->next();
			}
		}
	}
};
?>