<?php
require_once(BASE_CONTROLS_PATH . 'control.php');
define('PAGER_SEPARATOR_WINGS', '<span>...</span>');

class Pager extends CControl
{
	protected $Software;
	protected $count_per_page = 9;
	protected $count_items = 0;
	protected $total_count = 0;
	protected $current_page;
	protected $count_left_wing = 0;
	protected $count_right_wing = 0;
	protected $count_center_wing = 12;
	public $count_pages;
	public $link;

	function Pager($object_id, $curr_page, $count_items, $per_page = 9, $get = false)
	{
		parent::CControl('Pager', $object_id);
		$this->template = CUSTOM_CONTROLS_TEMPLATE_PATH .'pager.tpl';
		$this->current_page = $curr_page;
		$this->count_items = $count_items;
		$this->count_per_page = $per_page;
		$this->count_pages = ceil($count_items / $this->count_per_page);
		$this->link = &$this->tv['link'];
		$this->tv['object_id'] = $object_id;
		$this->tv['get'] = $get;
		$this->bind_data();
	}

	function bind_data()
	{
		$this->tv['pager_hide'] = false;
		$this->tv['la_show'] = ($this->current_page > 1);
		$this->tv['ra_show'] = ($this->current_page < $this->count_pages);
		if($this->count_items > 0)
		{
			$this->tv['sp_start'] = (($this->current_page - 1) * $this->count_per_page) + 1;
			if($this->current_page != $this->count_pages)
				$this->tv['sp_end'] = (($this->current_page - 1) * $this->count_per_page) + $this->count_per_page;
			else
				$this->tv['sp_end'] = $this->count_items;

			$this->tv['current_page'] = $this->current_page;
			$this->tv['count_pages'] = $this->count_pages;
			$this->tv['count_items'] = $this->count_items;
			$this->tv['la_link_page'] = $this->current_page - 1;
			$this->tv['ra_link_page'] = $this->current_page + 1;

			if($this->count_pages > ($this->count_left_wing + $this->count_center_wing + $this->count_right_wing))
			{
				for($i = 1; $i <= $this->count_left_wing; $i++)
					$this->tv['pages'][] = $i;

				if($this->current_page > ($this->count_left_wing + $this->count_center_wing) && $this->current_page <= ($this->count_pages - ($this->count_right_wing + $this->count_center_wing)))
				{
					//$this->tv['pages'][] = PAGER_SEPARATOR_WINGS;

					for($i = ($this->current_page - floor($this->count_center_wing / 2)); $i <= ($this->current_page + floor($this->count_center_wing / 2)); $i++)
						$this->tv['pages'][] = $i;

					//$this->tv['pages'][] = PAGER_SEPARATOR_WINGS;

				}
				elseif($this->current_page <= ($this->count_left_wing + $this->count_center_wing))
				{
					for($i = $this->count_left_wing + 1; $i < $this->count_left_wing + $this->count_center_wing + 1; $i++)
						$this->tv['pages'][] = $i;

					//$this->tv['pages'][] = PAGER_SEPARATOR_WINGS;
				}
				elseif($this->current_page > ($this->count_pages - ($this->count_right_wing + $this->count_center_wing)))
				{
					//$this->tv['pages'][] = PAGER_SEPARATOR_WINGS;

					for($i = ($this->count_pages - ($this->count_right_wing + $this->count_center_wing - 1)); $i < ($this->count_pages - $this->count_right_wing + 1); $i++)
						$this->tv['pages'][] = $i;
				}

				for($i = ($this->count_pages - ($this->count_right_wing - 1)); $i <= $this->count_pages; $i++)
					$this->tv['pages'][] = $i;
			}
			else
			{
				for($i = 0; $i < $this->count_pages; $i++)
					$this->tv['pages'][$i] = $i + 1;
			}
		}
		else
			$this->tv['pager_hide'] = true;
	}

	function get_status()
	{
		return array('start' => $this->tv['sp_start'], 'end' => $this->tv['sp_end'], 'total' => $this->tv['count_items']);
	}
}
?>