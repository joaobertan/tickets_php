<?php
namespace App\repository\PDO;

use App\repository\interfaces\ReservedRepositoryInterface;
use App\utils\UUID;

class ReservedRepositoryPDO implements ReservedRepositoryInterface {
	private $pdo;

	public function __construct($pdo) {
		$this->pdo = $pdo;
	}

	public function create(string $ticketId, string $userId): bool {
		$now = time();
		$this->pdo->beginTransaction();

		try {
			$stmt = $this->pdo->prepare("SELECT amount FROM tickets WHERE id = :ticket_id AND deleted_at IS NULL");
			$stmt->bindValue(':ticket_id', $ticketId);
			$stmt->execute();
			$ticket = $stmt->fetch(\PDO::FETCH_ASSOC);

			if (!$ticket) {
				$this->pdo->rollBack();
				return false; 
			}

			$amount = $ticket['amount'];

			$limitTimestamp = $now - 120;

			$stmt = $this->pdo->prepare("
				DELETE FROM reserved
				WHERE ticket_id = :ticket_id AND user_id = :user_id
			");
			$stmt->execute([
				':ticket_id' => $ticketId,
				':user_id' => $userId
			]);

			$stmt = $this->pdo->prepare("
				SELECT COUNT(*) AS reserved_count 
				FROM reserved 
				WHERE ticket_id = :ticket_id AND created_at > :limit
			");
			$stmt->bindValue(':ticket_id', $ticketId);
			$stmt->bindValue(':limit', $limitTimestamp);
			$stmt->execute();
			$reservedCount = (int) $stmt->fetchColumn();

			if ($amount - $reservedCount <= 0) {
				$this->pdo->rollBack();
				return false; 
			}

			$stmt = $this->pdo->prepare("
				INSERT INTO reserved (id, ticket_id, user_id, created_at)
				VALUES (:id, :ticket_id, :user_id, :created_at)
			");

			$stmt->execute([
				':id' => UUID::generate(),
				':ticket_id' => $ticketId,
				':user_id' => $userId,
				':created_at' => $now
			]);

			$this->pdo->commit();
			return true;
		} catch (\Throwable $e) {
			$this->pdo->rollBack();
			throw $e; 
		}
	} 

	public function findValidReservation(string $ticketId, string $userId): array|null {
		$stmt = $this->pdo->prepare("
			SELECT * FROM reserved 
			WHERE ticket_id = :ticketId AND user_id = :userId 
			ORDER BY created_at DESC
			LIMIT 1
		");
		$stmt->bindValue(':ticketId', $ticketId);
		$stmt->bindValue(':userId', $userId);
		$stmt->execute();

		$reservation = $stmt->fetch(\PDO::FETCH_ASSOC);
		
		if ($reservation && (time() - (int) $reservation['created_at']) < 120) {
			return $reservation;
		}

		return null;
	}

	public function deleteByUserId(string $userId): bool {
		$stmt = $this->pdo->prepare("DELETE FROM reserved WHERE user_id = :user_id");
		$stmt->bindValue(':user_id', $userId);
		return $stmt->execute();
	}

	public function countValidReservations(string $ticketId): int {
		$now = time();
		$stmt = $this->pdo->prepare("
			SELECT COUNT(*) 
			FROM reserved 
			WHERE ticket_id = :ticketId AND created_at > :limit
		");
		$limitTimestamp = $now - 120;
		$stmt->bindValue(':ticketId', $ticketId);
		$stmt->bindValue(':limit', $limitTimestamp);
		$stmt->execute();
		
		return (int) $stmt->fetchColumn();
	}
}