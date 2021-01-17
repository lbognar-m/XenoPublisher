<?php
/**
 * Root directory of XenoPublisher installation.
 */
define( 'XENO_ROOT', __DIR__ );
define( 'XENO_CORE', XENO_ROOT . '/core' );
define( 'XENO_SITE', XENO_ROOT . '/site' );
define( 'XENO_VAULT', XENO_ROOT . '/vault' );
define( 'XENO_URL', ( empty( $_SERVER['HTTPS'] ) ? 'http' : 'https' ) . '://' . $_SERVER['SERVER_NAME'] . ( ( empty($_SERVER['HTTPS']) == 'http' && $_SERVER['SERVER_PORT'] == 80 || !empty($_SERVER['HTTPS'] ) == 'https' && $_SERVER['SERVER_PORT'] == 443 ) ? '' : ':' . $_SERVER['SERVER_PORT'] ) . ( empty( substr(__DIR__, strlen( $_SERVER[ 'DOCUMENT_ROOT' ] ) ) ) ? '' : '/' . substr( __DIR__, strlen( $_SERVER[ 'DOCUMENT_ROOT' ] ) ) ) . '/');
//	
include_once ( XENO_ROOT . '/core/engine/xeno-load.php' );
include_once ( XENO_ROOT . '/core/engine/xeno-prepare.php' );
include_once ( XENO_ROOT . '/core/engine/xeno-render.php' );

//	testing stuff
include_once ( XENO_ROOT . '/testingMVC.php' );