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
     *     groups={"user-default", "user-register"},
     * )
     * @Assert\Length(
     *     groups={"user-default", "user-register"},
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
     *     groups={"user-default", "user-register"},
     * )
     * @Assert\Length(
     *     groups={"user-default", "user-register"},
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
     *     groups={"user-register", "user-reset"},
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
     * User group
     *
     * @var User $group
     *
     * @ORM\ManyToOne(
     *     targetEntity="AppBundle\Entity\Group",
     *     inversedBy="users"
     * )
     * @ORM\JoinColumn(
     *     name="group_id",
     *     referencedColumnName="id",
     *     nullable=true,
     *     onDelete="CASCADE"
     * )
     * @Assert\NotBlank(
     *     groups={"user-register"}
     * )
     */
    protected $group;


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
        return true;
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
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Returns the roles granted to the user.
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return (Role|string)[] The user roles
     */
    public function getRoles()
    {
        return [$this->getRole()->getName()];
    }

    /**
     * Get salt
     *
     * @return string
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
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
     * Set username
     *
     * @param string $username
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Set approved
     *
     * @param boolean $approved
     * @return User
     */
    public function setApproved($approved)
    {
        $this->approved = $approved;

        return $this;
    }

    /**
     * Get approved
     *
     * @return boolean
     */
    public function getApproved()
    {
        return $this->approved;
    }

    /**
     * Set resetToken
     *
     * @param string $resetToken
     * @return User
     */
    public function setResetToken($resetToken)
    {
        $this->resetToken = $resetToken;

        return $this;
    }

    /**
     * Get resetToken
     *
     * @return string
     */
    public function getResetToken()
    {
        return $this->resetToken;
    }

    /**
     * Set confirmToken
     *
     * @param string $confirmToken
     * @return User
     */
    public function setConfirmToken($confirmToken)
    {
        $this->confirmToken = $confirmToken;

        return $this;
    }

    /**
     * Get confirmToken
     *
     * @return string
     */
    public function getConfirmToken()
    {
        return $this->confirmToken;
    }

    /**
     * Set lastLogin
     *
     * @param \DateTime $lastLogin
     * @return User
     */
    public function setLastLogin($lastLogin)
    {
        $this->lastLogin = $lastLogin;

        return $this;
    }

    /**
     * Get lastLogin
     *
     * @return \DateTime
     */
    public function getLastLogin()
    {
        return $this->lastLogin;
    }

    /**
     * Set role
     *
     * @param \AppBundle\Entity\Role $role
     * @return User
     */
    public function setRole(\AppBundle\Entity\Role $role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return \AppBundle\Entity\Role
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set group
     *
     * @param \AppBundle\Entity\Group $group
     * @return User
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
}
