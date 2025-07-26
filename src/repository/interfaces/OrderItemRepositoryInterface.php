<?php
namespace App\repository\interfaces;

interface OrderItemRepositoryInterface {
	function create(string $orderId, string $ticketId, int $amount): bool;
}