<?php

namespace Repositories;

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
        $statement = $this->connector->getPdo()->prepare('SELECT c.id, c.content, c.user_id, u.login, u.avatar, c.parent, c.likes, c.date_created 
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

            $children = $this->connector->getPdo()->prepare('SELECT c.id, c.content, c.user_id, u.login, u.avatar, c.parent, c.likes, c.date_created 
                FROM comments c INNER JOIN user u ON u.id = c.user_id AND c.parent = :parent');
            $children->bindValue(':parent', (int) $result['id'], \PDO::PARAM_INT);
            $children->execute();

            $likedUsers = $this->connector->getPdo()->prepare('SELECT l.user_id FROM user u, likes l, comments c WHERE u.id=l.user_id AND l.comment_id=c.id AND c.id = :commentId');
            $likedUsers->bindValue(':commentId', (int) $result['id'], \PDO::PARAM_INT);
            $likedUsers->execute();

            $results[] =
                $object->setId($result['id']);
            $object->setContent($result['content']);
            $object->setUserId($result['user_id']);
            $object->setUserLogin($result['login']);
            $object->setUserAvatar($result['avatar']);
            $object->setParent($result['parent']);
            $object->setLikes($result['likes']);
            $object->setDateCreated($result['date_created']);


            while ($user = $likedUsers->fetch()) {
                $object->addLikedUser($user['user_id']);
            }

            while ($child = $children->fetch()) {
                $childObject = new \Entities\CommentsEntity;

                $children1 = $this->connector->getPdo()->prepare('SELECT c.id, c.content, c.user_id, u.login, u.avatar, c.parent, c.likes, c.date_created 
                        FROM comments c INNER JOIN user u ON u.id = c.user_id AND c.parent = :parent');
                $children1->bindValue(':parent', (int) $child['id'], \PDO::PARAM_INT);
                $children1->execute();

                $likedUsers1 = $this->connector->getPdo()->prepare('SELECT l.user_id FROM user u, likes l, comments c WHERE u.id=l.user_id AND l.comment_id=c.id AND c.id = :commentId');
                $likedUsers1->bindValue(':commentId', (int) $child['id'], \PDO::PARAM_INT);
                $likedUsers1->execute();

                $childObject->setId($child['id']);
                $childObject->setContent($child['content']);
                $childObject->setUserId($child['user_id']);
                $childObject->setUserLogin($child['login']);
                $childObject->setUserAvatar($child['avatar']);
                $childObject->setParent($child['parent']);
                $childObject->setLikes($child['likes']);
                $childObject->setDateCreated($child['date_created']);

                while ($user1 = $likedUsers1->fetch()) {
                    $childObject->addLikedUser($user1['user_id']);
                }

                while ($child1 = $children1->fetch()) {
                    $childObject1 = new \Entities\CommentsEntity;

                    $likedUsers2 = $this->connector->getPdo()->prepare('SELECT l.user_id FROM user u, likes l, comments c WHERE u.id=l.user_id AND l.comment_id=c.id AND c.id = :commentId');
                    $likedUsers2->bindValue(':commentId', (int) $child1['id'], \PDO::PARAM_INT);
                    $likedUsers2->execute();

                    $childObject1->setId($child1['id']);
                    $childObject1->setContent($child1['content']);
                    $childObject1->setUserId($child1['user_id']);
                    $childObject1->setUserLogin($child1['login']);
                    $childObject1->setUserAvatar($child1['avatar']);
                    $childObject1->setParent($child1['parent']);
                    $childObject1->setLikes($child1['likes']);
                    $childObject1->setDateCreated($child1['date_created']);

                    while ($user2 = $likedUsers2->fetch()) {
                        $childObject1->addLikedUser($user2['user_id']);
                    }

                    $childObject->addChild($childObject1);
                }

                $object->addChild($childObject);
            }
        }

        return $results;
    }

    public function find($id)
    {
        $statement = $this->connector->getPdo()->prepare('SELECT c.id, c.content, c.user_id, u.login, u.avatar, c.parent, c.likes, c.date_created
            FROM comments c INNER JOIN user u ON u.id = c.user_id WHERE c.id = :id LIMIT 1');
        $statement->bindValue(':id', (int) $id, \PDO::PARAM_INT);
        $statement->execute();
        $commentsData = $this->fetchCommentsData($statement);

        return $commentsData[0];
    }

    public function insert(array $commentsData)
    {
        $statement = $this->connector->getPdo()->prepare('INSERT INTO comments (content, user_id, likes, date_created) VALUES(:content, :userId, :likes, NOW())');
        $statement->bindValue(':content', $commentsData['content']);
        $statement->bindValue(':userId', (int) $_SESSION['user_id'], \PDO::PARAM_INT);
        $statement->bindValue(':likes', (int) $commentsData['likes'], \PDO::PARAM_INT);
        $statement->execute();


        $statement = $this->connector->getPdo()->prepare('SELECT c.id, c.content, c.user_id, u.login, u.avatar, c.parent, c.likes, c.date_created
            FROM comments c INNER JOIN user u ON u.id = c.user_id ORDER BY id DESC LIMIT 1');
        $statement->execute();
        $commentsData = $this->fetchCommentsData($statement);

        return $commentsData[0];
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
        $statement = $this->connector->getPdo()->prepare('INSERT INTO comments (content, user_id, parent, date_created, likes) VALUES(:content, :userId, :parent, NOW(), :likes)');
        $statement->bindValue(':content', $commentsData['content']);
        $statement->bindValue(':userId', (int) $_SESSION['user_id'], \PDO::PARAM_INT);
        $statement->bindValue(':parent', (int) $commentsData['parent_id'], \PDO::PARAM_INT);
        $statement->bindValue(':likes', (int) $commentsData['likes'], \PDO::PARAM_INT);

        return $statement->execute();
    }

    public function checkCounter(array $data)
    {
        $statement = $this->connector->getPdo()->prepare("SELECT * FROM likes WHERE comment_id = :commentId AND user_id = :userId");
        $statement->bindValue(':commentId', (int) $data['comment_id'], \PDO::PARAM_INT);
        $statement->bindValue(':userId', (int) $data['user_id'], \PDO::PARAM_INT);
        $statement->execute();

        if ($statement->fetch()) {
            $statement = $this->connector->getPdo()->prepare("DELETE FROM likes WHERE comment_id = :commentId AND user_id = :userId");
            $statement->bindValue(':commentId', (int) $data['comment_id'], \PDO::PARAM_INT);
            $statement->bindValue(':userId', (int) $data['user_id'], \PDO::PARAM_INT);
            $statement->execute();

            return false;
        } else {
            $statement = $this->connector->getPdo()->prepare("INSERT INTO likes (comment_id, user_id) VALUES (:commentId, :userId)");
            $statement->bindValue(':commentId', (int) $data['comment_id'], \PDO::PARAM_INT);
            $statement->bindValue(':userId', (int) $data['user_id'], \PDO::PARAM_INT);
            $statement->execute();

            return true;
        }
    }
}
