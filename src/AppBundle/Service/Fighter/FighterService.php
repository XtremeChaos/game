<?php

namespace AppBundle\Service\Fighter;

use AppBundle\Entity\Fighter;
use AppBundle\Entity\Attack;
use AppBundle\Service\GameLogs;
use AppBundle\Service\GameUtils;
use AppBundle\Service\Fighter\Skill\SkillService;

class FighterService
{

    public function isDead(Fighter $fighter): bool
    {
        return $fighter->getHealthRemained() <= 0;
    }

    public function attack(SkillService $skillService, Fighter $fighter): Attack
    {
        $attack = new Attack($fighter->getStrength());

        $skillService->modifyAttack($attack, 'attack');

        return $attack;
    }

    public function defend(SkillService $skillService, Fighter $fighter, Attack $attack): void
    {
        if ($this->hasLuck($fighter)) {
            GameLogs::add($fighter->getName() . ' a avut noroc si nu a fost lovit');
            return;
        }

        $skillService->modifyAttack($attack, 'defence');

        $this->takeDamage($fighter, $attack);
    }

    public function takeDamage(Fighter $fighter, Attack $attack = null): Attack
    {
        if ($attack->getMultiplier() < 1) {
            $damage = ($attack->getStrength() - $fighter->getDefence()) * $attack->getMultiplier();
        } else {
            $damage = $attack->getStrength() - $fighter->getDefence();
        }
        if ($damage > 0) {
            $fighter->setHealthRemained($fighter->getHealthRemained() - $damage);
            GameLogs::add($fighter->getName() . ' a fost atacat cu ' . $damage . ' si a mai ramas la viata cu : ' . $fighter->getHealthRemained());
        }
        if ($attack->getMultiplier() - 1 > 0) {
            $attack->setMultiplier($attack->getMultiplier() - 1);
            return $this->takeDamage($fighter, $attack);
        }

        return $attack;
    }

    public function hasLuck(Fighter $fighter): bool
    {
        return GameUtils::checkProbability($fighter->getLuck());
    }

    public function getSkills(Fighter $fighter): array
    {
        return $fighter->skillService->getAll();
    }
}