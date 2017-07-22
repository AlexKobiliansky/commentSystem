<?php

namespace Repositories;

    class Connector
    {
        private $pdo;

        /**
         * Initialize the database connection with sql server via given credentials
         * @param $databasename
         * @param $user
         * @param $pass
         */
        public function __construct($databasename, $user, $pass)
        {
            $this->pdo = new \PDO('mysql:host=localhost;dbname=' . $databasename . ';charset=UTF8', $user, $pass);
            if (!$this->pdo) {
                return false;
                throw new Exception('Error connecting to the database');
            }

        }

        public function getPdo()
        {
            return $this->pdo;
        }
    }

