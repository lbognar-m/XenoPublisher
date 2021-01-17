<?php 

function display_home($params, $Xeno) {
	$Xeno->Page->title = 'HOME PAGE TITLE';
	$Xeno->Page->content['params'] = $params;
	debug( '=======================YOUR HOME IS SHOWING=============================', null, true );
	debug( $params, null, true );
}

function display_admin_menus($params, $Xeno) {
	$Xeno->Page->title = 'ADMIN MENU TITLE';
	$Xeno->Page->content['params'] = $params;
	debug( '=======================YOUR ADMIN MENU IS SHOWING=============================', null, true );
	debug( $params, null, true );
}