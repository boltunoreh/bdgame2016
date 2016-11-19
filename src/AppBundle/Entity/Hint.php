<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Hint
 *
 * @ORM\Table(name="hint")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\HintRepository")
 */
class Hint
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
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"title"})
     * @ORM\Column(name="slug", type="string", length=255, unique=true)
     */
    private $slug;

    /**
     * @var boolean
     *
     * @ORM\Column(name="team1_used", type="boolean")
     */
    private $teamOneUsed;

    /**
     * @return boolean
     */
    public function isTeamOneUsed()
    {
        return $this->teamOneUsed;
    }

    /**
     * @param boolean $teamOneUsed
     * @return Hint
     */
    public function setTeamOneUsed($teamOneUsed)
    {
        $this->teamOneUsed = $teamOneUsed;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isTeamTwoUsed()
    {
        return $this->teamTwoUsed;
    }

    /**
     * @param boolean $teamTwoUsed
     * @return Hint
     */
    public function setTeamTwoUsed($teamTwoUsed)
    {
        $this->teamTwoUsed = $teamTwoUsed;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isTeamThreeUsed()
    {
        return $this->teamThreeUsed;
    }

    /**
     * @param boolean $teamThreeUsed
     * @return Hint
     */
    public function setTeamThreeUsed($teamThreeUsed)
    {
        $this->teamThreeUsed = $teamThreeUsed;
        return $this;
    }

    /**
     * @var boolean
     *
     * @ORM\Column(name="team2_used", type="boolean")
     */
    private $teamTwoUsed;

    /**
     * @var boolean
     *
     * @ORM\Column(name="team3_used", type="boolean")
     */
    private $teamThreeUsed;

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     * @return Hint
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
        return $this;
    }

    /**
     * @var string
     *
     * @ORM\Column(name="text", type="text")
     */
    private $text;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Hint
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
     * Set text
     *
     * @param string $text
     * @return Hint
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string 
     */
    public function getText()
    {
        return $this->text;
    }
}
