<?php
require "scss.inc.php";

$scss = new scssc();
$scss->setFormatter("scss_formatter_compressed");
$scss->setImportPaths("stylesheet/");
$scss->addImportPath("stylesheet/lib");

$server = new scss_server("stylesheet", null, $scss);
$server->serve();