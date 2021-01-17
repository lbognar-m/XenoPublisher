<?php

class Theme {
	public $name;
	public $dir;
	public $url;
	
	public function setTheme($type) {
		if ( $type == 'admin' ) {
			$this->name = XQuery::get_setting( 'theme_admin' );
		} else {
			$this->name = XQuery::get_setting( 'theme_front' );
		}

		$themefolder = XENO_SITE . '/themes/';
		if (file_exists( $themefolder . $this->name . '/theme.php' )) {
			$this->dir = XENO_SITE . '/themes/' . $this->name;
			$this->url = XENO_URL . 'site/themes/' . $this->name;
		} elseif ($type == 'admin') {
			$this->dir = XENO_SITE . '/themes/queen';
			$this->url = XENO_URL . 'site/themes/queen';
		} else {
			$this->dir = XENO_SITE . '/themes/prime';
			$this->url = XENO_URL . 'site/themes/prime';
		}
	}
}