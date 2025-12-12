<?php
$formname = '';
$_it_fs = array();
$tinymce_included = false;

class CTemplate extends CDeprecatedTemplate
{
	/**
	 * @var array of attributes that will not be escaped
	 */
	private static $not_escaped_attributes = array(
		'onblur',
		'onchange',
		'onclick',
		'ondblclick',
		'onfocus',
		'onkeydown',
		'onkeypress',
		'onkeyup',
		'onload',
		'onmousedown',
		'onmousemove',
		'onmouseout',
		'onmouseover',
		'onmouseup',
		'onreset',
		'onselect',
		'onsubmit',
		'onunload',
		'value'
	);

	/**
	 * @var array of attributes that will ignore in $attributes
	 */
	private static $excluded_attributes = array(
		'type',
		'id',
		'name'
	);

	/**
	 * @param $id
	 * @param $name
	 * @param $value
	 * @param string $class
	 * @param array $attributes
	 * @param string $target
	 * @param string $param1
	 */
	static function button($id, $name, $value, $class = '', $attributes = array(), $target = '', $param1 = '')
	{
		global $formname;

		if(strlen($formname) > 0)
		{
			if(is_array($attributes))
				$attributes['onclick'] = $formname."_submit('".$name."', '".$target."', '".$param1."', 0);";
			else
				$attributes .= " ".$formname."_submit('".$name."', '".$target."', '".$param1."', 0);";
		}
		self::add_class($class, $attributes);
		$attributes = self::get_attributes($attributes);

		echo '<input type="button" id="'.$id.'" name="'.$name.'" value="'.$value.'"'.$attributes.'/>';
	}

	/**
	 * @param $id
	 * @param $name
	 * @param $value
	 * @param string $class
	 * @param array $attributes
	 * @param string $target
	 * @param string $param1
	 */
	static function reset($id, $name, $value, $class = '', $attributes = array(), $target = '', $param1 = '')
	{
		global $formname;
		self::add_class($class, $attributes);
		$attributes = self::get_attributes($attributes);

		if(strlen($formname) > 0)
			echo '<input type="reset" id="'.$id.'" name="'.$name.'" value="'.$value.'"'.$attributes.'/>';
		else system_die('opening form tag not found');
	}

	/**
	 * @param $id
	 * @param $name
	 * @param $value
	 * @param string $class
	 * @param array|string $attributes
	 */
	static function submit($id, $name, $value, $class = '', $attributes = array())
	{
		global $formname;
		self::add_class($class, $attributes);
		$attributes = self::get_attributes($attributes);

		if(strlen($formname) > 0)
			echo '<input type="submit" id="'.$id.'" name="'.$name.'" value="'.$value.'"'.$attributes.'/>';
		else system_die('opening form tag not found');
	}

	/**
	 * @param $type
	 * @param $id
	 * @param $name
	 * @param string $class
	 * @param array $attributes
	 */
	static function input($type, $id, $name, $class = '', $attributes = array())
	{
		if(in_array($type, array('file','hidden','image','password','radio','text')))
		{
			global $app, $_it_fs, $formname;
			self::add_value($name, $attributes);
			self::add_class($class, $attributes);
			$attributes = self::get_attributes($attributes);

			echo '<input type="'.htmlspecialchars($type).'" id="'.htmlspecialchars($id).'" name="'.htmlspecialchars($name).'"'.$attributes.'/>';

			if(strlen($formname) > 0)
			{
				if(!isset($_it_fs[$formname])) $_it_fs[$formname] = array();
				$_it_fs[$formname][] = $name;
			}
		}
	}

