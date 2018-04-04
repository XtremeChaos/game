<?php

namespace AppBundle\Service\Fighter\Skill;

use AppBundle\Service\Fighter\Action\Attack;

interface InterfaceSkillFacade
{
    public function getDefence() : array;
    public function getAttack() : array;
    public function getAll() : array;
    public function add( $skills ) : void;
    public function modifyAttack(Attack $attack = null, string $type = null ) : void;
    public function getSkillsByType(string $type = null) : array ;
}