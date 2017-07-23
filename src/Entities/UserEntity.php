<?php
namespace Entities;

Class UserEntity
{
    protected $id;

    protected $login;

    protected $password;

    protected $avatar;

    public function setId ($id)
    {
        $this->id = $id;

        return $this;
    }

    public function getId ()
    {
        return $this->id;
    }

    public function setLogin ($login)
    {
        $this->login = $login;

        return $this;
    }

    public function getLogin ()
    {
        return $this->login;
    }

    public function setAvatar ($avatar)
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getAvatar ()
    {
        return $this->avatar;
    }


}