	/**
	 * @param $id
	 * @param $name
	 * @param string $class
	 * @param array $attributes
	 */
	static function checkbox($id, $name, $class = '', $attributes = array())
	{
		global $app, $_it_fs, $formname;
		self::add_class($class, $attributes);
		$attributes = self::get_attributes($attributes);
		if($app->tv[$name])
			$attributes .= ' checked="checked"';
		echo '<input type="checkbox" id="'.htmlspecialchars($id).'" name="'.htmlspecialchars($name).'"'.$attributes.' value="1" />';
		if(strlen($formname) > 0)
		{
			if(!isset($_it_fs[$formname])) $_it_fs[$formname] = array();
			$_it_fs[$formname][] = $name;
		}
	}

	/**
	 * @param $id
	 * @param $name
	 * @param string $class
	 * @param array $attributes
	 */
	static function select($id, $name, $class = '', $attributes = array())
	{
		global $app, $_it_fs, $formname;
		self::add_class($class, $attributes);
		$attributes = self::get_attributes($attributes);
		$tv = $app->tv;
		$tv[$name] = str_replace('"', '&quot;', str_replace('«', '&laquo;', str_replace('»', '&raquo;', $tv[$name])));

		echo '<select id="'.$id.'" name="'.$name.'"'.$attributes.'>';

		foreach ($tv["{$name}:select"] as $id => $option_data)
		{
			if(strlen($tv[$name]) && $tv[$name] == $option_data['value'] && !isset($option_data['attributes']['selected']))
				$option_data['attributes']['selected'] = 'selected';

			$attributes = self::get_attributes($option_data['attributes']);
			echo '<option value="'.$option_data['value'].'" '.$attributes.'>'.$option_data['text'].'</option>';
		}

		echo '</select>';

		if(strlen($formname) > 0)
		{
			if(!isset($_it_fs[$formname])) $_it_fs[$formname] = array();
			$_it_fs[$formname][] = $name;
		}
	}

	/**
	 * @param $id
	 * @param $name
	 * @param string $class
	 * @param array $attributes
	 */
	static function textarea($id, $name, $class = '', $attributes = array('cols' => 150, 'rows' => 7))
	{
		global $app, $_it_fs, $formname;
		self::add_class($class, $attributes);
		$attributes = self::get_attributes($attributes);
		$tv = $app->tv;
		$value = str_replace('"', '&quot;', str_replace('«', '&laquo;', str_replace('»', '&raquo;', $tv[$name])));
		echo '<textarea id="'.htmlspecialchars($id).'" name="'.htmlspecialchars($name).'"'.$attributes.'>'.$value.'</textarea>';
		if(strlen($formname) > 0)
		{
			if(!isset($_it_fs[$formname])) $_it_fs[$formname] = array();
			$_it_fs[$formname][] = $name;
		}
	}

	/**
	 * @param $id
	 * @param $name
	 * @param string $class
	 * @param array $attributes
	 */
	static function htmlarea($id, $name, $class = '', $attributes = array())
	{
		global $app, $_it_fs, $formname, $tinymce_included;
		self::add_class($class.' mceEditor', $attributes);
		$attributes = self::get_attributes($attributes);
		$tv = $app->tv;
		$value = $tv[$name];

		echo '<div class="html-cont">';
		if($tinymce_included === false)
		{
			echo '
			<!-- TinyMCE -->
				<script type="text/javascript" src="'.$tv['JS'].'tiny_mce/tiny_mce.js"></script>
				<script type="text/javascript">
					tinyMCE.init({
						// General options
						mode : "specific_textareas",
						editor_selector : "mceEditor",
						relative_urls : false,
						theme : "advanced",
						plugins : "autolink,lists,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,wordcount,advlist,autosave,jbimages",
						// Theme options
						theme_advanced_buttons1 : "bold,italic,underline,strikethrough,separator,sub,sup,separator,justifyleft,justifycenter,justifyright,justifyfull,separator,formatselect,fontselect,fontsizeselect",
						theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,separator,undo,redo,separator,search,replace,separator,bullist,numlist,separator,outdent,indent,blockquote,separator,link,unlink,anchor,charmap,hr",
						theme_advanced_buttons3 : "forecolor,backcolor,|,insertdate,inserttime,|,removeformat,cleanup,|,preview,print,fullscreen,|,code,|,pagebreak,|,jbimages",
						theme_advanced_toolbar_location : "top",
						theme_advanced_toolbar_align : "left",
						theme_advanced_statusbar_location : "bottom",
						theme_advanced_resizing : true,
						pagebreak_separator : "<!-- more -->",
						// Example content CSS (should be your site CSS)
						content_css : "'.$tv['CSS'].'editor.css",
					});
				</script>
			<!-- /TinyMCE -->';
			$tinymce_included = true;
		}
		echo '<textarea mce_editable="true" id="'.$id.'" name="'.$name.'"'.$attributes.'>'.htmlspecialchars(strval($value)).'</textarea></div>';
		if(strlen($formname) > 0)
		{
			if(!isset($_it_fs[$formname])) $_it_fs[$formname] = array();
			$_it_fs[$formname][] = $name;
		}
	}

