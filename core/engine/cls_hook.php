<?php

//	wrap the non-static hook into a static
class HOOK {
	
	protected static $xhook = null;

	public static function __callStatic( $name, $arguments ) {
		$xhook = HOOK::$xhook;
		
		if ( $xhook === null ) {
			$xhook = HOOK::$xhook = new XenoHook();
			call_user_func_array(array($xhook, $name), $arguments);	//	ugly but short method to call a variable method from another class, correctly passing the arguments though
		}
	}	
}


class XenoHook
{
	var $filters = array();
	var $merged_filters = array();
	var $actions = array();
	var $current_filter = array();

	public function __construct($args = null)
	{
		$this->filters = array();
		$this->merged_filters = array();
		$this->actions = array();
		$this->current_filter = array();
	}

	public function add_filter($tag, $function_to_add, $priority = 10, $accepted_args = 1) {
		$idx =	$this->_filter_build_unique_id($tag, $function_to_add, $priority);
		$this->filters[$tag][$priority][$idx] = array('function' => $function_to_add, 'accepted_args' => $accepted_args);
		unset( $this->merged_filters[ $tag ] );
		return true;
	}

	public function remove_filter( $tag, $function_to_remove, $priority = 10 ) {
		$function_to_remove = $this->_filter_build_unique_id($tag, $function_to_remove, $priority);

		$r = isset($this->filters[$tag][$priority][$function_to_remove]);

		if ( true === $r) {
			unset($this->filters[$tag][$priority][$function_to_remove]);
			if ( empty($this->filters[$tag][$priority]) )
				unset($this->filters[$tag][$priority]);
			unset($this->merged_filters[$tag]);
		}
		return $r;
	}

	public function remove_all_filters($tag, $priority = false) {
		if( isset($this->filters[$tag]) ) {
			if( false !== $priority && isset($this->filters[$tag][$priority]) )
				unset($this->filters[$tag][$priority]);
			else
				unset($this->filters[$tag]);
		}

		if( isset($this->merged_filters[$tag]) )
			unset($this->merged_filters[$tag]);

		return true;
	}

	public function has_filter($tag, $function_to_check = false) {
		$has = !empty($this->filters[$tag]);
		if ( false === $function_to_check || false == $has )
			return $has;

		if ( !$idx = $this->_filter_build_unique_id($tag, $function_to_check, false) )
			return false;

		foreach ( (array) array_keys($this->filters[$tag]) as $priority ) {
			if ( isset($this->filters[$tag][$priority][$idx]) )
				return $priority;
		}
		return false;
	}

	public function apply_filters($tag, $value) {
		$args = array();
		// Do 'all' actions first
		if ( isset($this->filters['all']) ) {
			$this->current_filter[] = $tag;
			$args = func_get_args();
			$this->_call_all_hook($args);
		}

		if ( !isset($this->filters[$tag]) ) {
			if ( isset($this->filters['all']) )
				array_pop($this->current_filter);
			return $value;
		}

		if ( !isset($this->filters['all']) )
			$this->current_filter[] = $tag;

		// Sort
		if ( !isset( $this->merged_filters[ $tag ] ) ) {
			ksort($this->filters[$tag]);
			$this->merged_filters[ $tag ] = true;
		}

		reset( $this->filters[ $tag ] );

		if ( empty($args) )
			$args = func_get_args();

		do {
			foreach( (array) current($this->filters[$tag]) as $the_ )
				if ( !is_null($the_['function']) ){
					$args[1] = $value;
					$value = call_user_func_array($the_['function'], array_slice($args, 1, (int) $the_['accepted_args']));
				}

		} while ( next($this->filters[$tag]) !== false );

		array_pop( $this->current_filter );

		return $value;
	}

	public function apply_filters_ref_array($tag, $args) {
		// Do 'all' actions first
		if ( isset($this->filters['all']) ) {
			$this->current_filter[] = $tag;
			$all_args = func_get_args();
			$this->_call_all_hook($all_args);
		}

		if ( !isset($this->filters[$tag]) ) {
			if ( isset($this->filters['all']) )
				array_pop($this->current_filter);
			return $args[0];
		}

		if ( !isset($this->filters['all']) )
			$this->current_filter[] = $tag;

		// Sort
		if ( !isset( $this->merged_filters[ $tag ] ) ) {
			ksort($this->filters[$tag]);
			$this->merged_filters[ $tag ] = true;
		}

		reset( $this->filters[ $tag ] );

		do {
			foreach( (array) current($this->filters[$tag]) as $the_ )
				if ( !is_null($the_['function']) )
					$args[0] = call_user_func_array($the_['function'], array_slice($args, 0, (int) $the_['accepted_args']));

		} while ( next($this->filters[$tag]) !== false );

		array_pop( $this->current_filter );

		return $args[0];
	}

	public function add_action($tag, $function_to_add, $priority = 10, $accepted_args = 1) {
		return $this->add_filter($tag, $function_to_add, $priority, $accepted_args);
	}

	public function has_action($tag, $function_to_check = false) {
		return $this->has_filter($tag, $function_to_check);
	}

