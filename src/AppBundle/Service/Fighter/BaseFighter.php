<?php

namespace AppBundle\Service\Fighter;

use AppBundle\Service\Fighter\Action\Attack;

interface BaseFighter{
    function __construct($skillClass);
    function setName( string $name ):void;
    function getName() : string ;
    function setHealth( float $health ): void;
    function getHealth() : float;
    function setHealthRemained( float $health_remained ): void ;
    function getHealthRemained() : float ;
    function setStrength( float $strength ) : void;
    function getStrength() : float ;
    function setDefence( float $defence ) : void;
    function getDefence() : float;
    function setSpeed( float $speed ) : void;
    function getSpeed() : float;
    function setLuck( float $luck ) : void;
    function getLuck() : float ;
    function isDead() : bool;
    function attack() : Attack;
    function defend( Attack $attack ) : void;
    function takeDamage( Attack $attack ) : Attack;
    function hasLuck() : bool ;
    function getSkills() : array;
}