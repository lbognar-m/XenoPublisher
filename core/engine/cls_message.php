<?php

class Message {
	public static function set($message = NULL, $type = 'status', $repeat = TRUE) {
		if ($message) {
			if (!isset($_SESSION['messages'][$type])) {
				$_SESSION['messages'][$type] = array();
			}

			if ($repeat || !in_array($message, $_SESSION['messages'][$type])) {
				$_SESSION['messages'][$type][] = $message;
			}
		}

		return isset($_SESSION['messages']) ? $_SESSION['messages'] : NULL;
	}
	
	public static function get($type = NULL, $clear_queue = TRUE) {
		if ($messages = self::set()) {
			if ($type) {
				if ($clear_queue) {
					unset($_SESSION['messages'][$type]);
				}
				if (isset($messages[$type])) {
					return array($type => $messages[$type]);
				}
			}
			else {
				if ($clear_queue) {
					unset($_SESSION['messages']);
				}
				return $messages;
			}
		}
		return array();
	}
	
	public static function render() {
		$display = array(
			'status',
			'error',
			'warning',
		);
		$output = '';

		$status_heading = array(
			'status' => 'Status message',
			'error' => 'Error message',
			'warning' => 'Warning message',
		);
		foreach ($display as $class) {
			foreach (self::get($class) as $type => $messages) {
				$output .= "<div class=\"messages $type\">\n";
				if (!empty($status_heading[$type])) {
					$output .= '<h2 class="element-invisible">' . $status_heading[$type] . "</h2>\n";
				}
				if (count($messages) > 1) {
					$output .= " <ul>\n";
					foreach ($messages as $message) {
						$output .= '	<li>' . $message . "</li>\n";
					}
					$output .= " </ul>\n";
				}
				else {
					$output .= reset($messages);
				}
				$output .= "</div>\n";
			}
		}
		return $output;
	}
}