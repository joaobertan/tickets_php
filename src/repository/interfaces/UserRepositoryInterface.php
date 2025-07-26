<?php
namespace App\repository\interfaces;

use App\DTOs\User\UserCreateDTO;
use App\DTOs\User\UserGetDTO;

interface UserRepositoryInterface {
	function create(UserCreateDTO $data): void;
	function findByEmail(string $email): UserGetDTO|null;
	function findById(string $id): UserGetDTO|null;
}