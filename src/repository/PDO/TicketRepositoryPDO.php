<?php
namespace App\repository\PDO;

use App\repository\interfaces\TicketRepositoryInterface;
use App\DTOs\Ticket\TicketCreateDTO;
use App\DTOs\Ticket\TicketGetDTO;
use App\DTOs\Ticket\TicketUpdateDTO;

class TicketRepositoryPDO implements TicketRepositoryInterface {
	private $pdo;

	public function __construct($pdo) {
		$this->pdo = $pdo;
	}

	public function create(TicketCreateDTO $data): void {
		$sql = "INSERT INTO tickets (id, amount, value, user_id, title, description, event_date, created_at) 
				VALUES (:id, :amount, :value, :user_id, :title, :description, :event_date, :created_at)";
		
		$stmt = $this->pdo->prepare($sql);
		$stmt->bindValue(':id', $data->id);
		$stmt->bindValue(':amount', $data->amount);
		$stmt->bindValue(':value', $data->value);
		$stmt->bindValue(':user_id', $data->userId);
		$stmt->bindValue(':title', $data->title);
		$stmt->bindValue(':description', $data->description);
		$stmt->bindValue(':event_date', $data->eventDate);
		$stmt->bindValue(':created_at', $data->createdAt);

		if (!$stmt->execute()) {
			throw new \Exception("Failed to create ticket");
		}
	}

	public function update(TicketUpdateDTO $data): void {
		$sql = "UPDATE tickets SET amount = :amount, value = :value, title = :title, 
				description = :description, event_date = :event_date WHERE id = :id";
		
		$stmt = $this->pdo->prepare($sql);
		$stmt->bindValue(':id', $data->id);
		$stmt->bindValue(':amount', $data->amount);
		$stmt->bindValue(':value', $data->value);
		$stmt->bindValue(':title', $data->title);
		$stmt->bindValue(':description', $data->description);
		$stmt->bindValue(':event_date', $data->eventDate);

		if (!$stmt->execute()) {
			throw new \Exception("Failed to update ticket");
		}
	}

	public function delete(string $id): void {
		$sql = "UPDATE tickets SET deleted_at = :deleted_at WHERE id = :id";
		$stmt = $this->pdo->prepare($sql);
		$deletedAt = time();
		$stmt->bindValue(':id', $id);
		$stmt->bindValue(':deleted_at', $deletedAt);
		
		if (!$stmt->execute()) {
			throw new \Exception("Failed to delete ticket");
		}
	}

	public function findAvailable(): array {
		$now = time();

		$sql = "
			SELECT 
				t.*,
				t.amount - COALESCE((
					SELECT COUNT(*) 
					FROM reserved r 
					WHERE r.ticket_id = t.id 
					AND (:now - r.created_at) < 120
				), 0) AS available_stock
			FROM tickets t
			WHERE t.deleted_at IS NULL
			AND t.event_date >= :now
			AND (
				t.amount - COALESCE((
					SELECT COUNT(*) 
					FROM reserved r 
					WHERE r.ticket_id = t.id 
					AND (:now - r.created_at) < 120
				), 0)
			) > 0
			ORDER BY t.event_date ASC
		";

		$stmt = $this->pdo->prepare($sql);
		$stmt->bindValue(':now', $now, \PDO::PARAM_INT);
		$stmt->execute();

		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}

	public function findByUserId(string $userId): array | null {
		$stmt = $this->pdo->prepare('SELECT * FROM tickets WHERE user_id = :userId');
		$stmt->bindValue(':userId', $userId);
		$stmt->execute();
		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}	

	public function findById(string $id): TicketGetDTO | null {
		$stmt = $this->pdo->prepare('SELECT * FROM tickets WHERE id = :id');
		$stmt->bindValue(':id', $id);
		$stmt->execute();
		$result = $stmt->fetch(\PDO::FETCH_ASSOC);

		if ($result) {
			return new TicketGetDTO(
				$result['id'],
				$result['amount'],
				$result['value'],
				$result['user_id'],
				$result['title'],
				$result['description'],
				$result['event_date'],
				$result['created_at']
			);
		}

		return null;
	}

	public function decreaseAmount(string $ticketId, int $amount): bool {
		$sql = "UPDATE tickets SET amount = amount - :amount WHERE id = :id AND amount >= :amount";
		$stmt = $this->pdo->prepare($sql);
		$stmt->bindValue(':id', $ticketId);
		$stmt->bindValue(':amount', $amount, \PDO::PARAM_INT);

		if (!$stmt->execute()) {
			return false;
		}

		return true;
	}
}
