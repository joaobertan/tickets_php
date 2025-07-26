<?php
namespace App\repository\PDO;

use App\repository\PDO\PDOconn;
use App\repository\interfaces\OrderItemRepositoryInterface;
use App\utils\UUID;

class OrderItemRepositoryPDO implements OrderItemRepositoryInterface {
    private $pdo;

    public function __construct() {
        $this->pdo = PDOconn::conectar();
    }

    public function create(string $orderId, string $ticketId, int $amount): bool {
        $stmt = $this->pdo->prepare("
            INSERT INTO order_itens (id, order_id, ticket_id, amount) 
            VALUES (:id, :orderId, :ticketId, :amount)
        ");
		$stmt->bindValue(':id', UUID::generate());
        $stmt->bindValue(':orderId', $orderId);
        $stmt->bindValue(':ticketId', $ticketId);
        $stmt->bindValue(':amount', $amount);
        return $stmt->execute();
    }
}
