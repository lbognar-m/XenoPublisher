<?php

/**
 * Outputs debug information.
 */
function debug( $data, $label = NULL, $print_r = FALSE ) {
	static $row;
	$row = ( $row == 'odd' ) ? 'even' : 'odd';
	$debuginfo = debug_backtrace();
	$caller = print_r( $debuginfo[0], true );
	$callerfile = $debuginfo[0]['file'];
	$callerline = $debuginfo[0]['line'];
	$string = check_plain( $print_r ? print_r( $data, TRUE ) : var_export( $data, TRUE ) );

	// Display values with pre-formatting to increase readability.
	$string = '<pre class="debug ' . $row . '">Called from: ' . $callerfile . ' Line: ' . $callerline . '<hr />' . $string . '</pre>';

	print( trim( $label ? "$label: $string" : $string ) );
}

/**
 * Encodes special characters in a plain-text string for display as HTML.
 *
 * Also validates strings as UTF-8 to prevent cross site scripting attacks on
 * Internet Explorer 6.
 */
function check_plain( $text ) {
	return htmlspecialchars( trim( $text ), ENT_QUOTES, 'UTF-8' );
}

//	start a secure session
function sec_session_start() {
	$session_name = 'sec_session_id';   // Set a custom session name
	$secure = false;	//	set true on https
	// This stops JavaScript being able to access the session id.
	$httponly = true;
	// Forces sessions to only use cookies.
	if (ini_set('session.use_only_cookies', 1) === false) {
	   debug( 'safe session could not be initiated. cookies are not enough.', null,true );
		exit();
	}
	// Gets current cookies params.
	$cookieParams = session_get_cookie_params();
	session_set_cookie_params($cookieParams["lifetime"],
		$cookieParams["path"], 
		$cookieParams["domain"], 
		$secure,
		$httponly);
	// Sets the session name to the one set above.
	session_name($session_name);
	session_start();			// Start the PHP session 
	session_regenerate_id(true);	// regenerated the session, delete the old one. 
}

//	detect mobile devices
function is_mobile() {
	return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up.browser|up.link|webos|wos)/i", $_SERVER['HTTP_USER_AGENT']);
}

/**
 * Returns the requested URL path of the page being viewed.
 *
 * Examples:
 * - http://example.com/node/306 returns "node/306".
 * - http://example.com/drupalfolder/node/306 returns "node/306" while
 *   base_path() returns "/drupalfolder/".
 * - http://example.com/path/alias (which is a path alias for node/306) returns
 *   "path/alias" as opposed to the internal path.
 * - http://example.com/index.php returns an empty string (meaning: front page).
 * - http://example.com/index.php?page=1 returns an empty string.
 *
 * @return
 *   The requested Drupal URL path.
 *
 * @see current_path()
 */
function request_path() {
	static $path;

	if (isset($path)) {
		return $path;
	}

	if (isset($_GET['q']) && is_string($_GET['q'])) {
		$path = $_GET['q'];
	}
	elseif (isset($_SERVER['REQUEST_URI'])) {
		$request_path = strtok($_SERVER['REQUEST_URI'], '?');
		$base_path_len = strlen(rtrim(dirname($_SERVER['SCRIPT_NAME']), '\/'));
		$path = substr(urldecode($request_path), $base_path_len + 1);
		if ($path == basename($_SERVER['PHP_SELF'])) {
			$path = '';
		}
	}
	else {
		$path = '';
	}
	$path = trim($path, '/');

	return $path;
}