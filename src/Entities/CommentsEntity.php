<?php
namespace Entities;

class CommentsEntity
{
    protected $id;

    protected $content;

    protected $date_created;

    protected $date_updated;

    protected $user_id;

    protected $user_login;

    protected $user_avatar;

    protected $parent;

    protected $children;

    protected $likers;

    protected $likes;

    protected $is_last_child;


    public function __construct()
    {
        $this->children = [];
        $this->likers = [];
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

    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setDateCreated($date_created)
    {
        $this->date_created = $date_created;

        return $this;
    }

    public function getDateCreated()
    {
        return $this->date_created;
    }

    public function setDateUpdated($date_updated)
    {
        $this->date_updated = $date_updated;

        return $this;
    }

    public function getDateUpdated()
    {
        return $this->date_updated;
    }

    public function setUserId($user_id)
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getUserId()
    {
        return $this->user_id;
    }

    public function setUserLogin($user_login)
    {
        $this->user_login = $user_login;

        return $this;
    }

    public function getUserLogin()
    {
        return $this->user_login;
    }

    public function setUserAvatar($user_avatar)
    {
        $this->user_avatar = $user_avatar;

        return $this;
    }

    public function getUserAvatar()
    {
        return $this->user_avatar;
    }

    public function setParent($parent)
    {
        $this->parent = $parent;

        return $this;
    }

    public function getParent()
    {
        return $this->parent;
    }

    public function addChild($child)
    {
        $this->children[] = $child;

        return $this;
    }

    public function getChildren()
    {
        return $this->children;
    }

    public function setLikes($likes)
    {
        $this->likes = $likes;
    }

    public function getLikes()
    {
        return $this->likes;
    }

    public function addLikedUser($liked_user)
    {
        $this->likers[] = $liked_user;

        return $this;
    }

    public function getLikedUser()
    {
        return $this->likers;
    }

    public function setLastChild($is_last_child)
    {
        $this->is_last_child = $is_last_child;
    }

    public function getLastChild()
    {
        return $this->is_last_child;
    }
}
