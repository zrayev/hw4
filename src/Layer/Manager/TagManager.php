<?php

namespace Layer\Manager;

use Entity\Post;
use Entity\Tag;

class TagManager extends Manager
{
    /**
     * @var string
     */
    public $tableName = 'Tag';

    /**
     * @param Tag $tag
     * @return bool
     */
    public function insert($tag)
    {
        $statement = $this->connector->connect()
            ->prepare(
                "INSERT INTO {$this->tableName}
                (title) VALUES(:title)"
            );
        $statement->bindValue(':title', $tag->getTitle());

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
                 SET title=:title,
                 WHERE id=:id"
            );
        $statement->bindValue(':id', $post->getId());
        $statement->bindValue(':title', $post->getTitle());

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
              PRIMARY KEY (`id`),
              UNIQUE KEY `id` (`id`)
            ) ENGINE=InnoDB  DEFAULT CHARSET=utf8";
        $statement->exec($sql);
    }

    /**
     * @param array $fields
     * @return Tag
     */
    public function createObject(array $fields)
    {
        $tag = new Tag();
        $tag
            ->setId(isset($fields['id']) ? $fields['id'] : '')
            ->setTitle($fields['title'])
        ;

        return $tag;
    }
}
