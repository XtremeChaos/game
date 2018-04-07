<?php

namespace AppBundle\Service\Fighter\Skill\Listing;

use AppBundle\Entity\Attack;

interface Skill{
    function getType() : string ;
    function getName() : string ;
    function getChance() : float ;
    function run( Attack $attack) : void ;
}
