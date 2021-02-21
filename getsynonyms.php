<?

require_once('bootstrap.php');

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

use Src\Controllers\SynonymsController;

$request = file_get_contents('php://input');

$controller = new SynonymsController();
$result = $controller->getSynonyms($request);

echo $result;