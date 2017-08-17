<?php
namespace Entities;

class UserEntity
{
    protected $id;

    protected $login;

    protected $password;

    protected $avatar;

    protected $liked_comments;

    public function __construct()
    {
        $this->liked_comments = [];
    }

    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setLogin($login)
    {
        $this->login = $login;

        return $this;
    }

    public function getLogin()
    {
        return $this->login;
    }

    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getAvatar()
    {
        return $this->avatar;
    }

    public function addLikedComment($liked_comment)
    {
        $this->liked_comments[] = $liked_comment;

        return $this;
    }

    public function getLikedComments()
    {
        return $this->liked_comments;
    }
}
