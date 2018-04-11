<?php

namespace AppBundle\Service;

use AppBundle\Entity\Fighter;
use AppBundle\Entity\Game;
use AppBundle\Entity\GameFighter;
use AppBundle\Service\Fighter\FighterService;
use AppBundle\Service\Fighter\Skill\SkillService;

class GameService
{
    /**
     * @var Game $game
     */
    private $game;
    /**
     * @var FighterService $fighterService
     */
    private $fighterService;
    /**
     * @var SkillService $skillService
     */
    private $skillService;

    public function addToTeam(
        GameFighter $fighter,
        array $teamFighters
    ): array
    {
        $teamFighters[$fighter->getTeam()][] = $fighter->getFighter();
        return $teamFighters;
    }

    private function hasTeamMembers(array $teamFighters, string $team = ''): bool
    {
        $fighters = $this->getTeam($teamFighters, $team);
        return count($fighters) != 0;
    }


    private function nextTeamAttacker(array $teamFighters,string $team = ''): void
    {
        switch ($team) {
            case 'white':
                $this->nextWhiteTeamAttacker($teamFighters);
                break;
            case 'black':
                $this->nextBlackTeamAttacker($teamFighters);
                break;
        }
    }

    private function nextWhiteTeamAttacker(array $teamFighters): void
    {
        $this->game->setWhiteTeamCurrentAttacker($this->game->getWhiteTeamCurrentAttacker() + 1);

        if ($this->game->getWhiteTeamCurrentAttacker() > count($this->getTeam($teamFighters,'white')) - 1) {
            $this->game->setWhiteTeamCurrentAttacker(0);
        }
    }

    private function nextBlackTeamAttacker(array $teamFighters): void
    {
        $this->game->setBlackTeamCurrentAttacker($this->game->getBlackTeamCurrentAttacker() + 1);

        if ($this->game->getBlackTeamCurrentAttacker() > count($this->getTeam($teamFighters,'black')) - 1) {
            $this->game->setBlackTeamCurrentAttacker(0);
        }
    }

    private function getTeamDefender(array $teamFighters, string $team = ''): Fighter
    {

        $fighters = $this->getTeam($teamFighters,$team);
        return $fighters[0];
    }

    private function getTeamCurrentAttackerIndex(string $team = ''): int
    {
        $index = null;
        switch ($team) {
            case 'white':
                $index = $this->game->getWhiteTeamCurrentAttacker();
                break;
            case 'black':
                $index = $this->game->getBlackTeamCurrentAttacker();
                break;
        }
        return $index;
    }

    private function getTeamAttacker(array $teamFighters, string $team = ''): Fighter
    {
        $fighters = $this->getTeam($teamFighters, $team);
        $current = $this->getTeamCurrentAttackerIndex($team);
        if (empty($fighters[$current])) {
             $this->nextTeamAttacker($teamFighters,$team);
        }
        $current = $this->getTeamCurrentAttackerIndex($team);
        return $fighters[$current];
    }

    private function removeFromTeam(array $teamFighters, string $team = '', int $index = null): array
    {
        //@TODO metoda in entitate de scoatere din echipa
        unset($teamFighters[$team][$index]);
        return $this->sortTeam($teamFighters,$team);
    }

    private function getFastestFighter(array $teamFighters, string $team): Fighter
    {
        $fighters = $teamFighters[$team];
        $fastestFighter = null;
        $fighterBestSpeed = 0;
        foreach ($fighters as $fighter) {
            /**
             * @var Fighter $fighter
             */
            if ($fighter->getSpeed() > $fighterBestSpeed) {
                $fighterBestSpeed = $fighter->getSpeed();
                $fastestFighter = $fighter;
            }
        }
        return $fastestFighter;
    }

    private function checkFirstAttacker(Fighter $white, Fighter $black): string
    {
        if ($white->getSpeed() == $black->getSpeed()) {
            return $white->getLuck() >= $black->getLuck() ? 'white' : 'black';
        }
        return $white->getSpeed() > $black->getSpeed() ? 'white' : 'black';
    }

    private function sortTeam(array $teamFighters, string $team = ''): array
    {
        usort($teamFighters[$team], array('\AppBundle\Service\GameService', 'compareFightersSpeeds'));
        return $teamFighters;
    }

    public function getTeam($teamFighters, string $team): array
    {
        return $teamFighters[$team];
    }

