<?php

namespace AppBundle\Service;

class GameUtils
{
    public static function checkProbability(float $value): bool
    {
        if( $value == 0 ){
            return false;
        }
        return mt_rand(1, 100) <= $value;
    }
}