<?php

class Database {
    private $conn = null;

    public function __construct() {
        $this->loadEnv(__DIR__ . '/../../.env');
        $this->conn = new mysqli($_ENV['serverDB'], $_ENV['userDB'], $_ENV['passwordDB'], $_ENV['databaseName']);

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }

        return 'Connected';
    }

    public function getConnection(){
        return $this->conn;
    }


    public function close(){
        $this->conn->close();
        return 'Closed';
    }

    function loadEnv($path) {
        if (!file_exists($path)) {
            throw new Exception("El archivo .env no existe en la ruta especificada.");
        }
    
        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos(trim($line), '#') === 0) continue; 
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
    
            $value = trim($value, '"\'');
    
            $_ENV[$key] = $value;
            putenv("$key=$value");
        }
    }
    
    
}