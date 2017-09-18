<?php
/**
 * Presence entity.
 */
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Presence.
 *
 * @package AppBundle\Entity
 *
 * @ORM\Table(
 *     name="presences"
 * )
 * @ORM\Entity(
 *     repositoryClass="AppBundle\Repository\PresenceRepository"
 * )
 */
class Presence
{
    /**
     * Use constants to define configuration options that rarely change instead
     * of specifying them in app/config/config.yml.
     * See http://symfony.com/doc/current/best_practices/configuration.html#constants-vs-configuration-options
     */
    const NUM_ITEMS = 10;

    /**
     * Primary key.
     *
     * @var integer $id
     *
     * @ORM\Id
     * @ORM\Column(
     *     name="id",
     *     type="integer",
     *     nullable=false,
     *     options={"unsigned"=true},
     * )
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * Date.
     *
     * @var string $date
     *
     * @ORM\Column(
     *     name="classes_date",
     *     type="date",
     *     nullable=false,
     * )
     */
    protected $date;

    /**
     * Is active.
     *
     * @var boolean $active
     *
     * @ORM\Column(
     *     name="is_active",
     *     type="boolean"
     * )
     */
    protected $active;

    /**
     * Allowed ip.
     *
     * @var string $ip
     *
     * @ORM\Column(
     *     name="ip",
     *     type="text",
     *     nullable=true,
     * )
     */
    protected $ip;

    /**
     * Presence allow group
     *
     * @var Presence $group
     *
     * @ORM\OneToOne(
     *     targetEntity="AppBundle\Entity\Group",
     *     inversedBy="presence"
     * )
     * @ORM\JoinColumn(
     *     name="allowed_group_id",
     *     referencedColumnName="id",
     *     nullable=true,
     *     onDelete="CASCADE"
     * )
     */
    protected $group;

    /**
     * Presence year
     *
     * @var Presence $year
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Year")
     * @ORM\JoinColumn(
     *     name="year_id",
     *     referencedColumnName="id",
     *     nullable=false,
     *     onDelete="CASCADE"
     * )
     */
    protected $year;

    /**
     * Presence presences
     *
     * @var ArrayCollection $userPresences
     *
     * @ORM\OneToMany(
     *     targetEntity="UserPresence",
     *     mappedBy="presence"
     * )
     */
    protected $userPresences;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->date = new \DateTime();
        $this->active = false;
        $this->userPresences = new ArrayCollection();
    }

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
     * Set date
     *
     * @param \DateTime $date
     * @return Presence
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set active
     *
     * @param boolean $active
     * @return Presence
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return boolean
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set group
     *
     * @param \AppBundle\Entity\Group $group
     * @return Presence
     */
    public function setGroup(\AppBundle\Entity\Group $group = null)
    {
        $this->group = $group;

        return $this;
    }

    /**
     * Get group
     *
     * @return \AppBundle\Entity\Group
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * Set year
     *
     * @param \AppBundle\Entity\Year $year
     * @return Presence
     */
    public function setYear(\AppBundle\Entity\Year $year)
    {
        $this->year = $year;

        return $this;
    }

    /**
     * Get year
     *
     * @return \AppBundle\Entity\Year
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Add userPresences
     *
     * @param \AppBundle\Entity\UserPresence $userPresences
     * @return Presence
     */
    public function addUserPresence(\AppBundle\Entity\UserPresence $userPresences)
    {
        $this->userPresences[] = $userPresences;

        return $this;
    }

    /**
     * Remove userPresences
     *
     * @param \AppBundle\Entity\UserPresence $userPresences
     */
    public function removeUserPresence(\AppBundle\Entity\UserPresence $userPresences)
    {
        $this->userPresences->removeElement($userPresences);
    }

    /**
     * Get userPresences
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUserPresences()
    {
        return $this->userPresences;
    }

    /**
     * Set ip
     *
     * @param string $ip
     * @return Presence
     */
    public function setIp($ip)
    {
        $this->ip = $ip;

        return $this;
    }

    /**
     * Get ip
     *
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }
}
