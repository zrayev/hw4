<?php
namespace Entity;
use Entity\EntityFootwear;

/**
 * Class Shoes
 * @package Footwear
 */
class Shoes extends EntityFootwear
{
    /**
     * @var string
     */
    private $season;
    /**
     * @var string
     */
    private $shoelace;
    /**
     * @return string
     */
    public function getSeason()
    {
        return $this->season;
    }
    /**
     * @param $season
     * @return $this
     */
    public function setSeason($season)
    {
        $this->season = $season;
        return $this;
    }
    /**
     * @return string
     */
    public function getShoelace()
    {
        return $this->shoelace;
    }
    /**
     * @param $shoelace
     * @return $this
     */
    public function setShoelace($shoelace)
    {
        $this->shoelace = $shoelace;
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
        $str .= 'Season: ' . $this->getSeason() . "<br>\n";
        $str .= 'Shoelace: ' . $this->getShoelace() . "<br>\n";
        return $str;
    }
}