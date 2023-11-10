<?php
/**
 * Here, we are connecting '/' (base path) to controller called 'Pages',
 * its action called 'display', and we pass a param to select the view file
 * to use (in this case, /app/views/pages/home.thtml)...
 */
	$Route->connect('/', array('controller' => 'usuarios', 'action' => 'index'));
/**
 * ...and connect the rest of 'Pages' controller's urls.
 */
	$Route->connect('/pages/*', array('controller' => 'pages', 'action' => 'display'));
/**
 * Then we connect url '/test' to our test controller. This is helpfull in
 * developement.
 */
	$Route->connect('/tests', array('controller' => 'tests', 'action' => 'index'));
	$Route->connect('/panel_freites', array('controller' => 'script_correciones', 'action' => 'index'));
?>
