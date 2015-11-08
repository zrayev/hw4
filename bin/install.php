<?php

require_once __DIR__ . '/../config/autoload.php';

use Layer\Connector\ConnectorClass;
use Layer\Manager\PostManager;
use Entity\Post;

$connector = new ConnectorClass($config['db_name'], $config['db_user'], $config['db_password']);
$connector ->createDataBase($config['db_name'], $config['db_user'], $config['db_password'],$config['host']);
$postManager = new PostManager($connector);
$postManager->createTable();
$post = new Post();
$post
    ->setTitle('Перший пост')
    ->setBody('Текст посту 1.')
;
$postManager->insert($post);
$post = new Post();
$post
    ->setTitle('Другий пост')
    ->setBody('Текст посту 2.')
;
$postManager->insert($post);
$post = new Post();
$post
    ->setTitle('Третій пост')
    ->setBody('Текст посту 3.')
;
$postManager->insert($post);

