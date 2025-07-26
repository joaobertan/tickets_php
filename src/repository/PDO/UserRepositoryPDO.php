<?php
namespace App\repository\PDO;

use App\repository\interfaces\UserRepositoryInterface;
use App\DTOs\User\UserCreateDTO;
use App\DTOs\User\UserGetDTO;
use App\DTOs\User\UserUpdateDTO;

class UserRepositoryPDO implements UserRepositoryInterface {
	private $pdo;

	public function __construct($pdo) {
		$this->pdo = $pdo;
	}

	public function create(UserCreateDTO $data): void {
		$sql = "INSERT INTO users (id, name, email, hashed_password, role, created_at) VALUES (:id, :name, :email, :password, :role, :created_at)";
		$stmt = $this->pdo->prepare($sql);
		$stmt->bindParam(':id', $data->id);
		$stmt->bindParam(':name', $data->name);
		$stmt->bindParam(':email', $data->email);
		$stmt->bindParam(':password', $data->password);
		$stmt->bindParam(':role', $data->role);
		$stmt->bindParam(':created_at', $data->createdAt);
		$stmt->execute();
	}

	public function update(UserUpdateDTO $data): void {
		$sql = "UPDATE users SET name = :name, email = :email, hashed_password = :password WHERE id = :id";
		$stmt = $this->pdo->prepare($sql);
		$stmt->bindParam(':id', $data->id);
		$stmt->bindParam(':name', $data->name);
		$stmt->bindParam(':email', $data->email);
		$stmt->bindParam(':password', $data->password);
		$stmt->execute();
	}

	public function findByEmail(string $email): ?UserGetDTO {
		$sql = "SELECT * FROM users WHERE email = :email";
		$stmt = $this->pdo->prepare($sql);
		$stmt->bindParam(':email', $email);
		$stmt->execute();
		$result = $stmt->fetch(\PDO::FETCH_ASSOC);

		if ($result) {
			$dto = new UserGetDTO(
				$result['id'],
				$result['name'],
				$result['email'],
				$result['hashed_password'],
				$result['role'],
				$result['created_at']
			);
			return $dto;
		}

		return null;
	}

	public function findById(string $id): ?UserGetDTO {
		$sql = "SELECT * FROM users WHERE id = :id";
		$stmt = $this->pdo->prepare($sql);
		$stmt->bindParam(':id', $id);
		$stmt->execute();
		$result = $stmt->fetch(\PDO::FETCH_ASSOC);

		if ($result) {
			return new UserGetDTO(
				$result['id'],
				$result['name'],
				$result['email'],
				$result['hashed_password'],
				$result['role'],
				$result['created_at']
			);
		}

		return null;
	}
}