	function rating_stars($rate, $title = '', $max_stars = 5, $separator = '')
	{
		global $ImagesPath;
		$alt_def = '';
		if(strlen($title) > 0)
			$alt_def .= $title.' - ';

		$int = ceil($rate);
		$fract = 0;

		for($i = 1; $i <= $max_stars; $i++)
		{
			if($i > 1)
			{
				$alt = $alt_def."{$i} ".self::get_loc_string('stars');
				$title = $alt_def.self::get_loc_string('rate')." - "."{$i} ".self::get_loc_string('stars');
			}
			else
			{
				$alt = $alt_def."{$i} ".self::get_loc_string('star');
				$title = $alt_def.self::get_loc_string('rate')." - "."{$i} ".self::get_loc_string('star');
			}

			if($i == $int && $fract > 0)
			{
				echo '<img height="11" width="12" title="'.$title.'" alt="'.$alt.'" src="'.$HTTP.'images/half-rating.gif" />'.(($i !== $max_stars) ? $separator : '');
			}
			elseif($i > $rate){
				echo '<img height="11" width="12" title="'.$title.'" alt="'.$alt.'" src="'.$HTTP.'images/star_disable.png" />'.(($i !== $max_stars) ? $separator : '');
			}
			else {
				echo '<img height="11" width="12" title="'.$title.'" alt="'.$alt.'" src="http://sportmax.office/images/star_enable.png" />'.(($i !== $max_stars) ? $separator : '');
			}
		}
	}

	function set_select_data($name, $arr, $id='id', $text='title', &$tv = null)
	{
		global $app;
		if(is_null($tv)) $tv = &$app->tv;

		$tv[$name.':select'] = array();

		if(is_array($arr)) foreach ($arr as $key => $val) $tv[$name.':select'][$key] = $val;
		elseif (strcasecmp(get_class($arr), 'CRecordSet') == 0)
		{
			if($arr !== false && !$arr->eof())
				while (!$arr->eof())
				{
					$tv[$name.':select'][$arr->get_field($id)] = $arr->get_field($text);
					$arr->next();
				}
			else
				$tv[$name.':select'][''] = $GLOBALS['app']->Localizer->get_string('internal_error');
		}
	}



	function select_rate($id, $name, $max_stars = 5, $separator = '')
	{
		global $ImagesPath, $JSPath;
		echo '<script type="text/javascript">
			var select = "'.$id.'";
		</script>';
		self::select($id, $name, $name);
	}

	/**
	 * @param array|string $attributes
	 * @return string
	 */
	static function get_attributes($attributes)
	{
		$attrs = '';
		if(is_array($attributes))
		{
			if(empty($attributes))
				$attrs = '';
			else
				foreach($attributes as $attr => $value)
					if(!in_array($attr, self::$excluded_attributes))
						$attrs .= (!in_array($attr, self::$not_escaped_attributes)) ? ' '.htmlspecialchars($attr).'="'.htmlspecialchars($value).'"' : ' '.$attr.'="'.str_replace('"', "'", $value).'"';
		}
		else $attrs = $attributes;

		return $attrs;
	}

