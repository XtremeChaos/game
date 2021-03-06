<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Skill
 *
 * @ORM\Table(name="skills")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SkillRepository")
 * @UniqueEntity("className")
 */
class Skill
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
     * @Assert\Type("alnum")
     *
     * @var string
     *
     * @ORM\Column(name="className", type="string", columnDefinition="enum('MagicShield', 'RapidStrike', 'Berserk')", unique=true)
     */
    private $className;

    /**
     * @Assert\NotBlank()
     * @Assert\Choice({"attack", "defence"})
     *
     * @var string
     *
     * @ORM\Column(name="type", type="string", columnDefinition="enum('attack', 'defence')")
     */
    private $type;

    /**
     * @Assert\NotBlank()
     * @Assert\Type("numeric")
     * @Assert\Range(
     *      min = 1,
     *      max = 60
     * )
     *
     * @var string
     *
     * @ORM\Column(name="chance", type="decimal", precision=7, scale=3)
     */
    private $chance;

    /**
     * @Assert\NotBlank()
     * @Assert\Type("print")
     *
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     * Constructor
     */
    public function __construct()
    {
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
     * @return Skill
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
     * Set className
     *
     * @param string $className
     *
     * @return Skill
     */
    public function setClassName($className)
    {
        $this->className = $className;

        return $this;
    }

    /**
     * Get className
     *
     * @return string
     */
    public function getClassName()
    {
        return $this->className;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Skill
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     * @return Skill
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function getChance()
    {
        return $this->chance;
    }

    /**
     * @param string $chance
     * @return Skill
     */
    public function setChance(string $chance)
    {
        $this->chance = $chance;
        return $this;
    }

}
