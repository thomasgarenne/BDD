<?php

namespace App\Entity;

use PDO;

class Connexion extends PDO
{
    private string $dbname;
    private string $dbhost;
    private string $username;
    private string $password;
    private ?PDO $pdo = null;
    private static $instance = null;

    public function __construct()
    {
        $config = require_once('../config.php');

        if (isset($config)) {
            $this->dbname = $config['dbname'];
            $this->dbhost = $config['dbhost'];
            $this->username = $config['username'];
            $this->password = $config['password'];
        }
    }

    public static function getInstance(): self
    {
        if (is_null(self::$instance)) {
            self::$instance = new Connexion;
        }
        return self::$instance;
    }

    public function getPDO()
    {
        if (is_null($this->pdo)) {
            try {
                $dsn = 'mysql:dbname=' . $this->dbname . ';host=' . $this->dbhost;
                $this->pdo = new PDO($dsn, $this->username, $this->password, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                ]);
            } catch (\Throwable $th) {
                echo $th->getMessage();
            }
        }

        return $this->pdo;
    }
}