	/**
	 * @param string $class
	 * @param array|string $attributes
	 */
	static function add_class($class, &$attributes)
	{
		if(strlen($class))
		{
			if(is_array($attributes))
				$attributes['class'] = $class;
			else
			{
				if(!is_string($attributes))
					$attributes = '';
				$attributes .= ' class="'.$class.'"';
			}
		}
	}

	/**
	 * @param string $name
	 * @param array|string $attributes
	 */
	private static function add_value($name, &$attributes)
	{
		if(strlen($name))
		{
			global $app;
			$tv = $app->tv;
			$value = str_replace('"', '&quot;', str_replace('«', '&laquo;', str_replace('»', '&raquo;', $tv[$name])));
			if(is_array($attributes))
				$attributes['value'] = $value;
			else
			{
				if(!is_string($attributes))
					$attributes = '';
				$attributes .= ' value="'.$value.'"';
			}
		}
	}
}

class CDeprecatedTemplate {
	
	static function get_loc_string($str){
		return $GLOBALS['app']->Localizer->get_string($str);
	}
	
	static function loc_string($str){
		echo $GLOBALS['app']->Localizer->get_string($str);
	}

	static function button($id, $name, $value, $class = '', $target = '', $param1 = '')
	{
		global $formname;
		if(strlen($formname) > 0) echo "<input type=\"button\" id=\"{$id}\" name=\"{$name}\" value=\"{$value}\" onclick=\"{$formname}_submit('{$name}', '{$target}', '{$param1}', 0);\" class=\"{$class}\" />";
		else system_die('opening form tag not found');
	}

	static function reset($id, $name, $value, $class = '', $target = '', $param1 = '')
	{
		global $formname;
		if(strlen($formname) > 0) echo "<input type=\"reset\" id=\"{$id}\" name=\"{$name}\" value=\"{$value}\" class=\"{$class}\" />";
		else system_die('opening form tag not found');
	}
	
	static function submit($id, $name, $value, $class = '', $attributes = array())
	{
		global $formname;
		if(strlen($formname) > 0) echo "<input type=\"submit\" id=\"{$id}\" name=\"{$name}\" value=\"{$value}\" class=\"{$class}\" />";
		else system_die('opening form tag not found');
	}
	
