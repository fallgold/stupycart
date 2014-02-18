<?php

// Version
define('VERSION', '1.5.5.1');

use Phalcon\Mvc\Application;

error_reporting(E_ALL);

try {
	$config = require __DIR__ . "/../config/config.php";
	require __DIR__ . '/../config/services.php';

	require __DIR__ . '/../libs/std.php';
	require __DIR__ . '/../libs/helper/utf8.php';

	$application = new Application($di);
	$application->registerModules($config['modules']);

	echo $application->handle()->getContent();

} catch (Phalcon\Exception $e) {
	echo $e->getMessage();
} catch (PDOException $e){
	echo $e->getMessage();
}
