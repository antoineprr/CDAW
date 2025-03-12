<?php
require "bootstrap.php";
require "controller/LoginController.php";
// https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Access-Control-Allow-Origin
header("Access-Control-Allow-Origin: *");

header("Content-Type: application/json; charset=UTF-8");

// https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Access-Control-Allow-Methods
header("Access-Control-Allow-Methods: GET,POST,PUT,DELETE");

header("Access-Control-Max-Age: 3600"); // Maximum number of seconds the results can be cached.

// https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Access-Control-Allow-Headers
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    header("HTTP/1.1 200 OK");
    exit;
}

function getRoute($url){

    $url = trim($url, '/');
    $urlSegments = explode('/', $url);
    
    $scheme = ['controller', 'params'];
    $route = [];

    foreach ($urlSegments as $index => $segment){
        if ($scheme[$index] == 'params'){
            $route['params'] = array_slice($urlSegments, $index);
            break;
        } else {
            $route[$scheme[$index]] = $segment;
        }
    }

    return $route;
}

//$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);
$route = getRoute($uri);

$requestMethod = $_SERVER["REQUEST_METHOD"];

$controllerName = $route['controller'];
$userId = null;

switch($controllerName) {
    case 'users' :
        // GET api.php?/users
        // POST api.php?/users
        $controller = new UsersController($requestMethod);
        break;
    case 'user' :
        // DELETE api.php?/user/{id}
        // GET api.php?/user/{id}
        // PUT api.php?/user/{id}
        if (isset($route['params']) && !empty($route['params'])) {
            $userId = (int)$route['params'][0];
            $controller = new UsersController($requestMethod, $userId);
        } else {
            header("HTTP/1.1 400 Bad Request");
            echo json_encode(['message' => 'User ID is required']);
            exit();
        }
        break;
    case 'login' :
        $controller = new LoginController($requestMethod);
        break;
    default :
        header("HTTP/1.1 404 Not Found");
        exit();
}

$controller->processRequest();