	static function input($type, $id, $name, $class = '', $attributes = array())
	{
		global $app, $_it_fs, $formname;
		$tv = $app->tv;
		$tv[$name] = str_replace('"', '&quot;', str_replace('«', '&laquo;', str_replace('»', '&raquo;', $tv[$name])));
		switch ($type)
		{
			case 'text':
				if(XHTML)
					echo '<input type="text" id="'.$id.'" name="'.$name.'"'.((strlen($class)>0) ? ' class="'.$class.'"' : null).' value="'.$tv[$name].'"'.(($readonly) ? ' readonly="readonly"' : null).' '.(($disabled) ? ' disabled="disabled"' : null).' />';
				else
					echo '<input type="text" id="'.$id.'" name="'.$name.'"'.((strlen($class)>0) ? ' class="'.$class.'"' : null).' value="'.$tv[$name].'"'.(($readonly) ? ' readonly' : null).' '.(($disabled) ? ' disabled' : null).'>';
				break;
			case 'hidden':
				if(XHTML)
					echo '<input type="hidden" id="'.$id.'" name="'.$name.'"'.((strlen($class)>0) ? ' class="'.$class.'"' : null).' value="'.$tv[$name].'"'.(($readonly) ? ' readonly="readonly"' : null).' '.(($disabled) ? ' disabled="disabled"' : null).' />';
				else
					echo '<input type="hidden" id="'.$id.'" name="'.$name.'"'.((strlen($class)>0) ? ' class="'.$class.'"' : null).' value="'.$tv[$name].'"'.(($readonly) ? ' readonly' : null).' '.(($disabled) ? ' disabled' : null).'>';
				break;
			case 'password':
				if(XHTML)
					echo '<input type="password" id="'.$id.'" name="'.$name.'"'.((strlen($class)>0) ? ' class="'.$class.'"' : null).' value=""'.(($readonly) ? ' readonly="readonly"' : null).' '.(($disabled) ? ' disabled="disabled"' : null).' />';
				else
					echo '<input type="password" id="'.$id.'" name="'.$name.'"'.((strlen($class)>0) ? ' class="'.$class.'"' : null).' value=""'.(($readonly) ? ' readonly' : null).' '.(($disabled) ? ' disabled' : null).' />';
				break;
			case 'checkbox':
				if(XHTML)
					echo '<input type="checkbox" id="'.$id.'" name="'.$name.'"'.((strlen($class)>0) ? ' class="'.$class.'"' : null).' value="1"'.(($readonly) ? ' readonly="readonly"' : null).' '.(($disabled) ? ' disabled="disabled"' : null).''.((intval($tv[$name]) > 0) ? 'checked="checked"' : null).' />';
				else
					echo '<input type="checkbox" id="'.$id.'" name="'.$name.'"'.((strlen($class)>0) ? ' class="'.$class.'"' : null).' value="1"'.(($readonly) ? ' readonly' : null).' '.(($disabled) ? ' disabled' : null).''.((intval($tv[$name]) > 0 || $checked) ? 'checked' : null).' />';
				break;
			case 'select':
				if(!isset($tv["{$name}:select"]) || !is_array($tv["{$name}:select"])) system_die('Invalid data for select: use CInput::set_select_data()', 'CDeprecatedTemplate::input');
				if(XHTML)
					echo '<select id="'.$id.'" name="'.$name.'"'.((strlen($class)>0) ? ' class="'.$class.'"' : null).(($readonly) ? ' readonly="readonly"' : null).' '.(($disabled) ? ' disabled="disabled"' : null).'>';
				else 
					echo '<select id="'.$id.'" name="'.$name.'"'.((strlen($class)>0) ? ' class="'.$class.'"' : null).(($readonly) ? ' readonly' : null).' '.(($disabled) ? ' disabled' : null).'>';
					
				foreach ($tv["{$name}:select"] as $id => $text)
					if($tv[$name] == $id) 
						echo '<option value="'.$id.'"'.((XHTML) ? 'selected="selected"' : 'selected').'>'.$text.'</option>';
					else 
						echo '<option value="'.$id.'">'.$text.'</option>';
				echo '</select>';
				break;
			case 'file':
				if(XHTML)
					echo '<input type="file" id="'.$id.'" name="'.$name.'"'.((strlen($class)>0) ? ' class="'.$class.'"' : null).' value="'.$tv[$name].'"'.(($readonly) ? ' readonly="readonly"' : null).' '.(($disabled) ? ' disabled="disabled"' : null).' />';
				else	
					echo '<input type="file" id="'.$id.'" name="'.$name.'"'.((strlen($class)>0) ? ' class="'.$class.'"' : null).' value="'.$tv[$name].'"'.(($readonly) ? ' readonly' : null).' '.(($disabled) ? ' disabled' : null).' />';
			break;
		}
		
		if(!isset($_it_fs[$formname])) $_it_fs[$formname] = array();
		$_it_fs[$formname][] = $name;
		
	}
	
