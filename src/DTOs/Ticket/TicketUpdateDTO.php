<?php
namespace App\DTOs\Ticket;

class TicketUpdateDTO {
	public string $id;
	public int $amount;
	public float $value;
	public string $title;
	public string $description;
	public int $eventDate;

	public function __construct(string $id, int $amount, float $value, string $userId, string $title, string $description, int $eventDate) {
		$this->id = $id;
		$this->amount = $amount;
		$this->value = $value;
		$this->title = $title;
		$this->description = $description;
		$this->eventDate = $eventDate;
	}

}