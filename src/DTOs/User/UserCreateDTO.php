<?php
namespace App\DTOs\User;

class UserCreateDTO {
	public string $id;
	public string $name;
	public string $email;
	public string $password;
	public string $role;
	public int $createdAt;

	public function __construct(string $name, string $email, string $password, string $role) {
		$this->name = $name;
		$this->email = $email;
		$this->password = $password;
		$this->role = $role;
	}

	public function setHashedPassword(string $hashedPassword): void {
		$this->password = $hashedPassword;
	}

	public function setId(string $id): void {
		$this->id = $id;
	}

	public function setCreatedAt(int $createdAt): void {
		$this->createdAt = $createdAt;
	}
}