<?php

namespace Layer\Manager;


use Entity\Category;
use Entity\Post;
use Layer\Connector\ConnectorClass;

class PostManager extends Manager
{
    public $tableName = 'Post';
    /**
     * @var categoryManager
     */
    private $categoryManager;

    /**
     * @param categoryManager $categoryManager
     * @return $this
     */
    public function setCategoryManager($categoryManager)
    {
        $this->categoryManager = $categoryManager;

        return $this;
    }

    /**
     * @param Post $post
     * @return bool
     */
    public function insert($post)
    {
        $statement = $this->connector->connect()
            ->prepare(
                "INSERT INTO {$this->tableName}
                (title, body) VALUES(:title, :body, :category_id)"
            );
        $statement->bindValue(':title', $post->getTitle());
        $statement->bindValue(':body', $post->getBody());
        $statement->bindValue(':category_id', $post->getCategory());

        return $statement->execute();
    }

    /**
     * @param Post $post
     * @return bool
     */
    public function update($post)
    {
        $statement = $this->connector->connect()
            ->prepare(
                "UPDATE {$this->tableName}
                 SET title=:title, body=:body, category_id=:category_id
                 WHERE id=:id"
            );
        $statement->bindValue(':id', $post->getId());
        $statement->bindValue(':title', $post->getTitle());
        $statement->bindValue(':body', $post->getBody());
        $statement->bindValue(':category_id', $post->getCategory());

        return $statement->execute();
    }

    /**
     *
     */
    public function createTable()
    {
        $statement = $this->connector->connect();
        $sql = "CREATE TABLE `$this->tableName` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `title` text CHARACTER SET utf8 NOT NULL,
              `body` text CHARACTER SET utf8 NOT NULL,
              `category_id` int(11),
              PRIMARY KEY (`id`),
              UNIQUE KEY `id` (`id`)
            ) ENGINE=InnoDB  DEFAULT CHARSET=utf8";
        $statement->exec($sql);
    }

    /**
     * @param Category $category
     * @return Post
     */
    public function findByCategory(Category $category)
    {
        $statement = $this->connector->connect()
            ->prepare(
                "SELECT* FROM {$this->tableName} LEFT JOIN {$this->categoryManager->tableName}
                 ON {$this->tableName}.category_id={$this->categoryManager->tableName}.id
                 WHERE {$this->categoryManager->tableName}.id = :category_id"
            );
        $statement->bindValue(':category_id', $category->getId());
        $statement->execute();

        return $this->createObjects($statement->fetchAll());
    }

    /**
     * @param array $fields
     * @return Post
     */
    protected function createObject(array $fields)
    {
        $post = new Post();
        $post
            ->setId($fields['id'])
            ->setTitle($fields['title'])
            ->setBody($fields['body'])
            ->setCategory($fields['category_id'])
        ;

        return $post;
    }
}