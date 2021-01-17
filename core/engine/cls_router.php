<?php

class Router {
	
	public $path = false;
	public $theme = false;
	public $routes = array();
	public $callback = array();
	public $params = array();
	
	public function __construct() {
		$this->RouteInit();
		$this->RequestPath();
		$this->getTheme();
	}
	
	public function RouteInit() {
		return false;
	}
	
	public function getRoutes() {
		$DBroutes = DB::query( "SELECT * FROM %b", 'path' );
		foreach ($DBroutes as $k => $v) {
			$pattern = '/^' . str_replace('/', '\/', $v['path_url']) . '$/';
			$routelist[$pattern] = $v;
		}
		return $routelist;
	}
	
	public function route($pattern, $callback) {
		$pattern = '/^' . str_replace('/', '\/', $pattern) . '$/';
		$this->routes[$pattern] = $callback;
	}
	
	public function execute($Xeno) {
		$routelist = array_merge( $this->getRoutes(), $this->routes );
		$this->path = $this->path ? $this->path : 'home';
		foreach ( $routelist as $pattern => $callback ) {
			if (preg_match($pattern, $this->path, $params)) {
				$this->callback = $callback;
				$this->params = $params;
				return true;
			}
		}
		$this->callback = array(
				'path_type' => 'error',
				'path_callback' => '404',
			);
		debug( $this->path . ' IS NOT A VALID PATH', null,true );
	}
	
	public function getTheme() {
		if( strpos( $this->path, 'dashboard' ) === 0) {
			$this->theme = 'admin';
		} else {
			$this->theme = 'front';
		}
	}
	
	public function RequestPath() {

		if (isset($_GET['q']) && is_string($_GET['q'])) {
			$this->path = $_GET['q'];
		}
		elseif (isset($_SERVER['REQUEST_URI'])) {
			$request_path = strtok($_SERVER['REQUEST_URI'], '?');
			$base_path_len = strlen(rtrim(dirname($_SERVER['SCRIPT_NAME']), '\/'));
			$this->path = substr(urldecode($request_path), $base_path_len + 1);
			if ($this->path == basename($_SERVER['PHP_SELF'])) {
				$this->path = '';
			}
		}
		else {
			$this->path = '';
		}
		$this->path = trim($this->path, '/');
	}
}