<?php
require_once('conf.php');

class monPDO
{
    private static $instance = null;
    private $pdo;

    private function __construct()
    {
        $dsn = 'mysql:host='.DB_HOST.';dbname='.DB_NAME.'';
        $user = DB_USER;
        $password = DB_PASSWORD;

        try {
            $this->pdo = new PDO($dsn, $user, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->exec("SET CHARACTER SET utf8");
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new monPDO();
        }

        return self::$instance->pdo;
    }
}