	static function textarea($id, $name, $class = '', $attributes = array('cols' => 150, 'rows' => 7))
	{
		global $app, $_it_fs, $formname;
		$tv = &$app->tv;
		if(!$is_htmlarea)
			if(XHTML)
				echo '<textarea id="'.$id.'" name="'.$name.'" cols="'.$cols.'" rows="'.$rows.'"'.((strlen($class) > 0) ? ' class="'.$class.'"' : null).(($readonly) ? ' readonly="readonly"' : null).' '.(($disabled) ? ' disabled="disabled"' : null).'>'.$tv[$name].'</textarea>';
			else 
				echo '<textarea id="'.$id.'" name="'.$name.'" cols="'.$cols.'" rows="'.$rows.'"'.((strlen($class) > 0) ? ' class="'.$class.'"' : null).(($readonly) ? ' readonly' : null).' '.(($disabled) ? ' disabled' : null).'>'.$tv[$name].'</textarea>';
		else 
		{
			$out = '<div class="html-cont">
		            <!-- TinyMCE -->
					<script type="text/javascript" src="'.$tv['HTTP'].'js/tiny_mce/tiny_mce.js"></script>
					<script type="text/javascript">
						tinyMCE.init({
							// General options
							mode : "specific_textareas",
		                    editor_selector : "mceEditor",
							theme : "advanced",
							plugins : "autolink,lists,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,wordcount,advlist,autosave",
							// Theme options
							theme_advanced_buttons1 : "bold,italic,underline,strikethrough,separator,sub,sup,separator,justifyleft,justifycenter,justifyright,justifyfull,separator,formatselect,fontselect,fontsizeselect",
		                    theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,separator,undo,redo,separator,search,replace,separator,bullist,numlist,separator,outdent,indent,blockquote,separator,link,unlink,anchor,charmap,hr",
		                    theme_advanced_buttons3 : "forecolor,backcolor,|,insertdate,inserttime,|,removeformat,cleanup,|,preview,print,fullscreen,|,code",
							theme_advanced_toolbar_location : "top",
							theme_advanced_toolbar_align : "left",
							theme_advanced_statusbar_location : "bottom",
							theme_advanced_resizing : true,
							
							// Example content CSS (should be your site CSS)
							content_css : "'.$tv['CSS'].'weditor.css",
						});
					</script>
					<!-- /TinyMCE -->';
			$out .= '<textarea mce_editable="true" class="mceEditor" id="'.$id.'" name="'.$name.'">'.htmlspecialchars(strval($tv[$name])).'</textarea></div>';
			echo $out;
		}
		if(!isset($_it_fs[$formname])) $_it_fs[$formname] = array();
		$_it_fs[$formname][] = $name;
	}
	
	static function parse_string($str, $tv = null)
	{
		global $app;
		if(is_null($tv))
			$tv = $app->tv;
			
		foreach ($tv as $var => $value)
			$str = str_replace("<%={$var}%>", strval($value), $str);
			
		return $str;
	}

}

class CForm
{
	const ENCTYPE_URL_ENCODED = 'application/x-www-form-urlencoded';
	const ENCTYPE_MULTIPART_DATA = 'multipart/form-data';
	const ENCTYPE_TEXT_PLAIN = 'text/plain';

	static function begin($id, $method = 'post', $action = '', $class = '', $attributes = array(), $ajaxValidator = false){
		global $formname, $app;
		if(strlen($formname) > 0) system_die('opening form tag can not be used because the previous form is not closed');
		else 
		{
			$formname = $id;
			if($ajaxValidator)
				$class .= ' ajax_validation';
			CTemplate::add_class($class, $attributes);
			$attributes = CTemplate::get_attributes($attributes);
			echo '
			<script type="text/javascript">
				function '.$id.'_submit(act,trg,prm,v) {
					var f=document.forms.'.$id.';
					f.target=trg;
					f.elements.param1.value = prm;
					f.elements.param2.value = act;
					if ((act !== "") && (!v)) {
						'.$id.'noa=1;
						f.submit();
					} else {
						'.$id.'oa=1;
						f.submit();
					}
				}
			</script>';

			echo '<form id="'.htmlspecialchars($id).'" method="'.htmlspecialchars($method).'" action="'.htmlspecialchars($action).'"'.$attributes.'>';

			echo '<input type="hidden" name="formname" value="'.$id.'" />';
			echo '<input type="hidden" name="param2" value="" /><input type="hidden" name="param1" value="" />';
			if($ajaxValidator === true && $page_class = get_class($app->CurrentPage))
				echo '<input type="hidden" id="'.htmlspecialchars($id).'_page_class" name="page_class" value="'.$page_class.'" />';
		}
	}

