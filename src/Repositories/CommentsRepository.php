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
        $statement = $this->connector->getPdo()->prepare('SELECT * FROM comments LIMIT :limit OFFSET :offset');
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
            $results[] =
                $object->setId($result['id']);
                $object->setContent($result['content']);


        }

        return $results;
    }

    public function find($id)
    {
        $statement = $this->connector->getPdo()->prepare('SELECT * FROM comments WHERE id = :id LIMIT 1');
        $statement->bindValue(':id', (int) $id, \PDO::PARAM_INT);
        $statement->execute();
        $commentsData = $this->fetchCommentsData($statement);

        return $commentsData[0];

    }

    public function insert(array $commentsData)
    {
        $statement = $this->connector->getPdo()->prepare('INSERT INTO comments (content) VALUES(:content)');
        $statement->bindValue(':content', $commentsData['content']);

        return $statement->execute();
    }

    public function update(array $commentsData)
    {
        $statement = $this->connector->getPdo()->prepare("UPDATE comments SET content = :content WHERE id = :id");

        $statement->bindValue(':content', $commentsData['content'], \PDO::PARAM_STR);
        $statement->bindValue(':id', $commentsData['id'], \PDO::PARAM_INT);

        return $statement->execute();
    }

    public function remove(array $commentsData)
    {
        $statement = $this->connector->getPdo()->prepare("DELETE FROM comments WHERE id = :id");
        $statement->bindValue(':id', $commentsData['id'], \PDO::PARAM_INT);

        return $statement->execute();
    }

}