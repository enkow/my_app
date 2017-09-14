<?php
/**
 * Year entity.
 */
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Year.
 *
 * @package AppBundle\Entity
 *
 * @ORM\Table(
 *     name="years"
 * )
 * @ORM\Entity(
 *     repositoryClass="AppBundle\Repository\YearRepository"
 * )
 */
class Year
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
     * Start.
     *
     * @var string $start
     *
     * @ORM\Column(
     *     name="start",
     *     type="integer",
     *     nullable=false,
     * )
     * @Assert\NotBlank(
     *     groups={"year-default"}
     * )
     * @Assert\Length(
     *     groups={"year-default"},
     *     min="4",
     *     max="4",
     * )
     */
    protected $start;

    /**
     * End.
     *
     * @var string $end
     *
     * @ORM\Column(
     *     name="end",
     *     type="integer",
     *     nullable=false,
     * )
     * @Assert\NotBlank(
     *     groups={"year-default"}
     * )
     * @Assert\Length(
     *     groups={"year-default"},
     *     min="4",
     *     max="4",
     * )
     */
    protected $end;

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
     * Groups in year
     *
     * @var ArrayCollection $groups
     *
     * @ORM\OneToMany(
     *     targetEntity="Group",
     *     mappedBy="year"
     * )
     */
    protected $groups;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->groups = new ArrayCollection();
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
     * Set start
     *
     * @param integer $start
     * @return Year
     */
    public function setStart($start)
    {
        $this->start = $start;

        return $this;
    }

    /**
     * Get start
     *
     * @return integer
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * Set end
     *
     * @param integer $end
     * @return Year
     */
    public function setEnd($end)
    {
        $this->end = $end;

        return $this;
    }

    /**
     * Get end
     *
     * @return integer
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * Add groups
     *
     * @param \AppBundle\Entity\Group $groups
     * @return Year
     */
    public function addGroup(\AppBundle\Entity\Group $groups)
    {
        $this->groups[] = $groups;

        return $this;
    }

    /**
     * Remove groups
     *
     * @param \AppBundle\Entity\Group $groups
     */
    public function removeGroup(\AppBundle\Entity\Group $groups)
    {
        $this->groups->removeElement($groups);
    }

    /**
     * Get groups
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGroups()
    {
        return $this->groups;
    }

    public function __toString()
    {
        return sprintf('%s / %s', $this->getStart(), $this->getEnd());
    }

    /**
     * Set active
     *
     * @param boolean $active
     * @return Year
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
}
