<?php

class XQuery {

	public static function get_setting( $name = false ) {
		if ( !$name ) {
			return false;
		}
		$setting = DB::queryFirstField( "SELECT setting_value FROM %b WHERE setting_name=%s", 'settings', $name );
		return $setting;
	}
	
	public static function validate_secret() {
		if ( !empty( $_GET['secret'] ) && $_GET['secret'] == DB::queryOneField( 'setting_value', "SELECT * FROM %b WHERE setting_name=%s", 'settings', 'secret' )) {
			return true;
		}
		return false;
	}
}