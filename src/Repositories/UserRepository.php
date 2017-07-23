<?php
namespace Repositories;



class UserRepository
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


    public function fetchUserData($statement)
    {

        $results = [];

        while ($result = $statement->fetch()) {

            $object = new \Entities\UserEntity;
            $results[] =
                $object->setId($result['id']);
                $object->setLogin($result['login']);
        }

        return $results;
    }


    public function insert(array $userData)
    {
        $statement = $this->connector->getPdo()->prepare('INSERT INTO user (login) VALUES(:login)');
        $statement->bindValue(':login', $userData['login']);

        return $statement->execute();
    }

    public function find($login)
    {

        $statement = $this->connector->getPdo()->prepare('SELECT * FROM user WHERE login = :login LIMIT 1');
        $statement->bindValue(':login', (string) $login, \PDO::PARAM_STR);
        $statement->execute();
        $UserData = $this->fetchUserData($statement);

        return $UserData[0];

    }
}