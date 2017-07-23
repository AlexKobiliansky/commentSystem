<?php
namespace Entities;

Class CommentsEntity
{
    protected $id;

    protected $content;

    protected $date_created;

    protected $date_updated;

    protected $user_id;

    protected $user_login;


    public function setId ($id)
    {
        $this->id = $id;

        return $this;
    }

    public function getId ()
    {
        return $this->id;
    }


    public function setContent ($content)
    {
        $this->content = $content;

        return $this;
    }

    public function getContent ()
    {
        return $this->content;
    }

    public function setDateCreated ($date_created)
    {
        $this->date_created = $date_created;

        return $this;
    }

    public function getDateCreated ()
    {
        return $this->date_created;
    }

    public function setDateUpdated ($date_updated)
    {
        $this->date_updated = $date_updated;

        return $this;
    }

    public function getDateUpdated ()
    {
        return $this->date_updated;
    }

    public function setUserId ($user_id)
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getUserId ()
    {
        return $this->user_id;
    }

    public function setUserLogin ($user_login)
    {
        $this->user_login = $user_login;

        return $this;
    }

    public function getUserLogin ()
    {
        return $this->user_login;
    }
}

