<?php
/**
 * Test entity.
 */
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Test.
 *
 * @package AppBundle\Entity
 *
 * @ORM\Table(
 *     name="tests"
 * )
 * @ORM\Entity(
 *     repositoryClass="AppBundle\Repository\TestRepository"
 * )
 */
class Test
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
     *     nullable=false,
     * )
     * @Assert\NotBlank(
     *     groups={"test-default"}
     * )
     */
    protected $name;

    /**
     * Content.
     *
     * @var string $content
     *
     * @ORM\Column(
     *     name="content",
     *     type="text",
     *     nullable=false,
     * )
     * @Assert\NotBlank(
     *     groups={"test-default"}
     * )
     */
    protected $content;

    /**
     * Start time.
     *
     * @var string $start
     *
     * @ORM\Column(
     *     name="start_time",
     *     type="datetime",
     *     nullable=true,
     * )
     */
    protected $start;

    /**
     * MAx points.
     *
     * @var integer $max
     *
     * @ORM\Column(
     *     name="max_points",
     *     type="integer",
     *     nullable=false,
     * )
     */
    protected $max;

    /**
     * Test's Questions
     *
     * @var ArrayCollection $questions
     *
     * @ORM\ManyToMany(targetEntity="Question")
     * @ORM\JoinTable(
     *     name="tests_questions",
     *     joinColumns={
     *         @ORM\JoinColumn(
     *             name="test_id",
     *             referencedColumnName="id"
     *         )
     *     },
     *     inverseJoinColumns={
     *         @ORM\JoinColumn(
     *             name="question_id",
     *             referencedColumnName="id"
     *         )
     *     }
     * )
     */
    protected $questions;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->questions = new ArrayCollection();
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
     * @return Test
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
     * Set content
     *
     * @param string $content
     * @return Test
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set start
     *
     * @param \DateTime $start
     * @return Test
     */
    public function setStart($start)
    {
        $this->start = $start;

        return $this;
    }

    /**
     * Get start
     *
     * @return \DateTime
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * Add questions
     *
     * @param \AppBundle\Entity\Question $questions
     * @return Test
     */
    public function addQuestion(\AppBundle\Entity\Question $questions)
    {
        $this->questions[] = $questions;

        return $this;
    }

    /**
     * Remove questions
     *
     * @param \AppBundle\Entity\Question $questions
     */
    public function removeQuestion(\AppBundle\Entity\Question $questions)
    {
        $this->questions->removeElement($questions);
    }

    /**
     * Get questions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getQuestions()
    {
        return $this->questions;
    }

    /**
     * Set max
     *
     * @param integer $max
     * @return Test
     */
    public function setMax($max)
    {
        $this->max = $max;

        return $this;
    }

    /**
     * Get max
     *
     * @return integer 
     */
    public function getMax()
    {
        return $this->max;
    }
}
