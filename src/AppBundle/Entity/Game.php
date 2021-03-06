<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Game
 *
 * @ORM\Table(name="games")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\GameRepository")
 */
class Game
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
     * @Assert\NotBlank()
     * @Assert\Type("print")
     *
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @Assert\NotBlank()
     * @Assert\Type("numeric")
     * @Assert\Range(
     *      min = 5,
     *      max = 1000
     * )
     *
     * @var int
     *
     * @ORM\Column(name="max_round", type="integer")
     */
    private $maxRound;

    /**
     * @ORM\OneToMany(targetEntity="GameFighter", mappedBy="game")
     */
    protected $gameFighters;

    private $whiteTeamCurrentAttacker = 0;
    private $blackTeamCurrentAttacker = 0;
    private $round = 0;
    private $endRound = 20;

    public function __construct()
    {
        $this->gameFighters = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Game
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getMaxRound()
    {
        return $this->maxRound;
    }

    /**
     * @param string $maxRound
     * @return Game
     */
    public function setMaxRound($maxRound)
    {
        $this->maxRound = $maxRound;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getGameFighters()
    {
        return $this->gameFighters;
    }

    /**
     * @TODO
     */

    public function increaseRound()
    {
        $this->round++;
    }

    public function getRound()
    {
        return $this->round;
    }

    /**
     * @return int
     */
    public function getWhiteTeamCurrentAttacker(): int
    {
        return $this->whiteTeamCurrentAttacker;
    }

    /**
     * @param int $whiteTeamCurrentAttacker
     * @return Game
     */
    public function setWhiteTeamCurrentAttacker(int $whiteTeamCurrentAttacker): Game
    {
        $this->whiteTeamCurrentAttacker = $whiteTeamCurrentAttacker;
        return $this;
    }

    /**
     * @return int
     */
    public function getBlackTeamCurrentAttacker(): int
    {
        return $this->blackTeamCurrentAttacker;
    }

    /**
     * @param int $blackTeamCurrentAttacker
     * @return Game
     */
    public function setBlackTeamCurrentAttacker(int $blackTeamCurrentAttacker)
    {
        $this->blackTeamCurrentAttacker = $blackTeamCurrentAttacker;
        return $this;
    }

    /**
     * @return int
     */
    public function getEndRound(): int
    {
        return $this->endRound;
    }

    /**
     * @param int $endRound
     * @return Game
     */
    public function setEndRound(int $endRound)
    {
        $this->endRound = $endRound;
        return $this;
    }

}
