<?php
namespace App\repository\interfaces;

use App\DTOs\Ticket\TicketCreateDTO;
use App\DTOs\Ticket\TicketGetDTO;
use App\DTOs\Ticket\TicketUpdateDTO;

interface TicketRepositoryInterface {
	function create(TicketCreateDTO $data): void;
	function update(TicketUpdateDTO $data): void;
	function delete(string $id): void;
	function findAvailable(): array;
	function findByUserId(string $userId): array | null;
	function findById(string $id): TicketGetDTO | null;
	function decreaseAmount(string $ticketId, int $amount): bool;
}