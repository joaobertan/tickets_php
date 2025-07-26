<?php
namespace App\DTOs\Ticket;

class TicketGetDTO {
	public string $id;
	public int $amount;
	public float $value;
	public string $userId;
	public string $title;
	public string $description;
	public int $eventDate;
	public int $createdAt;

	public function __construct(string $id, int $amount, float $value, string $userId, string $title, string $description, int $eventDate, int $createdAt) {
		$this->id = $id;
		$this->amount = $amount;
		$this->value = $value;
		$this->userId = $userId;
		$this->title = $title;
		$this->description = $description;
		$this->eventDate = $eventDate;
		$this->createdAt = $createdAt;
	}
}