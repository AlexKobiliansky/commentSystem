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
        $statement = $this->connector->getPdo()->prepare('INSERT INTO user (login, password) VALUES(:login, :password)');
        $statement->bindValue(':login', $userData['login']);
        $statement->bindValue(':password', $userData['password']);

        return $statement->execute();
    }


    public function find($login)
    {
        $statement = $this->connector->getPdo()->prepare('SELECT * FROM user WHERE login = :login LIMIT 1');
        $statement->bindValue(':login', (string) $login, \PDO::PARAM_STR);
        $statement->execute();
        $userData = $this->fetchUserData($statement);

        if (!isset($userData[0]))
            return false;

        return $userData[0];
    }


    public function authentificate(array $userData)
    {
        $statement = $this->connector->getPdo()->prepare('SELECT * FROM user WHERE login = :login AND password = :password LIMIT 1');
        $statement->bindValue(':login', (string) $userData['login'], \PDO::PARAM_STR);
        $statement->bindValue(':password', (string) $userData['password'], \PDO::PARAM_STR);
        $statement->execute();
        $userData = $this->fetchUserData($statement);

        if (!isset($userData[0]))
            return false;

        return $userData[0];
    }
}