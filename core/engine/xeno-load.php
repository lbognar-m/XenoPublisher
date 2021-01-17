<?php

/**
* Start Up Xeno
*
* Include files needed for every boot-up
* Start a secure session
* Build up and prepare the main object for the current page
* Include all plugin and theme *function* files
* Continue in xeno-prepare.php
*/

include_once ( XENO_SITE . '/settings.php' );
$config = array_key_exists($_SERVER['SERVER_NAME'],$confgroup) ? $confgroup[$_SERVER['SERVER_NAME']] : $confgroup['default'];

include_once ( XENO_CORE . '/engine/cls_db.php' );
include_once ( XENO_CORE . '/engine/cls_xeno.php' );
include_once ( XENO_CORE . '/engine/cls_hook.php' );
include_once ( XENO_CORE . '/engine/cls_user.php' );
include_once ( XENO_CORE . '/engine/cls_page.php' );
include_once ( XENO_CORE . '/engine/cls_timer.php' );
include_once ( XENO_CORE . '/engine/cls_theme.php' );
include_once ( XENO_CORE . '/engine/cls_query.php' );
include_once ( XENO_CORE . '/engine/cls_assets.php' );
include_once ( XENO_CORE . '/engine/cls_router.php' );
include_once ( XENO_CORE . '/engine/lib_common.php' );
include_once ( XENO_CORE . '/engine/cls_message.php' );
include_once ( XENO_CORE . '/engine/cls_validate.php' );

//	conditional, move into context later
include_once ( XENO_CORE . '/engine/cls_minifyjs.php' );
include_once ( XENO_CORE . '/engine/cls_minifycss.php' );
include_once ( XENO_CORE . '/engine/lib_shortcodes.php' );
include_once ( XENO_CORE . '/engine/lib_formatting.php' );
include_once ( XENO_CORE . '/engine/cls_streamfile.php' );

define( 'VERSION',			'1.0');
define( 'DB_ENCODING',		'utf8' );
define( 'XENO_STATUS',		$config['dev'] );
define( 'DB_HOST',			$config['host'] );
define( 'DB_PORT',			$config['port'] );
define( 'DB_TABLE_PREFIX',	$config['prefix'] );
define( 'DB_USER',			$config['username'] );
define( 'DB_PASS',			$config['password'] );
define( 'DB_NAME',			$config['database'] );

DB::$user		=	DB_USER;
DB::$password	=	DB_PASS;
DB::$dbName		=	DB_NAME;
DB::$host		=	DB_HOST;
DB::$port		=	DB_PORT;
DB::$encoding	=	DB_ENCODING;

sec_session_start();

$timing = new Timer("<br />\n");
$timing->start();
$Xeno = new Xeno;


//	include all php oo and procedural files
include_once ( XENO_CORE . '/example/plugins.php' );