	public function remove_action( $tag, $function_to_remove, $priority = 10 ) {
		return $this->remove_filter( $tag, $function_to_remove, $priority );
	}

	public function remove_all_actions($tag, $priority = false) {
		return $this->remove_all_filters($tag, $priority);
	}

	public function do_action($tag, $arg = '') {

		if ( ! isset($this->actions) )
			$this->actions = array();

		if ( ! isset($this->actions[$tag]) )
			$this->actions[$tag] = 1;
		else
			++$this->actions[$tag];

		// Do 'all' actions first
		if ( isset($this->filters['all']) ) {
			$this->current_filter[] = $tag;
			$all_args = func_get_args();
			$this->_call_all_hook($all_args);
		}

		if ( !isset($this->filters[$tag]) ) {
			if ( isset($this->filters['all']) )
				array_pop($this->current_filter);
			return;
		}

		if ( !isset($this->filters['all']) )
			$this->current_filter[] = $tag;

		$args = array();
		if ( is_array($arg) && 1 == count($arg) && isset($arg[0]) && is_object($arg[0]) ) // array(&$this)
			$args[] =& $arg[0];
		else
			$args[] = $arg;
		for ( $a = 2; $a < func_num_args(); $a++ )
			$args[] = func_get_arg($a);

		// Sort
		if ( !isset( $this->merged_filters[ $tag ] ) ) {
			ksort($this->filters[$tag]);
			$this->merged_filters[ $tag ] = true;
		}

		reset( $this->filters[ $tag ] );

		do {
			foreach ( (array) current($this->filters[$tag]) as $the_ )
				if ( !is_null($the_['function']) )
					call_user_func_array($the_['function'], array_slice($args, 0, (int) $the_['accepted_args']));

		} while ( next($this->filters[$tag]) !== false );

		array_pop($this->current_filter);
	}

	public function do_action_ref_array($tag, $args) {
		
		if ( ! isset($this->actions) )
			$this->actions = array();

		if ( ! isset($this->actions[$tag]) )
			$this->actions[$tag] = 1;
		else
			++$this->actions[$tag];

		// Do 'all' actions first
		if ( isset($this->filters['all']) ) {
			$this->current_filter[] = $tag;
			$all_args = func_get_args();
			$this->_call_all_hook($all_args);
		}

		if ( !isset($this->filters[$tag]) ) {
			if ( isset($this->filters['all']) )
				array_pop($this->current_filter);
			return;
		}

		if ( !isset($this->filters['all']) )
			$this->current_filter[] = $tag;

		// Sort
		if ( !isset( $merged_filters[ $tag ] ) ) {
			ksort($this->filters[$tag]);
			$merged_filters[ $tag ] = true;
		}

		reset( $this->filters[ $tag ] );

		do {
			foreach( (array) current($this->filters[$tag]) as $the_ )
				if ( !is_null($the_['function']) )
					call_user_func_array($the_['function'], array_slice($args, 0, (int) $the_['accepted_args']));

		} while ( next($this->filters[$tag]) !== false );

		array_pop($this->current_filter);
	}

	public function did_action($tag) {

		if ( ! isset( $this->actions ) || ! isset( $this->actions[$tag] ) )
			return 0;

		return $this->actions[$tag];
	}

	public function current_filter() {
		return end( $this->current_filter );
	}

	function current_action() {
		return $this->current_filter();
	}

	function doing_filter( $filter = null ) {
		if ( null === $filter ) {
			return ! empty( $this->current_filter );
		} 
		return in_array( $filter, $this->current_filter );
	}

	function doing_action( $action = null ) {
		return $this->doing_filter( $action );
	}

	private function _filter_build_unique_id($tag, $function, $priority) {
		static $filter_id_count = 0;

		if ( is_string($function) )
			return $function;

		if ( is_object($function) ) {
			// Closures are currently implemented as objects
			$function = array( $function, '' );
		} else {
			$function = (array) $function;
		}

		if (is_object($function[0]) ) {
			// Object Class Calling
			if ( function_exists('spl_object_hash') ) {
				return spl_object_hash($function[0]) . $function[1];
			} else {
				$obj_idx = get_class($function[0]).$function[1];
				if ( !isset($function[0]->filter_id) ) {
					if ( false === $priority )
						return false;
					$obj_idx .= isset($this->filters[$tag][$priority]) ? count((array)$this->filters[$tag][$priority]) : $filter_id_count;
					$function[0]->filter_id = $filter_id_count;
					++$filter_id_count;
				} else {
					$obj_idx .= $function[0]->filter_id;
				}

				return $obj_idx;
			}
		} else if ( is_string($function[0]) ) {
			// Static Calling
			return $function[0].$function[1];
		}
	}

	public function __call_all_hook($args) {
		reset( $this->filters['all'] );
		do {
			foreach( (array) current($this->filters['all']) as $the_ )
				if ( !is_null($the_['function']) )
					call_user_func_array($the_['function'], $args);

		} while ( next($this->filters['all']) !== false );
	}
}//end class