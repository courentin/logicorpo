<?php 

namespace LogiCorpoBundle\Manager;
/**
* 
*/
class MenuManager
{
	private $settings;
	
	public function __construct(array $settings = array())
	{
		$this->settings = $settings;
	}

	public function getMenu($role)
	{
		$menu = array();
		/*
		foreach ($this->settings[$role] as $route) {
			$menu[] = $this->settings[$route];
		}
*/
		return $menu;
	}
}