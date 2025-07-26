<?php
namespace App\repository\PDO;

use App\repository\PDO\PDOconn;
use App\repository\interfaces\OrderRepositoryInterface;
use App\utils\UUID;

class OrderRepositoryPDO implements OrderRepositoryInterface {
    private $pdo;

    public function __construct() {
        $this->pdo = PDOconn::conectar();
    }

    public function create(string $userId): string|null {
        $stmt = $this->pdo->prepare("INSERT INTO orders (id, user_id, created_at) VALUES (:id, :userId, :createdAt) RETURNING id");
		$stmt->bindValue(':id', UUID::generate());
        $stmt->bindValue(':userId', $userId);
        $stmt->bindValue(':createdAt', time()); 

        if ($stmt->execute()) {
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            return $result['id'] ?? null;
        }

        return null;
    }

    public function findByUserId(string $userId): array {
        $stmt = $this->pdo->prepare("
            SELECT 
                t.title,
                t.event_date,
                t.value,
                oi.amount,
                o.created_at AS purchase_date
            FROM orders o
            JOIN order_itens oi ON oi.order_id = o.id
            JOIN tickets t ON t.id = oi.ticket_id
            WHERE o.user_id = :userId
            ORDER BY o.created_at DESC
        ");
        $stmt->bindValue(':userId', $userId);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
