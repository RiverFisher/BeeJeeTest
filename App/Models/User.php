<?php

namespace App\Models;

/**
 * User
 */
class User extends \Core\Model
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $password;

    /**
     * @var array
     */
    private $roles;

    /**
     * @var string
     */
    private $firstName;

    /**
     * @var string
     */
    private $lastName;

    /**
     * @var string
     */
    private $briefInfo;

    /**
     * @var \DateTime
     */
    private $dateOfCreation;

    /**
     * @var \DateTime
     */
    private $dateOfChange;

    /**
     * User constructor
     *
     * @param int|null $id
     * @param string|null $username
     * @param string|null $email
     * @param string|null $password
     * @param array|null $roles
     * @param string|null $firstName
     * @param string|null $lastName
     * @param string|null $briefInfo
     * @param \DateTime|null $dateOfCreation
     * @param \DateTime|null $dateOfChange
     */
    public function __construct(
        int $id = null,
        string $username = null,
        string $email = null,
        string $password = null,
        array $roles = null,
        string $firstName = null,
        string $lastName = null,
        string $briefInfo = null,
        \DateTime $dateOfCreation = null,
        \DateTime $dateOfChange = null
    )
    {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->roles = $roles;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->briefInfo = $briefInfo;
        $this->dateOfCreation = $dateOfCreation;
        $this->dateOfChange = $dateOfChange;
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
     *
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
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
     * Set email
     *
     * @param string $email
     *
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
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
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
     * Set roles
     *
     * @param array $roles
     *
     * @return User
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Get roles
     *
     * @return array
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @param string $role
     * @return bool
     */
    public function hasRole(string $role)
    {
        return in_array($role, $this->roles) ? true : false;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set briefInfo
     *
     * @param string $briefInfo
     *
     * @return User
     */
    public function setBriefInfo($briefInfo)
    {
        $this->briefInfo = $briefInfo;

        return $this;
    }

    /**
     * Get briefInfo
     *
     * @return string
     */
    public function getBriefInfo()
    {
        return $this->briefInfo;
    }

    /**
     * Set dateOfCreation
     *
     * @param \DateTime $dateOfCreation
     *
     * @return User
     */
    public function setDateOfCreation($dateOfCreation)
    {
        $this->dateOfCreation = $dateOfCreation;

        return $this;
    }

    /**
     * Get dateOfCreation
     *
     * @return \DateTime
     */
    public function getDateOfCreation()
    {
        return $this->dateOfCreation;
    }

    /**
     * Set dateOfChange
     *
     * @param \DateTime $dateOfChange
     *
     * @return User
     */
    public function setDateOfChange($dateOfChange)
    {
        $this->dateOfChange = $dateOfChange;

        return $this;
    }

    /**
     * Get dateOfChange
     *
     * @return \DateTime
     */
    public function getDateOfChange()
    {
        return $this->dateOfChange;
    }
}
