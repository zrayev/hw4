<?php

namespace Entity;


class Category
{
    use IdentifiableEntityTrait;

    private $name;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string
     */
    public function setName($name)
    {
        $this->name = $name;
    }

}