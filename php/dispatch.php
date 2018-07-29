<?php 

header("Access-Control-Allow-Origin: http://localhost:4200");

// This is for autoloading classes
function loadClass($className) {
	if ( file_exists("classes/$className.php")) {
		require_once "classes/$className.php";
	} elseif ( file_exists("controllers/$className.php")) {
		require_once "controllers/$className.php";
	} else {
		throw new Error($className . " can't be loaded!");
	}
}

spl_autoload_register('loadClass');

// Set up the header first to pass the cross origin request.
require_once 'Routes.php';

?>