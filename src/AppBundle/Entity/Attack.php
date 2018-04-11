<?php

namespace AppBundle\Entity;

class Attack{
    private $strength;
    private $multiplier;

    public function __construct(float $strength = null, float $multiplier = 1){
        $this->setStrength($strength);
        $this->setMultiplier($multiplier);
    }

    public function setStrength(float $strength = null) : void {
        $this->strength = $strength;
    }

    public function getStrength() : float {
        return $this->strength;
    }

    public function setMultiplier(float $multiplier = null ) : void {
        $this->multiplier = $multiplier;
    }

    public function getMultiplier() : float {
        return $this->multiplier;
    }

}
