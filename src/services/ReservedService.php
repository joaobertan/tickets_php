<?php
namespace App\services;

use App\repository\PDO\ReservedRepositoryPDO;
use App\repository\PDO\PDOconn;

class ReservedService {
	private $reservedRepository;

	public function __construct() {
		$conn = PDOconn::conectar();
		$this->reservedRepository = new ReservedRepositoryPDO($conn);
	}

	public function create(string $ticketId, string $userId): bool {
		return $this->reservedRepository->create($ticketId, $userId);
	}

	public function findValidReservation(string $ticketId, string $userId): array|null {
		return $this->reservedRepository->findValidReservation($ticketId, $userId);
	}

	public function deleteByUserId(string $userId): bool {
		return $this->reservedRepository->deleteByUserId($userId);
	}

	public function countValidReservations(string $ticketId): int {
		return $this->reservedRepository->countValidReservations($ticketId);
	}
}