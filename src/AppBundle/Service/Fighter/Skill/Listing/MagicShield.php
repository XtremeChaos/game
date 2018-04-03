<?php

namespace AppBundle\Service\Fighter\Skill\Listing;

use AppBundle\Service\Fighter\Action\Attack;

class MagicShield implements Skill
{
    private $chance = 20;
    private $type = 'defence';
    private $name = 'Magic Shield';

    public function getType() : string
    {
        return $this->type;
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function getChance() : float {
        return $this->chance;
    }

    public function run( Attack $attack) : void
    {
        $attack->setMultiplier($attack->getMultiplier() / 2);
    }

}
