<?php

namespace Repositories;
use Entities\UserEntity;



class CommentsRepository
{
    private $connector;

    /**
     * CommentsRepository constructor.
     * Initialize the database connection with sql server via given credentials
     * @param $connector
     */
    public function __construct($connector)
    {
        $this->connector = $connector;

    }

    public function findAll($limit = 1000, $offset = 0)
    {
        $statement = $this->connector->getPdo()->prepare('SELECT c.id, c.content, c.user_id, u.login, u.avatar, c.parent, c.likes 
            FROM comments c INNER JOIN user u ON u.id = c.user_id ORDER BY c.id DESC LIMIT :limit OFFSET :offset');
        $statement->bindValue(':limit', (int) $limit, \PDO::PARAM_INT);
        $statement->bindValue(':offset', (int) $offset, \PDO::PARAM_INT);
        $statement->execute();
        return $this->fetchCommentsData($statement);
    }

    public function fetchCommentsData($statement)
    {

        $results = [];
        while ($result = $statement->fetch()) {

            $object = new \Entities\CommentsEntity;
            //
            $children = $this->connector->getPdo()->prepare('SELECT c.id, c.content, c.user_id, u.login, u.avatar, c.parent, c.likes 
                FROM comments c INNER JOIN user u ON u.id = c.user_id AND c.parent = :parent');
            $children->bindValue(':parent', (int) $result['id'], \PDO::PARAM_INT);
            $children->execute();
            //
            $results[] =
                $object->setId($result['id']);
                $object->setContent($result['content']);
                $object->setUserId($result['user_id']);
                $object->setUserLogin($result['login']);
                $object->setUserAvatar($result['avatar']);
                $object->setParent($result['parent']);
                $object->setLikes($result['likes']);


                while ($child = $children->fetch()){
                    $childObject = new \Entities\CommentsEntity;

                    $children1 = $this->connector->getPdo()->prepare('SELECT c.id, c.content, c.user_id, u.login, u.avatar, c.parent, c.likes 
                        FROM comments c INNER JOIN user u ON u.id = c.user_id AND c.parent = :parent');
                    $children1->bindValue(':parent', (int) $child['id'], \PDO::PARAM_INT);
                    $children1->execute();


                    $childObject->setId($child['id']);
                    $childObject->setContent($child['content']);
                    $childObject->setUserId($child['user_id']);
                    $childObject->setUserLogin($child['login']);
                    $childObject->setUserAvatar($child['avatar']);
                    $childObject->setParent($child['parent']);
                    $childObject->setLikes($result['likes']);

                    while ($child1 = $children1->fetch()) {
                        $childObject1 = new \Entities\CommentsEntity;

                        $childObject1->setId($child['id']);
                        $childObject1->setContent($child['content']);
                        $childObject1->setUserId($child['user_id']);
                        $childObject1->setUserLogin($child['login']);
                        $childObject1->setUserAvatar($child['avatar']);
                        $childObject1->setParent($child['parent']);
                        $childObject1->setLikes($result['likes']);

                        $childObject->addChild($childObject1);
                    }

                    $object->addChild($childObject);
                }

        }



        return $results;
    }

    public function find($id)
    {
        $statement = $this->connector->getPdo()->prepare('SELECT c.id, c.content, c.user_id, u.login, u.avatar, c.parent, c.likes
            FROM comments c INNER JOIN user u ON u.id = c.user_id WHERE c.id = :id LIMIT 1');
        $statement->bindValue(':id', (int) $id, \PDO::PARAM_INT);
        $statement->execute();
        $commentsData = $this->fetchCommentsData($statement);

        return $commentsData[0];

    }

    public function insert(array $commentsData)
    {
        $statement = $this->connector->getPdo()->prepare('INSERT INTO comments (content, user_id, likes) VALUES(:content, :userId, :likes)');
        $statement->bindValue(':content', $commentsData['content']);
        $statement->bindValue(':userId', (int) $_SESSION['user_id'], \PDO::PARAM_INT);
        $statement->bindValue(':likes', (int) $commentsData['likes'], \PDO::PARAM_INT);

        return $statement->execute();
    }

    public function update(array $commentsData)
    {
        $statement = $this->connector->getPdo()->prepare("UPDATE comments SET content = :content, likes = :likes WHERE id = :id");

        $statement->bindValue(':content', $commentsData['content'], \PDO::PARAM_STR);
        $statement->bindValue(':id', $commentsData['id'], \PDO::PARAM_INT);
        $statement->bindValue(':likes', $commentsData['likes'], \PDO::PARAM_INT);

        return $statement->execute();
    }

    public function remove(array $commentsData)
    {
        $statement = $this->connector->getPdo()->prepare("DELETE FROM comments WHERE id = :id");
        $statement->bindValue(':id', $commentsData['id'], \PDO::PARAM_INT);

        return $statement->execute();
    }

    public function createSubcomment(array $commentsData)
    {
        $statement = $this->connector->getPdo()->prepare('INSERT INTO comments (content, user_id, parent) VALUES(:content, :userId, :parent)');
        $statement->bindValue(':content', $commentsData['content']);
        $statement->bindValue(':userId', (int) $_SESSION['user_id'], \PDO::PARAM_INT);
        $statement->bindValue(':parent', (int) $commentsData['parent_id'], \PDO::PARAM_INT);

        return $statement->execute();
    }

}