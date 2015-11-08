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
    public function remove($post)
    {
        $statement = $this->connector->connect()
            ->prepare("DELETE FROM  {$this->tableName} WHERE id = :id");
        $statement->bindValue(':id', $post->getId(), \PDO::PARAM_INT);

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
     * @param $id
     * @return Post|null
     */
    public function find($id)
    {
        $statement = $this->connector->connect()
            ->prepare('SELECT * FROM Post WHERE id = :id LIMIT 1');
        $statement->bindValue(':id', (int) $id);
        $statement->execute();
        $result = $statement->fetchAll();

        if (count($result) === 1) {
            return $this->createObject($result[0]);
        }

        return null;
    }

    /**
     * @return Post
     */
    public function findAll()
    {
        $statement = $this->connector->connect()
            ->prepare("SELECT * FROM {$this->tableName}")
        ;
        $statement->execute();

        return $this->createObject($statement->fetchAll());
    }

    public function findBy($criteria = [])
    {
        $sql = "SELECT * FROM {$this->tableName} WHERE ";
        $lastKey = end(array_keys($criteria));

        foreach ($criteria as $fieldName => $fieldValue) {
            $sql .= "$fieldName = $fieldValue";

            if ($fieldName !== $lastKey) {
                $sql .= ' AND ';
            }
        }

        $statement = $this->connector->connect()
            ->prepare($sql)
        ;
        $statement->execute();

        return $this->createObject($statement->fetchAll());
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
     * @return Post[]
     */
    protected function createObjects(array $fields)
    {
        $objects = [];

        foreach ($fields as $objectFields) {
            $objects[] = $this->createObject($objectFields);
        }

        return $objects;
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