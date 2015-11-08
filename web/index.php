<?php

require_once __DIR__ . '/../config/autoload.php';

use Layer\Connector\ConnectorClass;
use Layer\Manager\PostManager;

$connector = new ConnectorClass($config['db_name'], $config['db_user'], $config['db_password']);
$postManager = new PostManager($connector);

$post = $postManager->find(1);
var_dump($post);
exit;