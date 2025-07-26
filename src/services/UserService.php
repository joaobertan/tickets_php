<?php
namespace App\services;

use App\DTOs\User\UserCreateDTO;
use App\DTOs\User\UserUpdateDTO;
use App\DTOs\User\UserGetDTO;
use App\utils\UUID;
use App\repository\PDO\UserRepositoryPDO;
use App\repository\PDO\PDOconn;

class UserService {
	private $userRepository;
	
	public function __construct() {
		$conn = PDOconn::conectar();
		$this->userRepository = new UserRepositoryPDO($conn); 
	}

	public function create(UserCreateDTO $data): void {
		$existingUser = $this->userRepository->findByEmail($data->email);
		
		if ($existingUser) {
			throw new \Exception("User with email {$data->email} already exists.");
		}
		
		$hashedPassword = password_hash($data->password, PASSWORD_DEFAULT);
		$data->setHashedPassword($hashedPassword);

		$userId = UUID::generate();
		$data->setId($userId);

		$userCreatedAt = time();
		$data->setCreatedAt($userCreatedAt);
		
		$this->userRepository->create($data);
	}

	public function update(UserUpdateDTO $data): void {
		$existingUser = $this->userRepository->findById($data->id);
		
		if (!$existingUser) {
			throw new \Exception("User with ID {$data->id} does not exist.");
		}

		$hashedPassword = password_hash($data->password, PASSWORD_DEFAULT);
		$data->setHashedPassword($hashedPassword);

		$this->userRepository->update($data);
	}

	public function findById(string $id): ?UserGetDTO
	{
		return $this->userRepository->findById($id);
	}
}