<?php
namespace App\repository\interfaces;

interface OrderRepositoryInterface {
	function create(string $userId): string|null;
	function findByUserId(string $userId): array;
}