<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * GameFighter
 *
 * @ORM\Table(name="games_fighters")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\GameFighterRepository")
 */
class GameFighter
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="game_id", type="integer")
     */
    private $gameId;

    /**
     * @var int
     *
     * @ORM\Column(name="fighter_id", type="integer")
     */
    private $fighterId;

    /**
     * @ORM\ManyToOne(targetEntity="Game", inversedBy="gameFighters")
     * @ORM\JoinColumn(name="game_id", referencedColumnName="id")
     */
    private $game;

    /**
     * @ORM\ManyToOne(targetEntity="Fighter", inversedBy="gameFighters")
     * @ORM\JoinColumn(name="fighter_id", referencedColumnName="id")
     */
    private $fighter;

    /**
     * @var string
     *
     * @ORM\Column(name="team", type="string")
     */
    private $team;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getGame()
    {
        return $this->game;
    }

    /**
     * @param mixed $game
     * @return GameFighter
     */
    public function setGame($game)
    {
        $this->game = $game;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getFighter()
    {
        return $this->fighter;
    }

    /**
     * @param mixed $fighter
     * @return GameFighter
     */
    public function setFighter($fighter)
    {
        $this->fighter = $fighter;

        return $this;
    }

    /**
     * @return int
     */
    public function getGameId()
    {
        return $this->gameId;
    }

    /**
     * @param int $gameId
     * @return GameFighter
     */
    public function setGameId($gameId)
    {
        $this->gameId = $gameId;
        return $this;
    }

    /**
     * @return int
     */
    public function getFighterId()
    {
        return $this->fighterId;
    }

    /**
     * @param int $fighterId
     */
    public function setFighterId($fighterId)
    {
        $this->fighterId = $fighterId;
    }

    /**
     * @return string
     */
    public function getTeam()
    {
        return $this->team;
    }

    /**
     * @param string $team
     * @return GameFighter
     */
    public function setTeam($team)
    {
        $this->team = $team;

        return $this;
    }

}