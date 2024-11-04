<?php

class Database {
    private $conn = null;

    /**
     * constructor for new database connection.
     * @return String connection status
     */
    public function __construct() {
        if (file_exists(__DIR__ . '/../../.env')) {
            require __DIR__ . '../../../vendor/autoload.php';
            Dotenv\Dotenv::createUnsafeImmutable(__DIR__ . '../../../')->load();
        }
        $this->conn = new mysqli(getenv('serverDB'),getenv('userDB'), getenv('passwordDB'), getenv('databaseName'));

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }

        return 'Connected';
    }

    /**
     * get the connection to database.
     * @return mixed Connection
     */
    public function getConnection(){
        return $this->conn;
    }


    /**
     * close database connection.
     * @return String
     */
    public function close(){
        $this->conn->close();
        return 'Closed';
    }    
}