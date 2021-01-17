<?php

function display_dashboard($params, $Xeno) {
	$Xeno->Page->title = 'DASHBOARD HOME PAGE TITLE';
	$Xeno->Page->content['params'] = $params;
	debug( '=======================YOUR DASHBOARD IS SHOWING=============================', null,true );
	debug( $params, null,true );
}