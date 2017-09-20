<?php
/**
 * Group entity.
 */
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Group.
 *
 * @package AppBundle\Entity
 *
 * @ORM\Table(
 *     name="activity_groups"
 * )
 * @ORM\Entity(
 *     repositoryClass="AppBundle\Repository\GroupRepository"
 * )
 * @UniqueEntity(
 *     groups={"group-default"},
 *     fields={"name", "year"}
 * )
 */
class Group
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
     * Name.
     *
     * @var string $name
     *
     * @ORM\Column(
     *     name="name",
     *     type="string",
     *     length=128,
     *     nullable=false,
     * )
     * @Assert\NotBlank(
     *     groups={"group-default"}
     * )
     * @Assert\Length(
     *     groups={"group-default"},
     *     min="1",
     *     max="128",
     * )
     */
    protected $name;

    /**
     * Group year
     *
     * @var Group $year
     *
     * @ORM\ManyToOne(
     *     targetEntity="AppBundle\Entity\Year",
     *     inversedBy="groups"
     * )
     * @ORM\JoinColumn(
     *     name="year_id",
     *     referencedColumnName="id",
     *     nullable=false,
     *     onDelete="CASCADE"
     * )
     * @Assert\NotBlank(
     *     groups={"group-default"}
     * )
     */
    protected $year;

    /**
     * Users in group
     *
     * @var ArrayCollection $users
     *
     * @ORM\OneToMany(
     *     targetEntity="User",
     *     mappedBy="group"
     * )
     */
    protected $users;

    /**
     * Presence allow group
     *
     * @var Group $presence
     *
     * @ORM\OneToOne(
     *     targetEntity="AppBundle\Entity\Presence",
     *     mappedBy="group",
     * )
     */
    protected $presence;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->users = new ArrayCollection();
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
     * Set name
     *
     * @param string $name
     * @return Group
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
     * Set year
     *
     * @param \AppBundle\Entity\Year $year
     * @return Group
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
     * Add users
     *
     * @param \AppBundle\Entity\User $users
     * @return Group
     */
    public function addUser(\AppBundle\Entity\User $users)
    {
        $this->users[] = $users;

        return $this;
    }

    /**
     * Remove users
     *
     * @param \AppBundle\Entity\User $users
     */
    public function removeUser(\AppBundle\Entity\User $users)
    {
        $this->users->removeElement($users);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * To string
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }

    /**
     * Set presence
     *
     * @param \AppBundle\Entity\Group $presence
     * @return Group
     */
    public function setPresence(\AppBundle\Entity\Group $presence = null)
    {
        $this->presence = $presence;

        return $this;
    }

    /**
     * Get presence
     *
     * @return \AppBundle\Entity\Group
     */
    public function getPresence()
    {
        return $this->presence;
    }
}
