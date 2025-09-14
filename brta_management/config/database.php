<?php
class Database {
    private $host = 'localhost';
    private $db_name = 'brta_management';
    private $username = 'root';
    private $password = '';
    private $connection;
    
    public function connect() {
        $this->connection = null;
        
        try {
            $this->connection = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name,
                $this->username,
                $this->password
            );
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo "Connection Error: " . $e->getMessage();
        }
        
        return $this->connection;
    }
}

// Create database tables if they don't exist
function initializeDatabase() {
    $db = new Database();
    $connection = $db->connect();
    
    if ($connection) {
        $sql = file_get_contents('../database/schema.sql');
        $connection->exec($sql);
    }
}
?>
