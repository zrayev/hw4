<?php

require_once __DIR__ . '/../config/autoload.php';

use Layer\Connector\ConnectorClass;
use Layer\Manager\PostManager;
use Layer\Manager\CategoryManager;
use Layer\Manager\TagManager;
use Entity\Post;
use Entity\Category;
use Entity\Tag;

$connector = new ConnectorClass($config['db_name'], $config['db_user'], $config['db_password']);
$connector ->createDataBase($config['db_name'], $config['db_user'], $config['db_password'],$config['host']);
$categoryManager = new CategoryManager($connector);
$categoryManager->createTable();
$category = new Category();
$category->setName('Категорія 1');
$categoryManager->insert($category);

$category = new Category();
$category->setName('Категорія 2');
$categoryManager->insert($category);

$tagManager = new TagManager($connector);

$tagManager->createTable();
$tag = new Tag();
$tag
    ->setTitle('Перший тег')
;
$tagManager->insert($tag);

$tagManager->createTable();
$tag1 = new Tag();
$tag1
    ->setTitle('Другий тег')
;
$tagManager->insert($tag1);

$tagManager->createTable();
$tag2 = new Tag();
$tag2
    ->setTitle('Третій тег')
;
$tagManager->insert($tag2);

$tagManager->createTable();
$tag3 = new Tag();
$tag3
    ->setTitle('Четвертий тег')
;
$tagManager->insert($tag3);

$postManager = new PostManager($connector);
$postManager->createTable();
$post = new Post();
$post
    ->setTitle('Перший пост')
    ->setBody('Текст посту 1.')
    ->setCategory($category->getId())
;
$postManager->insert($post);
$post1 = new Post();
$post1
    ->setTitle('Другий пост')
    ->setBody('Текст посту 2.')
    ->setCategory($category->getId())
;
$postManager->insert($post1);
$post2 = new Post();
$post2
    ->setTitle('Третій пост')
    ->setBody('Текст посту 3.')
    ->setCategory($category->getId())
;
$postManager->insert($post2);

$post = $postManager->find(1)
    ->setTags([$tagManager->find(1)->getId(), $tagManager->find(2)->getId()]);
$postManager->update($post);

$post = $postManager->find(2)
    ->setTags([$tagManager->find(1)->getId(), $tagManager->find(3)->getId()]);
$postManager->update($post);
$post = $postManager->find(3)
    ->setTags([$tagManager->find(3)->getId(), $tagManager->find(4)->getId()]);
$postManager->update($post);
