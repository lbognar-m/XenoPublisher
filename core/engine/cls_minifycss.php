<?php

class MinifyCSS {
	
	private $_style = '';
	
	public function __construct( $_style )
	{
		$this->_style = $_style . "\n";
	}
	
	public function pack($path = '') {
		// remove comments, tabs, spaces, newlines, etc.
		$this->_style = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', ' ', $this->_style);
		$this->_style = str_replace(array("\r\n", "\r", "\n", "\t", '  '), '', $this->_style);
		$this->_style = str_replace(
			 array(';}', ' {', '} ', ': ', ' !', ', ', ' >', '> '),
			 array('}',  '{',  '}',  ':',  '!',  ',',  '>',  '>'), $this->_style);
		
		// url
		// $dir = dirname($path).'/';
		// $this->_style = preg_replace('|url\(\'?"?([a-zA-Z0-9=\?\&\-_\s\./]*)\'?"?\)|', "url(\"$dir$1\")", $this->_style);

		return $this->_style;
	}

}