<?php

namespace Layer\Manager;

use Entity\Category;

class CategoryManager extends Manager
{
    protected $tableName = 'Category';

    /**
     * @param Category $post
     * @return bool
     */
    public function insert($post)
    {
        $statement = $this->connector->connect()
            ->prepare(
                "INSERT INTO {$this->tableName}
                (name) VALUES(:name)"
            );
        $statement->bindValue(':name', $post->getName());

        return $statement->execute();
    }

    /**
     * @param Category $post
     * @return bool
     */
    public function update($post)
    {
        $statement = $this->connector->connect()
            ->prepare(
                "UPDATE {$this->tableName}
                 SET name=:name
                 WHERE id=:id"
            );
        $statement->bindValue(':id', $post->getId());
        $statement->bindValue(':name', $post->getName());

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
              `name` text CHARACTER SET utf8 NOT NULL,
              PRIMARY KEY (`id`),
              UNIQUE KEY `id` (`id`)
            ) ENGINE=InnoDB  DEFAULT CHARSET=utf8";
        $statement->exec($sql);
    }



    /**
     * @param array $fields
     * @return Category
     */
    protected function createObject(array $fields)
    {
        $post = new Category();
        $post
            ->setId($fields['id'])
            ->setName($fields['name'])
        ;

        return $post;
    }
}