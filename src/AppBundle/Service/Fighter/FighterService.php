<?php

namespace AppBundle\Service\Fighter;

use AppBundle\Service\Fighter\Action\Attack;
use AppBundle\Service\Fighter\Skill\SkillFacade;
use AppBundle\Service\GameLogs;
use AppBundle\Service\GameUtils;
use AppBundle\Service\Fighter\Skill\InterfaceSkillFacade;

abstract class FighterService implements BaseFighter {

    private $name;
    private $health;
    private $healthRemained;
    private $strength;
    private $defence;
    private $speed;
    private $luck;
    protected $skills;

    public function __construct(InterfaceSkillFacade $skillClass){
        $this->skills = $skillClass;
    }

    public function setName( string $name = null ) : void{
        $this->name = $name;
    }

    public function getName() : string {
        return $this->name;
    }

    public function setHealth( float $health = null ) : void{
        $this->health = $health;
    }

    public function getHealth() : float{
        return $this->health;
    }

    public function setHealthRemained( float $healthRemained = null ) : void{
        $this->healthRemained = $healthRemained;
    }

    public function getHealthRemained() : float{
        return $this->healthRemained;
    }

    public function setStrength( float $strength = null ) : void{
        $this->strength = $strength;
    }

    public function getStrength() : float{
        return $this->strength;
    }

    public function setDefence( float $defence = null ) : void{
        $this->defence = $defence ;
    }

    public function getDefence() : float{
        return $this->defence;
    }

    public function setSpeed( float $speed = null ) : void{
        $this->speed = $speed ;
    }

    public function getSpeed() : float{
        return $this->speed;
    }

    public function setLuck( float $luck = null ) : void{
        $this->luck = $luck ;
    }

    public function getLuck() : float {
        return $this->luck;
    }

    public function isDead() : bool {
        return $this->getHealthRemained() <= 0;
    }

    public function attack() : Attack{
        $attack = new Attack($this->getStrength());

        $this->skills->modifyAttack( $attack, 'attack' );

        return $attack;
    }

    public function defend( Attack $attack ) : void{
        if( $this->hasLuck() ){
            GameLogs::add($this->getName().' a avut noroc si nu a fost lovit');
            return;
        }

        $this->skills->modifyAttack( $attack, 'defence' );

        $this->takeDamage($attack);
    }

    public function takeDamage( Attack $attack = null ) : Attack{
        if( $attack->getMultiplier() < 1 ){
            $damage = ( $attack->getStrength() - $this->getDefence() ) * $attack->getMultiplier() ;
        }else{
            $damage = $attack->getStrength() - $this->getDefence() ;
        }
        if( $damage > 0 ){
            $this->setHealthRemained($this->getHealthRemained() - $damage);
            GameLogs::add($this->getName().' a fost atacat cu '.$damage.' si a mai ramas la viata cu : '.$this->getHealthRemained());
        }
        if( $attack->getMultiplier() - 1 > 0 ){
            $attack->setMultiplier($attack->getMultiplier() - 1 );
            return $this->takeDamage($attack);
        }

        return $attack;
    }

    public function hasLuck() : bool {
        return GameUtils::checkProbability($this->getLuck());
    }

    public function getSkills() : array {
        return $this->skills->getAll();
    }

}
