<?php

namespace AppBundle\Service\Fighter\Skill;

use AppBundle\Entity\Attack;
use AppBundle\Service\GameLogs;
use AppBundle\Entity\Skill;
use AppBundle\Service\GameUtils;

class SkillService{

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
             * @var Skill $skill
             */
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

    private function addToDefence( Skill $skill ) : void {
        array_push($this->defence,$skill);
    }

    private function addToAttack( Skill $skill ) : void {
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
            $this->run($skill, $attack );
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

    public function run(Skill $skill, Attack $attack)
    {
        $skills = [
            'MagicShield' => function(Attack $attack){
                $attack->setMultiplier($attack->getMultiplier() / 2);
            },
            'RapidStrike' => function(Attack $attack){
                $attack->setMultiplier($attack->getMultiplier() + 1);
            },
            'Berserk' => function(Attack $attack){
                $attack->setStrength( $attack->getStrength() * 1.5 );
            }
        ];

        $skills[$skill->getClassName()]($attack);
    }
}

