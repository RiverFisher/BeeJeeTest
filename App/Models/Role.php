<?php

namespace App\Models;

/**
 * Role
 */
class Role extends \Core\Model
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

//    /**
//     * @var array
//     */
//    private $roles;

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
     *
     * @return Role
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

//    /**
//     * Set roles
//     *
//     * @param array $roles
//     *
//     * @return Role
//     */
//    public function setRoles($roles)
//    {
//        $this->roles = $roles;
//
//        return $this;
//    }
//
//    /**
//     * Get roles
//     *
//     * @return array
//     */
//    public function getRoles()
//    {
//        return $this->roles;
//    }
}