	static function end()
	{
                global $formname, $_it_fs;
                if(strlen($formname) > 0)
                {
                        $it_fs = (isset($_it_fs[$formname]) && is_array($_it_fs[$formname])) ? $_it_fs[$formname] : array();
                        echo '<input type="hidden" name="_it_fs" value="'. base64_encode(join(',', $it_fs)) .'" />';
                        echo '</form>';
                        $formname = '';
                }
		else system_die('closing tag can not be used because of lack of opening <form>');	
	}

	static function is_submit($name, $action = null, $check_action = true) {
		$r = (strcasecmp(InPostGet('formname'), $name)==0);
		if ($check_action)
			if (is_null($action)) return ( $r && (0==strlen(InPostGet('param2'))) );
			else return ( $r && (strcasecmp(InPostGet('param2'), $action)==0) );
		else return $r;
	}

	static function get_param() {
		return InPostGet('param1');
	}
}

class CInput{
	
	static function set_select_data($name, $arr, $id='id', $text='title', $attributes = '', &$tv = null)
	{
		global $app;
		if(is_null($tv)) $tv = &$app->tv;
		
		$tv[$name.':select'] = array();
		
		if(is_array($arr))
			foreach ($arr as $key => $data)
			{
				if(is_array($data))
					$tv[$name.':select'][] = array('value' => ( (isset($data[$id])) ? $data[$id] : $key ), 'text' => $arr[$text], 'attributes' => $arr[$attributes]);
				else
					$tv[$name.':select'][] = array('value' => $key, 'text' => $data);
			}
		elseif ($arr !== false && strcasecmp(get_class($arr), 'CRecordSet') == 0)
		{
			$arr->first();
			if(!$arr->eof())
			{
				while (!$arr->eof())
				{
					$data = array('value' => $arr->get_field($id), 'text' => $arr->get_field($text));
					if(strlen($attributes))
						$data['attributes'] = $arr->get_field($attributes);

					$tv[$name.':select'][] = $data;
					$arr->next();
				}
				$arr->first();
			}
			else
				$tv[$name.':select'][] = $app->Localizer->get_string('internal_error');
		}
	}
}

function html_escape($value, $print = true, $allowable_tags = '<p><strong><b><font><h1><h2><h3><h4><h5><h6>')
{
	if(!strval($value))
		system_die('Invalid input $value (string)', 'html_escape()');
	if(!is_bool($print))
		system_die('Invalid input $print (bool)', 'html_escape()');
	if(!is_string($allowable_tags))
		system_die('Invalid input $allowable_tags (string)', 'html_escape()');
		
	if($print === true)
		echo strip_tags($value, $allowable_tags);
	else 
		return strip_tags($value, $allowable_tags);
}
function js_escape($value, $print = true)
{
	if(!strval($value))
		system_die('Invalid input $value (string)', 'js_escape()');
	if(!is_bool($print))
		system_die('Invalid input $print (bool)', 'js_escape()');
		
	if($print === true)
		echo stripcslashes(js_escape_string($value));
	else 
		return stripcslashes(js_escape_string($value));
}
function escape($value, $print = true)
{
	if(!strval($value))
		system_die('Invalid input $value (string)', 'escape()');
	if(!is_bool($print))
		system_die('Invalid input $print (bool)', 'escape()');
		
	if($print === true)
		echo htmlspecialchars($value);
	else 
		return htmlspecialchars($value);
}
?>