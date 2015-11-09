<?php

namespace Layer\Manager;

use Entity\Category;
use Entity\Post;
use Entity\Tag;

class PostManager extends Manager
{
    /**
     * @var string
     */
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
     * @param $id
     * @return object|null
     */
    public function find($id)
    {
        $statement = $this->connector->connect()
            ->prepare("SELECT * FROM {$this->tableName} WHERE id = :id LIMIT 1")
        ;
        $statement->bindValue(':id', (int) $id);
        $statement->execute();
        $result = $statement->fetchAll();

        if (count($result) === 1) {
            $result[0]['tag_ids'] = $this->getTagIds($id);

            return $this->createObject($result[0]);
        }

        return null;
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
                (title, body, category_id) VALUES(:title, :body, :category_id)"
            );
        $statement->bindValue(':title', $post->getTitle());
        $statement->bindValue(':body', $post->getBody());
        $statement->bindValue(':category_id', $post->getCategory());
        $statement->execute();

        foreach ($post->getTags() as $tag) {
            $statement = $this->connector->connect()
            ->prepare(
                "INSERT INTO post_tag
                (post_id, tag_id) VALUES(:post, :tag)"
            );
            $statement->bindValue(':post', $post->getTitle());
            $statement->bindValue(':tag', $tag);
            $statement->execute();
        }
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
        $statement->execute();

        $statement = $this->connector->connect()
            ->prepare('SELECT * FROM post_tag WHERE post_id=:post_id')

        ;
        $tags = $this->getTagIds($post->getId());

        foreach ($post->getTags() as $tag) {
            if (!in_array($tag, $tags)) {
                $statement = $this->connector->connect()
                    ->prepare(
                        "INSERT INTO post_tag
                        (post_id, tag_id) VALUES(:post, :tag)"
                    );
                $statement->bindValue(':post', $post->getId());
                $statement->bindValue(':tag', $tag);
                $statement->execute();
            }
        }

        foreach ($tags as $tag) {
            if (!in_array($tag, $post->getTags())) {
                $statement = $this->connector->connect()
                    ->prepare(
                        "DELETE FROM post_tag WHERE post_id = :post_id
                         AND tag_id = :tag_id"
                    );
                $statement->bindValue(':post_id', $post->getId());
                $statement->bindValue(':tag_id', $tag);
                $statement->execute();
            }
        }
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

        $sql = "CREATE TABLE `post_tag` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `post_id` int(11),
              `tag_id` int(11),
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
     * @param Tag $tag
     * @return Tag
     */
    public function findByTag(Tag $tag)
    {
        $statement = $this->connector->connect()
            ->prepare(
                "SELECT p.id, p.title, p.body, p.category_id
                FROM {$this->tableName} AS p
                LEFT JOIN `post_tag` AS pt ON p.id = pt.post_id
                LEFT JOIN `Tag` AS t ON t.id = pt.tag_id
                WHERE t.id = :tag_id"
            );
        $statement->bindValue(':tag_id', $tag->getId());
        $statement->execute();
        $posts = [];

        foreach ($statement->fetchAll() as $post) {
            $post['tag_ids'] = $this->getTagIds($post['id']);
            $posts[] = $this->createObject($post);
        }

        return $posts;
    }

    /**
     * @param array $fields
     * @return Post
     */
    public function createObject(array $fields)
    {
        $post = new Post();
        $post
            ->setId(isset($fields['id']) ? $fields['id'] : '')
            ->setTitle($fields['title'])
            ->setBody($fields['body'])
            ->setCategory($fields['category_id'])
            ->setTags($fields['tag_ids'])
        ;

        return $post;
    }

    /**
     * @param $postId
     * @return array
     */
    private function getTagIds($postId)
    {
        $statement = $this->connector->connect()
            ->prepare("SELECT * FROM post_tag WHERE post_id = :id");
        $statement->bindValue(':id', (int) $postId);
        $statement->execute();
        $tags = [];

        foreach ($statement->fetchAll() as $tag) {
            $tags = $tag['tag_id'];
        }

        return $tags;
    }
}