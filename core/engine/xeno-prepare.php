<?php

/**
* Prepare the page data for the view
*
* Run the init hook before everything
* Parse url request and decide what to do
* Continue in xeno-render.php
*/

HOOK::do_action( 'xeno_init', $Xeno );

$Xeno->Router->execute($Xeno);

$Xeno->Page->process($Xeno);

$Xeno->Theme->setTheme($Xeno->Router->theme);

HOOK::do_action( 'xeno_prerender', $Xeno );