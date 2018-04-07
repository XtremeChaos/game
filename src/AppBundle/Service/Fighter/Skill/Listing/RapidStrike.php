<?php

namespace AppBundle\Service\Fighter\Skill\Listing;

use AppBundle\Entity\Attack;

class RapidStrike implements Skill{
    private $chance = 10;
    private $type = 'attack';
    private $name = 'Rapid Strike';

    public function getType() : string
    {
        return $this->type;
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function getChance() : float
    {
        return $this->chance;
    }

    public function run( Attack $attack) : void
    {
        $attack->setMultiplier($attack->getMultiplier() + 1);
    }

}

