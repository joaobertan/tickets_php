<?php
namespace App\controllers;

use App\services\AuthService;
use App\DTOs\User\UserLoginDTO;

class AuthController {
	private $authService;

	public function __construct() {
		$this->authService = new AuthService();
	}

	public function login(array $postData): void
    {
        $dto = new UserLoginDTO(
            $postData['email'] ?? '',
            $postData['password'] ?? ''
        );

        $this->authService->auth($dto);
    }
}