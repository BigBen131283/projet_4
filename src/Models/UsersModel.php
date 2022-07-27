<?php

namespace App\Models;

use App\Core\Model;
use App\Repository\UsersDB;

class UsersModel extends Model
{
    protected $id;
    protected $email;
    protected $password;
    protected $pseudo;
    protected $status;
    protected $role;
    protected $profile_picture;
    protected $isLogged = false;

    public function __construct($id = null)
    {
        $this->table = 'users';

        if($id)
        {
            $usersDB = new UsersDB();
            $user = $usersDB->getUser($id);
            if($user)
            {
                $this->id = $user->id;
                $this->email = $user->email;
                $this->pseudo = $user->pseudo;
                $this->role = $user->role;
                $this->profile_picture = $user->profile_picture;
                $this->isLogged = true;
            }
        }
    }

    public function isLogged()
    {
        return $this->isLogged;
    }

    public function isAdmin()
    {
        if(intval($this->role)===10)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * Get the value of id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @param [type] $id
     * @return self
     */
    public function setId($id):self
    {
        $this->id = $id;
        return $this;
    }
    /**
     * Get the value of email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the value of email
     *
     * @param [type] $email
     * @return self
     */
    public function setEmail($email):self
    {
        $this->email = $email;
        return $this;
    }

    /**
     * Get the value of password
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set the value of password
     *
     * @param [type] $password
     * @return self
     */
    public function setPassword($password):self
    {
        $this->password = $password;
        return $this;
    }

    /**
     * Get the value of pseudo
     */
    public function getPseudo()
    {
        return $this->pseudo;
    }

    /**
     * Set the value of pseudo
     *
     * @param [type] $pseudo
     * @return self
     */
    public function setPseudo($pseudo):self
    {
        $this->pseudo = $pseudo;
        return $this;
    }

    /**
     * Get the value of status
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set the value of status
     *
     * @param [type] $status
     * @return self
     */
    public function setStatus($status):self
    {
        $this->status = $status;
        return $this;
    }

    /**
     * Get the value of role
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set the value of role
     *
     * @param [type] $role
     * @return self
     */
    public function setRole($role):self
    {
        $this->role = $role;
        return $this;
    }
    
    /**
     * Get the value of profile_picture
     */
    public function getProfile_picture()
    {
        return $this->profile_picture;
    }

    /**
     * Set the value of profile_picture
     *
     * @param [type] $profile_picture
     * @return self
     */
    public function setProfile_picture($profile_picture):self
    {
        $this->profile_picture = $profile_picture;
        return $this;
    }
}

?>