    private function compareFightersSpeeds(Fighter $fighterA, Fighter $fighterB): int
    {
        if ($fighterA->getSpeed() == $fighterB->getSpeed()) {
            if ($fighterA->getLuck() == $fighterB->getLuck()) {
                return 0;
            }
            if ($fighterA->getLuck() > $fighterB->getLuck()) {
                return -1;
            }
            return 1;
        }

        return $fighterA->getSpeed() > $fighterB->getSpeed() ? -1 : 1;
    }

    private function checkEndGameByRound(): bool
    {
        return $this->game->getRound() > $this->game->getMaxRound();
    }


    public function startGame(array $teamFighters): bool
    {
        $fastestWhiteFighter = $this->getFastestFighter($teamFighters,'white');
        $fastestBlackFighter = $this->getFastestFighter($teamFighters,'black');

        $startTeamName = $this->checkFirstAttacker($fastestWhiteFighter, $fastestBlackFighter);

        GameLogs::addStats(['white' => $this->getTeam($teamFighters,'white'), 'black' => $this->getTeam($teamFighters,'black')]);
        GameLogs::add('Incepe echipa ' . $startTeamName);

        $game = $this->startRound($teamFighters,$startTeamName);

        if ($game === false) {
            GameLogs::add('A aparut o problema');
            return false;
        }

        GameLogs::add('Jocul s-a terminat');

        GameLogs::addStats(['white' => $this->getTeam($teamFighters,'white'), 'black' => $this->getTeam($teamFighters,'black')]);
        return true;
    }

    private function startRound(array $teamFighters, string $startTeam = ''): bool
    {
        $this->game->increaseRound();
        if ($this->checkEndGameByRound()) {
            GameLogs::add("Runda {$this->game->getMaxRound()} s-a incheiat. Este egalitate");
            return true;
        }
        GameLogs::add("Incepe runda {$this->game->getRound()} ");
        switch ($startTeam) {
            case 'white':
                $attacker = $this->getTeamAttacker($teamFighters,'white');
                $defender = $this->getTeamDefender($teamFighters,'black');

                GameLogs::add("Ataca {$attacker->getName()} cu puterea {$attacker->getStrength()} pe {$defender->getName()} care are {$defender->getDefence()} aparare si {$defender->getHealthRemained()} viata ramasa");
                $attack = $this->fighterService->attack($this->skillService, $attacker);

                $this->fighterService->defend($this->skillService, $defender, $attack);

                if ($this->fighterService->isDead($defender)) {
                    GameLogs::add('A murit membrul echipei Black : ' . $defender->getName());
                    $teamFighters = $this->removeFromTeam($teamFighters,'black', 0);

                    if (!$this->hasTeamMembers($teamFighters,'black')) {
                        GameLogs::add('Echipa Black a pierdut !');
                        return true;
                    }
                }

                $this->nextTeamAttacker($teamFighters,'white');
                return $this->startRound($teamFighters,'black');
                break;
            case 'black':
                $attacker = $this->getTeamAttacker($teamFighters,'black');
                $defender = $this->getTeamDefender($teamFighters,'white');

                GameLogs::add("Ataca {$attacker->getName()} cu puterea {$attacker->getStrength()} pe {$defender->getName()} care are {$defender->getDefence()} aparare si {$defender->getHealthRemained()} viata ramasa");
                $attack = $this->fighterService->attack($this->skillService, $attacker);

                $this->fighterService->defend($this->skillService, $defender, $attack);

                if ($this->fighterService->isDead($defender)) {
                    GameLogs::add('A murit membrul echipei White : ' . $defender->getName());
                    $teamFighters = $this->removeFromTeam($teamFighters,'white', 0);

                    if (!$this->hasTeamMembers($teamFighters,'white')) {
                        GameLogs::add('Echipa White a pierdut !!');
                        return true;
                    }
                }

                $this->nextTeamAttacker($teamFighters,'black');
                return $this->startRound($teamFighters,'white');
                break;
        }

        return false;

    }

    public function start(Game $game, FighterService $fighterService, SkillService $skillService)
    {
        $this->game = $game;
        $this->fighterService = $fighterService;
        $this->skillService = $skillService;

        $teamFighters = [];
        foreach ($this->game->getGameFighters() as $fighter) {
            $teamFighters = $this->addToTeam($fighter,$teamFighters);
        }

        $this->startGame($teamFighters);

        return GameLogs::get();
    }
}