<?php
require_once('control.php');

class AdminUploader extends CControl{
	
	protected $id;
	protected $DataBase;
	protected $cache;
	protected $options;
	private $uploaded_file;
	
	function AdminUploader($object_id = null, $options = array())
	{
		if(is_null($object_id) || !$object_id)
			system_die('Invalid input: $object_id is required!', 'Admin Uploader');
			
		if(!is_array($options))
			system_die('Invalid input: $options (array)', 'Admin Uploader');
			
		parent::CControl('AdminUploader', $object_id);
		$this->template = BASE_CONTROLS_TEMPLATE_PATH . 'adminuploader.tpl';
		$this->DataBase = $this->Application->DataBase;
		$this->cache = InCache($this->object_id, false, 'AdminUploader');
		$this->options = array(
			'type' => 'simple',
			'standarts' => 'gears,html5,flash,silverlight,browserplus,html4',
			'max_file_size' => '20mb',
			'url' => $this->tv['HTTP'].'ajax/admin/upload.php',
			'upload_path' => ROOT.'pub/uploads/',
			'upload_url' => $this->tv['HTTP'].'pub/uploads/',
			'autostart' => false,
			'input_name' => $this->object_id,
			'is_image' => true,
			'file_types' => array(
				0 => array('title' => $this->Application->Localizer->get_string('image_files'), 'extensions' => 'jpg,gif,png')
			),
			'output_dir' => false,
			'note' => false,
			'replace_files' => true,
			'unique_filename' => false
		);
		
		$this->options = $this->merge_options($options, $this->options);

	}
	
	function merge_options($options_in, $options_out)
	{
		foreach ($options_in as $k => $o)
		{
			if(!is_array($o))
				$options_out[$k] = $o;
			else
				$options_out[$k] = $this->merge_options($o, $options_out[$k]);
		}
		
		return $options_out;
	}
	
	function bind()
	{
		$this->bind_data();
		global $AdminUploaders;
		$AdminUploaders[$this->object_id] = $this;
	}
	
	function bind_data()
	{
		$this->bind_options();
		$this->generate_id();
		$this->bind_files();
	}
	
	function bind_options()
	{
		$this->tv['options'] = $this->options;
	}
	
	function generate_id()
	{
		if($this->cache && isset($this->cache['id']))
		{
			$this->id = $this->tv['id'] = $this->cache['id'];
		}
		else 
		{
			$time = $this->tv['timestamp'] = microtime();
			$this->id = $this->tv['id'] = md5('ShilohKrast' . $time);
			SetCacheVar($this->object_id, array('id' => $this->id), 'AdminUploader');
		}
	}
	
	function bind_files()
	{
		$this->tv['current_file'] = false;
		$new_val = InPostGet($this->options['input_name'], false);
		if(strlen($new_val))
			$this->tv['current_file'] = $this->Application->tv[$this->options['input_name']];
		else
		{
			$this->tv['current_file'] = $this->Application->tv[$this->options['input_name']];
			$this->Application->tv[$this->options['input_name']] = false;
		}
	}

	function get_option($option_name)
	{
		return ( (isset($this->options[$option_name])) ? $this->options[$option_name] : false );
	}

	/**
	 * @param $object_id
	 * @return bool|AdminUploader
	 */
	static function get_instance($object_id)
	{
		global $AdminUploaders;
		return ( (is_array($AdminUploaders) && is_object($AdminUploaders[$object_id])) ? $AdminUploaders[$object_id] : false );
	}

	static function upload($object_id, $output_path, $full_folder = false)
	{
		$uploader = self::get_instance($object_id);
		if($uploader !== false)
		{
			$filename = $new_filename = InGetPost($uploader->get_option('input_name'));
			$input_path = $uploader->get_option('upload_path');
			if($full_folder == false)
				$output_dir = ROOT.$output_path;

			if(!is_dir($output_dir))
			{
				require_once(FUNCTION_PATH .'functions.files.php');
				@makedir($output_dir, true);
			}

			if($uploader->get_option('unique_filename'))
			{
				require_once(FUNCTION_PATH .'functions.files.php');
				$new_filename = unique_filename($filename);
			}

			if($uploader->get_option('replace_files'))
			{
				if(file_exists($output_dir.$new_filename))
					@unlink($output_dir.$new_filename);
			}

			$result = (rename($input_path.$filename, $output_dir.$new_filename));
			return ( ($result) ? $new_filename : false );
		}

		return false;
	}
}
?>