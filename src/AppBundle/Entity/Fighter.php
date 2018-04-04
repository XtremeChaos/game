<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Fighter
 *
 * @ORM\Table(name="fighters")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FighterRepository")
 */
class Fighter
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @Assert\NotBlank()
     * @Assert\Type("numeric")
     * @Assert\Range(
     *      min = 5,
     *      max = 500
     * )
     *
     * @var string
     *
     * @ORM\Column(name="health", type="decimal", precision=7, scale=3)
     */
    private $health;

    /**
     * @Assert\EqualTo(propertyPath="health")
     *
     * @var string
     *
     * @ORM\Column(name="healthRemained", type="decimal", precision=7, scale=3)
     */
    private $healthRemained;

    /**
     * @Assert\NotBlank()
     * @Assert\Type("numeric")
     * @Assert\GreaterThan(propertyPath="defence")
     * @Assert\Range(
     *      min = 5,
     *      max = 500
     * )
     *
     * @var string
     *
     * @ORM\Column(name="strength", type="decimal", precision=7, scale=3)
     */
    private $strength;

    /**
     * @Assert\NotBlank()
     * @Assert\Type("numeric")
     * @Assert\LessThan(propertyPath="strength")
     * @Assert\Range(
     *      min = 5,
     *      max = 500
     * )
     *
     * @var string
     *
     * @ORM\Column(name="defence", type="decimal", precision=7, scale=3)
     */
    private $defence;

    /**
     * @Assert\NotBlank()
     * @Assert\Type("numeric")
     * @Assert\Range(
     *      min = 5,
     *      max = 500
     * )
     *
     * @var string
     *
     * @ORM\Column(name="speed", type="decimal", precision=7, scale=3)
     */
    private $speed;

    /**
     * @Assert\NotBlank()
     * @Assert\Type("numeric")
     * @Assert\LessThan(50)
     * @Assert\Range(
     *      min = 5,
     *      max = 500
     * )
     *
     * @var string
     *
     * @ORM\Column(name="luck", type="decimal", precision=7, scale=3)
     */
    private $luck;

    /**
     * @Assert\NotBlank()
     * @Assert\Choice({"hero", "beast"})
     *
     * @var \stdClass
     *
     * @ORM\Column(name="type", type="string", columnDefinition="enum('hero', 'beast')")
     */
    private $type;

    /**
     * @ORM\ManyToMany(targetEntity="Skill")
     * @ORM\JoinTable(name="fighters_skills")
     */
    protected $skills;

    /**
     * @ORM\OneToMany(targetEntity="GameFighter", mappedBy="fighter")
     */
    protected $gameFighters;


    public function __construct()
    {
        $this->skills = new ArrayCollection();
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
     * Set name
     *
     * @param string $name
     *
     * @return Fighter
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set health
     *
     * @param string $health
     *
     * @return Fighter
     */
    public function setHealth($health)
    {
        $this->health = $health;

        return $this;
    }

    /**
     * Get health
     *
     * @return string
     */
    public function getHealth()
    {
        return $this->health;
    }

    /**
     * Set healthRemained
     *
     * @param string $healthRemained
     *
     * @return Fighter
     */
    public function setHealthRemained($healthRemained)
    {
        $this->healthRemained = $healthRemained;

        return $this;
    }

    /**
     * Get healthRemained
     *
     * @return string
     */
    public function getHealthRemained()
    {
        return $this->healthRemained;
    }

    /**
     * Set strength
     *
     * @param string $strength
     *
     * @return Fighter
     */
    public function setStrength($strength)
    {
        $this->strength = $strength;

        return $this;
    }

    /**
     * Get strength
     *
     * @return string
     */
    public function getStrength()
    {
        return $this->strength;
    }

    /**
     * Set defence
     *
     * @param string $defence
     *
     * @return Fighter
     */
    public function setDefence($defence)
    {
        $this->defence = $defence;

        return $this;
    }

    /**
     * Get defence
     *
     * @return string
     */
    public function getDefence()
    {
        return $this->defence;
    }

    /**
     * Set speed
     *
     * @param string $speed
     *
     * @return Fighter
     */
    public function setSpeed($speed)
    {
        $this->speed = $speed;

        return $this;
    }

    /**
     * Get speed
     *
     * @return string
     */
    public function getSpeed()
    {
        return $this->speed;
    }

    /**
     * Set luck
     *
     * @param string $luck
     *
     * @return Fighter
     */
    public function setLuck($luck)
    {
        $this->luck = $luck;

        return $this;
    }

    /**
     * Get luck
     *
     * @return string
     */
    public function getLuck()
    {
        return $this->luck;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return Fighter
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }


    /**
     * @return mixed
     */
    public function getSkills()
    {
        return $this->skills;
    }

    /**
     * @param mixed $skills
     * @return Fighter
     */
    public function setSkills($skills)
    {
        $this->skills = $skills;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getGameFighters()
    {
        return $this->gameFighters;
    }

}

