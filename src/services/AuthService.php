<?php
namespace App\services;

use App\DTOs\User\UserLoginDTO;
use App\repository\PDO\UserRepositoryPDO;
use App\repository\PDO\PDOconn;

class AuthService {
	private $userRepository;

	public function __construct() {
		$conn = PDOconn::conectar();
		$this->userRepository = new UserRepositoryPDO($conn);
	}

	public function auth(UserLoginDTO $data): void {
		$existingUser = $this->userRepository->findByEmail($data->email);
		
		if (!$existingUser) {
			throw new \Exception("User with email {$data->email} does not exist.");
		}

		if (!password_verify($data->password, $existingUser->hashedPassword)) {
			throw new \Exception("Invalid password.");
		}

		SessionService::login($existingUser);
	}
}