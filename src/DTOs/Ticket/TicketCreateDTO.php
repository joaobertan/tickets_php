<?php
namespace App\DTOs\Ticket;

class TicketCreateDTO {
	public string $id;
	public int $amount;
	public float $value;
	public string $userId;
	public string $title;
	public string $description;
	public int $eventDate;
	public int $createdAt;

	public function __construct(int $amount, float $value, string $userId, string $title, string $description, int $eventDate) {
		$this->amount = $amount;
		$this->value = $value;
		$this->userId = $userId;
		$this->title = $title;
		$this->description = $description;
		$this->eventDate = $eventDate;
	}

	public function setId(string $id): void {
		$this->id = $id;
	}

	public function setCreatedAt(int $createdAt): void {
		$this->createdAt = $createdAt;
	}
}