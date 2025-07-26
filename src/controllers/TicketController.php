<?php
namespace App\controllers;

use App\DTOs\Ticket\TicketCreateDTO;
use App\DTOs\Ticket\TicketUpdateDTO;
use App\services\TicketService;
use App\services\SessionService;

class TicketController {
	private $ticketService;

	public function __construct() {
		$this->ticketService = new TicketService();
	}

	function create(array $postData) {
		$userId = SessionService::getId();

		if (!$userId) {
			throw new \Exception("User not logged in");
		}

		$dto = new TicketCreateDTO(
			(int)$postData['amount'],
			(float)$postData['value'],
			$userId,
			$postData['title'] ?? '',
			$postData['description'] ?? '',
			strtotime($postData['eventDate'])
		);

		$dto->setCreatedAt(time());
		$this->ticketService->create($dto);
	}

	function update(array $postData) {
		$userId = SessionService::getId();

		if (!$userId) {
			throw new \Exception("User not logged in");
		}

		$dto = new TicketUpdateDTO(
			$postData['id'],
			(int)$postData['amount'],
			(float)$postData['value'],
			$userId,
			$postData['title'] ?? '',
			$postData['description'] ?? '',
			strtotime($postData['eventDate'])
		);

		$this->ticketService->update($dto);
	}

	function delete (string $id): void {
		$userId = SessionService::getId();

		if (!$userId) {
			throw new \Exception("User not logged in");
		}

		$ticket = $this->ticketService->findById($id);

		if (!$ticket || $ticket->userId !== $userId) {
			throw new \Exception("Ticket not found or access denied");
		}

		$this->ticketService->delete($id);
	}
}