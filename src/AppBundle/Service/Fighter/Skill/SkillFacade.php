<?php

namespace AppBundle\Service\Fighter\Skill;

use AppBundle\Service\Fighter\Action\Attack;
use AppBundle\Service\GameLogs;
use AppBundle\Service\Fighter\Skill\Listing\Skill;
use AppBundle\Entity\Skill as SkillEntity;
use AppBundle\Service\GameUtils;

class SkillFacade implements InterfaceSkillFacade{

    protected $attack = [];
    protected $defence = [];

    public function getDefence() : array {
        return $this->defence;
    }

    public function getAttack() : array {
        return $this->attack;
    }

    public function getAll() : array {
        return array_merge($this->getAttack(),$this->getDefence());
    }

    public function add( $skills ) : void {
        foreach ( $skills as $skill ) {
            /**
             * @var SkillEntity $skill
             */
            $skill_name = $skill->getClassName();
            $skill_class = '\AppBundle\Service\Fighter\Skill\Listing\\'.$skill_name;
            /**
             * @var Skill $skill
             */
            $skill = new $skill_class();
            switch ( $skill->getType() ){
                case 'attack':
                    $this->addToAttack($skill);
                    break;
                case 'defence':
                    $this->addToDefence($skill);
                    break;
            }
        }
    }

    private function addToDefence( skill $skill ) : void {
        array_push($this->defence,$skill);
    }

    private function addToAttack( skill $skill ) : void {
        array_push($this->attack,$skill);
    }

    private function getTriggered( string $type ) : array {
        $triggered = [];
        $skills = $this->getSkillsByType( $type );

        foreach( $skills as $skill ){
            if( !GameUtils::checkProbability($skill->getChance()) ){
                continue;
            }
            array_push($triggered, $skill);
        }
        return $triggered;
    }

    public function modifyAttack( Attack $attack = null, string $type = null ) : void {

        $skills = $this->getTriggered( $type );

        foreach( $skills  as $skill ){
            /**
             * @var skill $skill
             */
            GameLogs::add( "Se activeaza skill-ul ".$skill->getName()." celui care  ".($skill->getType() == 'attack' ? 'ataca' : 'se apara ') );
            $skill->run( $attack );
        }

    }

    public function getSkillsByType( string $type = null ) : array {
        $skills = [];
        switch ($type){
            case 'attack':
                $skills = $this->getAttack();
                break;
            case 'defence':
                $skills = $this->getDefence();
                break;
        }
        return $skills;
    }
}

