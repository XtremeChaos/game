<?php

namespace AppBundle\Service\Fighter\Skill\Listing;

use AppBundle\Entity\Attack;

class Berserk implements Skill
{
    private $chance = 5;
    private $type = 'attack';
    private $name = 'Berserk';

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

    public function run( attack $attack ) : void
    {
        $attack->setStrength( $attack->getStrength() * 1.5 );
    }

}