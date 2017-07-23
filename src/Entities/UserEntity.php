<?php
namespace Entities;

Class UserEntity
{
    protected $id;

    protected $login;

    protected $password;

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


}