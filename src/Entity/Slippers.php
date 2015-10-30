<?php
namespace Entity;

use Entity\EntityFootwear;

/**
 * Class Slippers
 * @package Entity
 */
class Slippers extends EntityFootwear
{
    /**
     * @var string
     */
    private $type;

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function printScreen()
    {
        $str = 'Name: ' . $this->getName() . "<br>\n";
        $str .= 'Size: ' . $this->getSize() . "<br>\n";
        $str .= 'Material: ' . $this->getMaterial() . "<br>\n";
        $str .= 'Type: ' . $this->getType() . "<br>\n";
        return $str;
    }
}