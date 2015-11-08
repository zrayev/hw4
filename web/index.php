<?php

require_once __DIR__ . '/../config/autoload.php';

use Layer\Connector\ConnectorClass;
use Layer\Manager\CategoryManager;
use Layer\Manager\PostManager;

$connector = new ConnectorClass($config['db_name'], $config['db_user'], $config['db_password']);
$categoryManager = new CategoryManager($connector);
$postManager = new PostManager($connector);
$postManager->setCategoryManager($categoryManager);

$post = $postManager->find(1);
var_dump($post);
