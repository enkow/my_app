<?php
/**
 * Tag entity.
 */
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Tag.
 *
 * @package AppBundle\Entity
 *
 * @ORM\Table(
 *     name="tags"
 * )
 * @ORM\Entity(
 *     repositoryClass="AppBundle\Repository\TagRepository"
 * )
 * @UniqueEntity(
 *     groups={"tag-default"},
 *     fields={"name"}
 * )
 */
class Tag
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
     *
     * @Assert\NotBlank(
     *     groups={"tag-default"}
     * )
     * @Assert\Length(
     *     groups={"tag-default"},
     *     min="3",
     *     max="128",
     * )
     */
    protected $name;
}
