<?php

namespace AppBundle\Service;

use AppBundle\Entity\Fighter;
use AppBundle\Entity\Game;
use AppBundle\Service\Fighter\Hero;
use AppBundle\Service\Fighter\Beast;
use AppBundle\Service\Fighter\FighterService;
use AppBundle\Service\Fighter\Skill\SkillFacade;

class GameService
{
    public $whiteTeam = [];
    public $blackTeam = [];
    private $whiteTeamCurrentAttacker = 0;
    private $blackTeamCurrentAttacker = 0;
    private $round = 0;
    private $endRound = 20;

    private function increaseRound()
    {
        $this->round++;
    }

    private function getRound()
    {
        return $this->round;
    }

    public function setEndRound($endRound = null)
    {
        $this->endRound = $endRound;
    }

    private function getEndRound()
    {
        return $this->endRound;
    }

    private function addWhiteTeam(FighterService $fighter): void
    {
        array_push($this->whiteTeam, $fighter);
    }

    private function addBlackTeam(FighterService $fighter): void
    {
        array_push($this->blackTeam, $fighter);
    }

    public function addPlayer(
        string $team = '',
        Fighter $fighter
    ): void {
        switch ($fighter->getType()) {
            case 'hero':
                $fighter = new Hero(new SkillFacade(), $fighter);
                break;
            case 'beast':
                $fighter = new Beast(new SkillFacade(), $fighter);
                break;
        }

        switch ($team) {
            case 'white':
                $this->addWhiteTeam($fighter);
                break;
            case 'black':
                $this->addBlackTeam($fighter);
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
        $this->whiteTeamCurrentAttacker += 1;

        if ($this->whiteTeamCurrentAttacker > count($this->whiteTeam) - 1) {
            $this->whiteTeamCurrentAttacker = 0;
        }
    }

    private function nextBlackTeamAttacker(): void
    {
        $this->blackTeamCurrentAttacker += 1;

        if ($this->blackTeamCurrentAttacker > count($this->blackTeam) - 1) {
            $this->blackTeamCurrentAttacker = 0;
        }
    }

    private function getTeamDefender(string $team = ''): FighterService
    {
        $fighters = $this->getTeam($team);
        return $fighters[0];
    }

    private function getTeamCurrentAttackerIndex(string $team = ''): int
    {
        $index = null;
        switch ($team) {
            case 'white':
                $index = $this->whiteTeamCurrentAttacker;
                break;
            case 'black':
                $index = $this->blackTeamCurrentAttacker;
                break;
        }
        return $index;
    }

    private function getTeamAttacker(string $team = ''): FighterService
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
        switch ($team) {
            case 'white':
                unset($this->whiteTeam[$index]);
                break;
            case 'black':
                unset($this->blackTeam[$index]);
                break;
        }
        $this->sortTeam($team);
    }

    private function getFastestFighter(string $team): FighterService
    {
        $fighters = $this->getTeam($team);
        $fastestFighter = null;
        $fighterBestSpeed = 0;
        foreach ($fighters as $fighter) {
            /**
             * @var FighterService $fighter
             */
            if ($fighter->getSpeed() > $fighterBestSpeed) {
                $fighterBestSpeed = $fighter->getSpeed();
                $fastestFighter = $fighter;
            }
        }
        return $fastestFighter;
    }

    private function checkFirstAttacker(FighterService $white, FighterService $black): string
    {
        if ($white->getSpeed() == $black->getSpeed()) {
            return $white->getLuck() >= $black->getLuck() ? 'white' : 'black';
        }
        return $white->getSpeed() > $black->getSpeed() ? 'white' : 'black';
    }

    private function sortTeam(string $team = ''): void
    {
        usort($this->{$team . 'Team'}, array('\AppBundle\Service\GameService', 'compareFightersSpeeds'));
    }

    public function getTeam(string $team): array
    {
        $fighters = [];
        switch ($team) {
            case 'white':
                $fighters = $this->getWhiteTeam();
                break;
            case 'black':
                $fighters = $this->getBlackTeam();
                break;
        }
        return $fighters;
    }

    private function getWhiteTeam(): array
    {
        return $this->whiteTeam;
    }

    private function getBlackTeam(): array
    {
        return $this->blackTeam;
    }

    private function compareFightersSpeeds(FighterService $fighterA, FighterService $fighterB): int
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
        return $this->getRound() > $this->getEndRound();
    }


    public function startGame() : bool {
        $this->sortTeam('white');
        $this->sortTeam('black');

        $fastestWhiteFighter = $this->getFastestFighter('white');
        $fastestBlackFighter = $this->getFastestFighter('black');

        $startTeamName = $this->checkFirstAttacker( $fastestWhiteFighter, $fastestBlackFighter );

        GameLogs::addStats(['white'=> $this->getWhiteTeam(), 'black' => $this->getBlackTeam()]);
        GameLogs::add('Incepe echipa '.$startTeamName);

        $game = $this->startRound($startTeamName);

        if( $game === false ){
            GameLogs::add('A aparut o problema');
            return false;
        }

        GameLogs::add('Jocul s-a terminat');

        GameLogs::addStats(['white'=> $this->getWhiteTeam(), 'black' => $this->getBlackTeam()]);
        return true;
    }

    private function startRound( string $startTeam = '' ) : bool {
        $this->increaseRound();
        if( $this->checkEndGameByRound() ){
            GameLogs::add("Runda {$this->getEndRound()} s-a incheiat. Este egalitate");
            return true;
        }
        GameLogs::add("Incepe runda {$this->getRound()} ");
        switch ($startTeam){
            case 'white':
                $attacker = $this->getTeamAttacker('white');
                $defender = $this->getTeamDefender('black');

                GameLogs::add("Ataca {$attacker->getName()} cu puterea {$attacker->getStrength()} pe {$defender->getName()} care are {$defender->getDefence()} aparare si {$defender->getHealthRemained()} viata ramasa");
                $attack = $attacker->attack();

                $defender->defend($attack);

                if( $defender->isDead() ){
                    GameLogs::add('A murit membrul echipei Black : '.$defender->getName());
                    $this->removeFromTeam('black',0);

                    if( !$this->hasTeamMembers('black') ){
                        GameLogs::add('Echipa Black a pierdut !');
                        return true;
                    }
                }

                $this->nextTeamAttacker('white');

                return $this->startRound('black');
                break;
            case 'black':
                $attacker = $this->getTeamAttacker( 'black' );
                $defender = $this->getTeamDefender('white');

                GameLogs::add("Ataca {$attacker->getName()} cu puterea {$attacker->getStrength()} pe {$defender->getName()} care are {$defender->getDefence()} aparare si {$defender->getHealthRemained()} viata ramasa");
                $attack = $attacker->attack();

                $defender->defend($attack);

                if( $defender->isDead() ){
                    GameLogs::add('A murit membrul echipei White : '.$defender->getName());

                    $this->removeFromTeam('white',0 );

                    if( !$this->hasTeamMembers('white') ){
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

    public function start(Game $game)
    {
        $this->setEndRound($game->getMaxRound());

        foreach ($game->getGameFighters() as $fighter) {
            $this->addPlayer($fighter->getTeam(),$fighter->getFighter());
        }

        $this->startGame();

        return GameLogs::get();
    }
}