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
        $statement = $this->connector->getPdo()->prepare('INSERT INTO user (login, password, avatar) VALUES(:login, :password, :avatar)');
        $statement->bindValue(':login', $userData['login']);
        $statement->bindValue(':password', $userData['password']);
        $statement->bindValue(':avatar', $userData['image']);

        return $statement->execute();
    }

    public function find($login)
    {
        $statement = $this->connector->getPdo()->prepare('SELECT * FROM user WHERE login = :login LIMIT 1');
        $statement->bindValue(':login', (string) $login, \PDO::PARAM_STR);
        $statement->execute();
        $userData = $this->fetchUserData($statement);

        if (!isset($userData[0])) {
            return false;
        }

        return $userData[0];
    }

    public function authentificate(array $userData)
    {
        $statement = $this->connector->getPdo()->prepare('SELECT * FROM user WHERE login = :login AND password = :password LIMIT 1');
        $statement->bindValue(':login', (string) $userData['login'], \PDO::PARAM_STR);
        $statement->bindValue(':password', (string) $userData['password'], \PDO::PARAM_STR);
        $statement->execute();
        $userData = $this->fetchUserData($statement);

        if (!isset($userData[0])) {
            return false;
        }

        return $userData[0];
    }

    public function checkRegisterData($login, $password, $confirmPassword)
    {
        if (empty($login)) {
            return 'Please enter your login';
        } elseif (empty($password)) {
            return 'Please enter your password';
        } elseif (empty($confirmPassword) || ($password !== $confirmPassword)) {
            return 'Mismatch passwords. Please check and try again';
        } else {
            if (!preg_match("/^[a-zа-яё\d]{1,}$/i", $login)) {
                return 'You login may consist only alphabetic and numeric characters without spaces ';
            } elseif (!preg_match("/^[a-zа-яё\d]{1,}$/i", $password)) {
                return 'You password may consist only alphabetic and numeric characters without spaces ';
            } else {
                return true;
            }
        }
    }

    public function checkAuthData($login, $password)
    {
        if (empty($login)) {
            return 'Please enter your login';
        } elseif (empty($password)) {
            return 'Please enter your password';
        } else {
            return true;
        }
    }
}
