<?php

class Page {
	public $title;
	public $content = array();
	
	public function process( $Xeno ) {
		array_shift($Xeno->Router->params);	//	we delete the first element, because it is obsolete
		switch ( $Xeno->Router->callback['path_type'] ) {
			case 'function':
				include_once ( XENO_CORE . '/control/home.php' );
				include_once ( XENO_CORE . '/control/dashboard.php' );
				return call_user_func( $Xeno->Router->callback['path_callback'], $Xeno->Router->params, $Xeno );
				break;
			case 'pod':
				include_once ( XENO_CORE . '/control/pod.php' );
				$this->content = pod_load( $Xeno->Router->params[0] );
				$this->title = $this->content['field']['title'][0] ? $this->content['field']['title'][0] : 'Pod number ' . $this->content['pod_id'];
				break;
			case 'error':	//	404 page
				debug( 'THIS PAGE DOES NOT EXIST: ' . $Xeno->Router->callback['path_callback'], null,true );
				break;
			case 'denied':	//	403 page
				debug( 'THIS IS NOT THE PAGE YOU WERE LOOKING FOR: ' . $Xeno->Router->callback['path_callback'], null,true );
				break;
			default:
				debug( $Xeno->Router->callback['path_type'] . ' IS NOT A VALID CALLBACK TYPE', null,true );
		}
	}
}