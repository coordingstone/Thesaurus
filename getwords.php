<?php

require_once('bootstrap.php');

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

use Src\Controllers\SynonymsController;

$controller = new SynonymsController();
$response = $controller->getWords();

echo $response;