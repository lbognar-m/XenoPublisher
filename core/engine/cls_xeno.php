<?php

/**
* Initialize main page object
*
* Create instances of vital classes
* Create the user object
* Create the page query arguments from URL
* Create the list of CSS and JS to be loaded in the template
*/

class Xeno {

	public $Router;
	public $User;
	public $Theme;
	public $Assets;
	public $Page;

	public function __construct()
	{
		$this->Router = new Router;	//	this takes care of the path, decides which theme should be loaded (admin/front), and which function should be used to generate page data
		$this->User = new User;		//	the user object. contains every information of the currently logged in user to be easily accessed, including roles and permissions
		$this->Theme = new Theme;	//	the theme name and base location by url and local directory
		$this->Assets = new Assets;	//	arrays containing the list of js and css files, identified by a unique ID (so it prevents double inclusion), and arranged by source location (header/footer)
		$this->Page = new Page;		//	The page content's data, in an array
	}
}