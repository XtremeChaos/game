<?php

namespace AppBundle\Service;

use AppBundle\Entity\Fighter;
use AppBundle\Entity\Game;
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

    public function addPlayer(
        string $team = '',
        Fighter $fighter
    ): void
    {

        $fighter->addSkillService(new SkillService());

        switch ($team) {
            case 'white':
                $this->game->addWhiteTeam($fighter);
                break;
            case 'black':
                $this->game->addBlackTeam($fighter);
                break;
        }

    }

    private function hasTeamMembers(string $team = ''): bool
    {
        $fighters = $this->getTeam($team);
        return count($fighters) != 0;
    }


    private function nextTeamAttacker(string $team = ''): void
    {
        switch ($team) {
            case 'white':
                $this->nextWhiteTeamAttacker();
                break;
            case 'black':
                $this->nextBlackTeamAttacker();
                break;
        }
    }

    private function nextWhiteTeamAttacker(): void
    {
        //@TODO set, get
        $this->game->whiteTeamCurrentAttacker += 1;

        if ($this->game->whiteTeamCurrentAttacker > count($this->game->whiteTeam) - 1) {
            $this->game->whiteTeamCurrentAttacker = 0;
        }
    }

    private function nextBlackTeamAttacker(): void
    {
        //@TODO set, get
        $this->game->blackTeamCurrentAttacker += 1;

        if ($this->game->blackTeamCurrentAttacker > count($this->game->blackTeam) - 1) {
            $this->game->blackTeamCurrentAttacker = 0;
        }
    }

    private function getTeamDefender(string $team = ''): Fighter
    {
        $fighters = $this->getTeam($team);
        return $fighters[0];
    }

    private function getTeamCurrentAttackerIndex(string $team = ''): int
    {
        $index = null;
        switch ($team) {
            case 'white':
                //@TODO get, set ..
                $index = $this->game->whiteTeamCurrentAttacker;
                break;
            case 'black':
                $index = $this->game->blackTeamCurrentAttacker;
                break;
        }
        return $index;
    }

    private function getTeamAttacker(string $team = ''): Fighter
    {
        $teamFighters = $this->getTeam($team);
        $current = $this->getTeamCurrentAttackerIndex($team);
        if (empty($teamFighters[$current])) {
            $this->nextTeamAttacker($team);
        }
        $current = $this->getTeamCurrentAttackerIndex($team);
        return $teamFighters[$current];
    }

    private function removeFromTeam(string $team = '', int $index = null): void
    {
        //@TODO metoda in entitate de scoatere din echipa
        switch ($team) {
            case 'white':
                unset($this->game->whiteTeam[$index]);
                break;
            case 'black':
                unset($this->game->blackTeam[$index]);
                break;
        }
        $this->sortTeam($team);
    }

    private function getFastestFighter(string $team): Fighter
    {
        $fighters = $this->getTeam($team);
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

    private function sortTeam(string $team = ''): void
    {
        usort($this->game->{$team . 'Team'}, array('\AppBundle\Service\GameService', 'compareFightersSpeeds'));
    }

    public function getTeam(string $team): array
    {
        $fighters = [];
        switch ($team) {
            case 'white':
                $fighters = $this->game->getWhiteTeam();
                break;
            case 'black':
                $fighters = $this->game->getBlackTeam();
                break;
        }
        return $fighters;
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


    public function startGame(): bool
    {
        $this->sortTeam('white');
        $this->sortTeam('black');

        $fastestWhiteFighter = $this->getFastestFighter('white');
        $fastestBlackFighter = $this->getFastestFighter('black');

        $startTeamName = $this->checkFirstAttacker($fastestWhiteFighter, $fastestBlackFighter);

        GameLogs::addStats(['white' => $this->game->getWhiteTeam(), 'black' => $this->game->getBlackTeam()]);
        GameLogs::add('Incepe echipa ' . $startTeamName);

        $game = $this->startRound($startTeamName);

        if ($game === false) {
            GameLogs::add('A aparut o problema');
            return false;
        }

        GameLogs::add('Jocul s-a terminat');

        GameLogs::addStats(['white' => $this->game->getWhiteTeam(), 'black' => $this->game->getBlackTeam()]);
        return true;
    }

    private function startRound(string $startTeam = ''): bool
    {
        $this->game->increaseRound();
        if ($this->checkEndGameByRound()) {
            GameLogs::add("Runda {$this->game->getMaxRound()} s-a incheiat. Este egalitate");
            return true;
        }
        GameLogs::add("Incepe runda {$this->game->getRound()} ");
        switch ($startTeam) {
            case 'white':
                $attacker = $this->getTeamAttacker('white');
                $defender = $this->getTeamDefender('black');

                GameLogs::add("Ataca {$attacker->getName()} cu puterea {$attacker->getStrength()} pe {$defender->getName()} care are {$defender->getDefence()} aparare si {$defender->getHealthRemained()} viata ramasa");
                $attack = $this->fighterService->attack($attacker);

                $this->fighterService->defend($defender, $attack);

                if ($this->fighterService->isDead($defender)) {
                    GameLogs::add('A murit membrul echipei Black : ' . $defender->getName());
                    $this->removeFromTeam('black', 0);

                    if (!$this->hasTeamMembers('black')) {
                        GameLogs::add('Echipa Black a pierdut !');
                        return true;
                    }
                }

                $this->nextTeamAttacker('white');

                return $this->startRound('black');
                break;
            case 'black':
                $attacker = $this->getTeamAttacker('black');
                $defender = $this->getTeamDefender('white');

                GameLogs::add("Ataca {$attacker->getName()} cu puterea {$attacker->getStrength()} pe {$defender->getName()} care are {$defender->getDefence()} aparare si {$defender->getHealthRemained()} viata ramasa");
                $attack = $this->fighterService->attack($attacker);

                $this->fighterService->defend($defender, $attack);

                if ($this->fighterService->isDead($defender)) {
                    GameLogs::add('A murit membrul echipei White : ' . $defender->getName());

                    $this->removeFromTeam('white', 0);

                    if (!$this->hasTeamMembers('white')) {
                        GameLogs::add('Echipa White a pierdut !!');
                        return true;
                    }
                }

                $this->nextTeamAttacker('black');
                return $this->startRound('white');
                break;
        }

        return false;

    }

    public function start(Game $game, FighterService $fighterService)
    {
        $this->game = $game;
        $this->fighterService = $fighterService;

        foreach ($this->game->getGameFighters() as $fighter) {
            $this->addPlayer($fighter->getTeam(), $fighter->getFighter());
        }

        $this->startGame();

        return GameLogs::get();
    }
}