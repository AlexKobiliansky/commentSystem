<?php
namespace Entities;

Class CommentsEntity
{
    protected $id;

    protected $content;

    protected $date_created;

    protected $date_updated;


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

}

