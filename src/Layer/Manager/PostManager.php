<?php

namespace Layer\Manager;


use Entity\Post;

class PostManager extends Manager
{
    protected $tableName = 'Post';

    /**
     * @param Post $post
     * @return bool
     */
    public function insert($post)
    {
        $statement = $this->connector->connect()
            ->prepare(
                "INSERT INTO {$this->tableName}
                (title, body) VALUES(:title, :body)"
            );
        $statement->bindValue(':title', $post->getTitle());
        $statement->bindValue(':body', $post->getBody());

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
                 SET title=:title, body=:body
                 WHERE id=:id"
            );
        $statement->bindValue(':id', $post->getId());
        $statement->bindValue(':title', $post->getTitle());
        $statement->bindValue(':body', $post->getBody());

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
              PRIMARY KEY (`id`),
              UNIQUE KEY `id` (`id`)
            ) ENGINE=InnoDB  DEFAULT CHARSET=utf8";
        $statement->exec($sql);
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
        ;

        return $post;
    }

}