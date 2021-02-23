<?php

require_once('../bootstrap.php');

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

$method = $_SERVER['REQUEST_METHOD'];

/** @var Thesaurus\Controllers\SynonymsController $controller */
try {
    $controller = $container->get('Thesaurus\Controllers\SynonymsController');
} catch (Exception $e) {
    http_response_code(500);
    echo "Something went wrong";
}

switch ($method) {
    case 'POST':
        try {
            $request = file_get_contents('php://input');
            echo $controller->addSynonyms($request);
        } catch (Exception $e) {
            http_response_code(500);
            echo "Something went wrong";
        }
        break;
    case 'GET':
        try {
            echo $controller->getSynonyms($_GET["word"]);
        } catch (Exception $e) {
            http_response_code(500);
            echo "Something went wrong";
        }
        break;
    default:
        http_response_code(405);
        break;
}