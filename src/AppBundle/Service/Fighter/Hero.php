<?php

namespace AppBundle\Service\Fighter;

use AppBundle\Entity\Fighter;
use AppBundle\Service\Fighter\Skill\InterfaceSkillFacade;

class Hero extends FighterService implements BaseFighter {

    public function __construct(InterfaceSkillFacade $skillClass, Fighter $fighter = null ){
        try{
            parent::__construct($skillClass);
            $this->setName($fighter->getName());
            $this->setHealth($fighter->getHealth());
            $this->setHealthRemained($this->getHealth());
            $this->setStrength($fighter->getStrength());
            $this->setDefence($fighter->getDefence());
            $this->setSpeed($fighter->getSpeed());
            $this->setLuck($fighter->getLuck());

            $this->skills->add($fighter->getSkills());
        }catch (\Exception $e ){
            die('A fost intampinata o problema la initierea eroului : ' . $e->getMessage() );
        }
    }

}