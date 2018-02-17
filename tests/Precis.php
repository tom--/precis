<?php

namespace spinitron\precis\tests;

class Precis extends \spinitron\precis\Precis
{
    public static function derivedProperty($char)
    {
        return parent::derivedProperty($char);
    }

    public static function getPrecisProperty($string, $pos = 0)
    {
        return parent::getPrecisProperty($string, $pos);
    }
}
