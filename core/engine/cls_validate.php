<?php

class ValidateString {
	public static function AlphaNumericUnderscore($str) 
		{
			return preg_match('/^[a-zA-Z0-9_]+$/',$str);
		}

	public static function Email($str) 
		{
			// return preg_match('/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/',$str);
			return filter_var($str, FILTER_VALIDATE_EMAIL);
		}
}