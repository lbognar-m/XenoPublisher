<?php

class Assets {

	public $js = array( 'header' => array(), 'footer' => array() );
	public $css = array();

//	add
	public function AddJS( $name, $path, $location = 'footer' ) {
		$this->js[$location][$name]['path'] = $path;
		return true;
	}

	public function AddCSS( $name, $path ) {
		$this->css[$name]['path'] = $path;
		return true;
	}

//	remove
	public function RemoveJS($name) {
		unset($this->js['header'][$name]);
		unset($this->js['footer'][$name]);
		return true;
	}

	public function RemoveCSS($name) {
		unset($this->css[$name]);
		return true;
	}

//	print (these are the functions that print out the final output and should be called at the corresponding location of the render code)
	public function PrintCSS() {
		if (count($this->css) > 0) {
			$stylelist = $this->Prepare($this->css, 'css');
			foreach ($stylelist as $v) {
				echo '<link rel="stylesheet" type="text/css" href="' . $v['path'] . '" media="screen" />' . "\n";
			}
			return true;
		}
		return false;
	}

	public function PrintJS($location) {
		if (count($this->js[$location]) > 0) {
			$scriptlist = $this->Prepare($this->js[$location], 'js', $location);
			foreach ($scriptlist as $v) {
				echo '<script type="text/javascript" src="' . $v['path'] . '"></script>' . "\n";
			}
			return true;
		}
		return false;
	}

//	minify

	public function Minify($string, $type) {
		if ($type == 'js') {
			$myPacker = new MinifyJS($string);
		} elseif ($type == 'css') {
			$myPacker = new MinifyCSS($string);
		} else {
			$string = '';
		}
		$string = $myPacker->pack();
		return $string;
	}

//	prepare
	private function Prepare($array, $type, $location = '', $forced = 0) {
		//	get info from options if we need combine and compression. in db there will be 5 values: merge, minify.
		$combine = XQuery::get_setting('combine_' . $type);
		$minify = XQuery::get_setting('minify_' . $type);
		$location_suffix = ( $location == '' ? '' : '.' . $location );
		
		if ( $combine == 1 && count($array) > 0 ) {
			$filename = 'site.min' . $location_suffix . '.' . $type;
			if( !file_exists( XENO_VAULT . '/cache/' . $filename ) || $forced ) {
				$all_files = '';
				foreach ($array as $v) {
					$all_files .= file_get_contents($v['path']);
				}
				
				if ($minify == 1) {
					$all_files = $this->Minify($all_files, $type);
				}
				
				file_put_contents(XENO_VAULT . '/cache/' . $filename, $all_files);
			}
			$array = array();
			$array['minified']['path'] = XENO_URL . 'vault/cache/' . $filename;
		}
		return $array;
	}
	
	public function Rebuild() {
		$this->Prepare($this->js['header'], 'js', 'header', 1);
		$this->Prepare($this->js['footer'], 'js', 'footer', 1);
		$this->Prepare($this->css, 'css', '', 1);
		
	}
}