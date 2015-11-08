<?php

require_once __DIR__ . '/../config/autoload.php';

use Layer\Connector\ConnectorClass;
use Layer\Manager\CategoryManager;
use Layer\Manager\PostManager;
use Phroute\Phroute\RouteCollector;
use Phroute\Phroute\Dispatcher;
use Controllers\PostController;

$connector = new ConnectorClass($config['db_name'], $config['db_user'], $config['db_password']);
$categoryManager = new CategoryManager($connector);
$postManager = new PostManager($connector);
$postManager->setCategoryManager($categoryManager);

$loader = new \Twig_Loader_Filesystem(__DIR__ . '/../src/View');
$twig = new \Twig_Environment($loader, [
    'cache' => false,
]);

$router = new RouteCollector();
$router->any('/', function() use($postManager, $categoryManager, $twig) {
    $controller = new PostController($postManager, $categoryManager,$twig);

    return $controller->indexAction();
});

$router->any('/show/{id}', function($id)  use($postManager, $categoryManager, $twig) {
    $controller = new PostController($postManager, $categoryManager, $twig);

    return $controller->showAction($id);
});

$router->any('/create', function()  use($postManager, $categoryManager, $twig) {
    $controller = new PostController($postManager, $categoryManager, $twig);

    return $controller->createAction();
});

$dispatcher = new Dispatcher($router->getData());
$response = $dispatcher->dispatch($_SERVER['REQUEST_METHOD'], parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

echo $response;