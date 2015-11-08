<?php

namespace Entity;

use Layer\Manager\EntityLoader;

class Post
{
    use IdentifiableEntityTrait;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $body;

    /**
     * @var Category
     */
    private $category;

    /**
     * @var EntityLoader
     */
    private $categoryLoader;

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
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

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param string $body
     * @return $this
     */
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * @return Category
     */
    public function getCategory()
    {
        if (!$this->category) {
            $this->category = $this->categoryLoader->get();
        }

        return $this->category;
    }

    /**
     * @param Category $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }

    /**
     * @param EntityLoader $categoryLoader
     */
    public function setCategoryLoader($categoryLoader)
    {
        $this->categoryLoader = $categoryLoader;
    }
}