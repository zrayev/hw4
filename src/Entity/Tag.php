<?php
/**
 * Created by PhpStorm.
 * User: dmitry
 * Date: 09.11.15
 * Time: 19:49
 */

namespace Entity;


class Tag
{
    use IdentifiableEntityTrait;

    /**
     * @var string
     */
    private $title;

    /**
     * @var array
     */
    private $posts;

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return array
     */
    public function getPosts()
    {
        return $this->posts;
    }

    /**
     * @param array $posts
     * @return $this
     */
    public function setPosts($posts)
    {
        $this->posts = $posts;

        return $this;
    }

    /**
     * @param string $title
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }
}
