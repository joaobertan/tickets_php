<?php
namespace App\repository\PDO;
use PDO;
use PDOException;

class PDOconn {
    public static function conectar() {
        try {
            $conn = new PDO('pgsql:host=127.0.0.1;port=5432;dbname=dev_evolution', 'devuser', 'devpass');
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
            return null;
        }
    }
}
?>