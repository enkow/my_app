<?php
/**
 * Result entity.
 */
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Result.
 *
 * @package AppBundle\Entity
 *
 * @ORM\Table(
 *     name="results"
 * )
 * @ORM\Entity(
 *     repositoryClass="AppBundle\Repository\ResultRepository"
 * )
 */
class Result
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
     * Points.
     *
     * @var integer $points
     *
     * @ORM\Column(
     *     name="points",
     *     type="integer",
     *     nullable=false,
     * )
     */
    protected $points;

    /**
     * Test result
     *
     * @var Result $test
     *
     * @ORM\ManyToOne(
     *     targetEntity="AppBundle\Entity\Test",
     *     inversedBy="results"
     * )
     * @ORM\JoinColumn(
     *     name="test_id",
     *     referencedColumnName="id",
     *     nullable=false,
     *     onDelete="CASCADE"
     * )
     * @Assert\NotBlank(
     *     groups={"result-default"}
     * )
     */
    protected $test;

    /**
     * Result user
     *
     * @var Result $user
     *
     * @ORM\ManyToOne(
     *     targetEntity="AppBundle\Entity\User",
     *     inversedBy="results"
     * )
     * @ORM\JoinColumn(
     *     name="user_id",
     *     referencedColumnName="id",
     *     nullable=false,
     *     onDelete="CASCADE"
     * )
     * @Assert\NotBlank(
     *     groups={"result-default"}
     * )
     */
    protected $user;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->groups = new ArrayCollection();
        $this->active = false;
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
     * Set points
     *
     * @param integer $points
     * @return Result
     */
    public function setPoints($points)
    {
        $this->points = $points;

        return $this;
    }

    /**
     * Get points
     *
     * @return integer
     */
    public function getPoints()
    {
        return $this->points;
    }

    /**
     * Set test
     *
     * @param \AppBundle\Entity\Test $test
     * @return Result
     */
    public function setTest(\AppBundle\Entity\Test $test)
    {
        $this->test = $test;

        return $this;
    }

    /**
     * Get test
     *
     * @return \AppBundle\Entity\Test
     */
    public function getTest()
    {
        return $this->test;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     * @return Result
     */
    public function setUser(\AppBundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}
