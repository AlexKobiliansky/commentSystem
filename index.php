<?php
require_once('autoloader.php');
require_once __DIR__ . '/configuration.php';

session_start();

$connector = new Repositories\Connector(
    $configuration['database'],
    $configuration['user'],
    $configuration['password']
);

$controllerName = isset($_GET['controller']) ? $_GET['controller'] :'comments';
$controllerName = ucfirst($controllerName) . 'Controller';
$controllerName = 'Controllers\\' . $controllerName;

$connector = new Repositories\Connector(
    $configuration['database'],
    $configuration['user'],
    $configuration['password']
);

$controller = new $controllerName($connector);

$actionName = isset($_GET['action']) ? $_GET['action'] : 'index';
$actionName = $actionName . 'Action';

$security = new \Controllers\SecurityController($connector);
if (!$security->checkAvailableAction($controllerName, $actionName)) {
    $security->checkAuthAction();
}

$response = $controller->$actionName();

echo $response;
