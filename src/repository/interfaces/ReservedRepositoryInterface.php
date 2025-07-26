<?php
namespace App\repository\interfaces;

interface ReservedRepositoryInterface {
	function create(string $ticketId, string $userId): bool;
	function findValidReservation(string $ticketId, string $userId): array|null;
	function deleteByUserId(string $userId): bool;
	function countValidReservations(string $ticketId): int;
}