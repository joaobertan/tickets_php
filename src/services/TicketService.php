<?php
namespace App\services;

use App\DTOs\Ticket\TicketCreateDTO;
use App\DTOs\Ticket\TicketGetDTO;
use App\DTOs\Ticket\TicketUpdateDTO;
use App\repository\PDO\TicketRepositoryPDO;
use App\repository\PDO\PDOconn;
use App\utils\UUID;

class TicketService {
	private $ticketRepository;

	public function __construct() {
		$conn = PDOconn::conectar();
		$this->ticketRepository = new TicketRepositoryPDO($conn); 
	}

	public function create(TicketCreateDTO $data): void {
		$ticketId = UUID::generate();
		$data->setId($ticketId);

		$createdAt = time();
		$data->setCreatedAt($createdAt);

		$this->ticketRepository->create($data);
	}

	public function update(TicketUpdateDTO $data): void {
		if (empty($data->id)) {
			throw new \Exception("Ticket ID is required for update");
		}

		$this->ticketRepository->update($data);
	}

	public function delete(string $id): void {
		if (empty($id)) {
			throw new \Exception("Ticket ID is required for deletion");
		}

		$this->ticketRepository->delete($id);
	}

	public function findAvailable(): array {
		return $this->ticketRepository->findAvailable();
	}

	public function findByUserId(string $userId): array | null {
		return $this->ticketRepository->findByUserId($userId);
	}

	public function findById(string $id): TicketGetDTO | null {
		return $this->ticketRepository->findById($id);
	}

	public function decreaseAmount(string $ticketId, int $amount): bool {
		return $this->ticketRepository->decreaseAmount($ticketId, $amount);
	}

	public function getTicketAmount(string $ticketId): int {
		$ticket = $this->ticketRepository->findById($ticketId);
		if ($ticket) {
			return $ticket->amount;
		}
		return 0;
	}
}