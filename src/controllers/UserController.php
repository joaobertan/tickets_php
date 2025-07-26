<?php
namespace App\controllers;

use App\services\UserService;
use	App\DTOs\User\UserCreateDTO;
use App\DTOs\User\UserUpdateDTO;
use App\services\SessionService;

class UserController {
	private $userService;

	function __construct() {
		$this->userService = new UserService();
	}

	function create(array $postData) {
		$dto = new UserCreateDTO(
			$postData['name'] ?? '',
			$postData['email'] ?? '',
			$postData['password'] ?? '',
			$postData['role'] ?? '',
		);

		$this->userService->create($dto);
	}

	function update(array $postData) {
		$id = SessionService::getId();

		if (!$id) {
			throw new \Exception("User not logged in");
		}

		$dto = new UserUpdateDTO(
			$id,
			$postData['name'] ?? '',
			$postData['email'] ?? '',
			$postData['password'] ?? '',
		);

		$this->userService->update($dto);
	}
}