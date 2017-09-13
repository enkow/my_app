<?php
/**
 * User
 */
namespace AppBundle\Entity;

use AppBundle\Interfaces\PropertyInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class User
 *
 * @ORM\Table(
 *     name="users"
 * )
 * @ORM\Entity(
 *     repositoryClass="AppBundle\Repository\UserRepository"
 * )
 * @UniqueEntity(
 *     "username",
 *     message="login_not_unique",
 *     groups={"user-default"},
 * )
 * @UniqueEntity(
 *     "email",
 *     message="email_not_unique",
 *     groups={"user-default"},
 * )
 */
class User implements AdvancedUserInterface, \Serializable
{

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
     * User username/login
     *
     * @var string $username
     *
     * @ORM\Column(
     *     name="username",
     *     type="string",
     *     length=255,
     *     nullable=false,
     *     unique=true
     * )
     * @Assert\NotBlank(
     *     groups={"user-default"},
     * )
     * @Assert\Length(
     *     groups={"user-default"},
     *     min="3",
     *     max="255",
     * )
     */
    protected $username;

    /**
     * Email
     *
     * @var string $email
     *
     * @ORM\Column(
     *     name="email",
     *     type="string",
     *     length=255,
     *     nullable=false,
     *     unique=true
     * )
     * @Assert\NotBlank(
     *     groups={"user-default"},
     * )
     * @Assert\Length(
     *     groups={"user-default"},
     *     min="3",
     *     max="255",
     * )
     */
    protected $email;

    /**
     * User password
     *
     * @var string $password
     *
     * @ORM\Column(
     *     name="password",
     *     type="string",
     *     length=255,
     *     nullable=false
     * )
     * @Assert\Length(
     *     groups={"user-default"},
     *     min="8",
     *     max="255",
     * )
     */
    protected $password;

    /**
     * Approved
     *
     * @var boolean $approved
     *
     * @ORM\Column(
     *     name="approved",
     *     type="boolean"
     * )
     */
    protected $approved;

    /**
     * Reset token
     *
     * @var string $resetToken
     *
     * @ORM\Column(
     *     name="reset_token",
     *     type="string",
     *     length=255,
     *     nullable=true
     * )
     */
    protected $resetToken;

    /**
     * Confirm token
     *
     * @var string $resetToken
     *
     * @ORM\Column(
     *     name="confirm_token",
     *     type="string",
     *     length=255,
     *     nullable=true
     * )
     */
    protected  $confirmToken;

    /**
     * Last login
     *
     * @var string $lastLogin
     *
     * @ORM\Column(
     *     name="last_login",
     *     type="datetime",
     *     length=255,
     *     nullable=true
     * )
     */
    protected  $lastLogin;

    /**
     * User role
     *
     * @var Role $role
     *
     * @ORM\ManyToOne(
     *     targetEntity="AppBundle\Entity\Role"
     * )
     * @ORM\JoinColumn(
     *     name="role_id",
     *     referencedColumnName="id",
     *     nullable=false,
     *     onDelete="CASCADE"
     * )
     */
    protected $role;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->approved = false;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
    }

    /**
     * Check if account is expired
     *
     * @return boolean Result
     */
    public function isAccountNonExpired()
    {
        return true;
    }

    /**
     * Check if account is locked
     *
     * @return boolean Result
     */
    public function isAccountNonLocked()
    {
        return (bool) $this->approved;
    }

    /**
     * Check if credentials are expired
     *
     * @return boolean Result
     */
    public function isCredentialsNonExpired()
    {
        return true;
    }

    /**
     * Check if account is enabled
     *
     * @return boolean Result
     */
    public function isEnabled()
    {
        return (bool) !$this->confirmToken;
    }

    /**
     * Serialize
     *
     * @return string Result
     */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
            $this->approved,
            $this->confirmToken,
        ));
    }

    /**
     * Unserialize
     *
     * @param \Serializable $serialized Serialized string
     */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
            $this->approved,
            $this->confirmToken,
        ) = unserialize($serialized);
    }
}
