<?php

use Thesaurus\Controllers\SynonymsController;

require_once('../bootstrap.php');

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

try {
    /** @var SynonymsController $controller */
    $controller = $container->get('Thesaurus\Controllers\SynonymsController');
} catch (Exception $e) {
    http_response_code(500);
    echo "Something went wrong";
}
$response = $controller->getWords();

echo $response;