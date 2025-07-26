<?php
namespace App\DTOs\User;

class UserGetDTO {
	public string $id;
	public string $name;
	public string $email;
	public string $hashedPassword;
	public string $role;
	public int $createdAt;

	public function __construct(string $id, string $name, string $email, string $hashedPassword, string $role, int $createdAt) {
		$this->id = $id;
		$this->name = $name;
		$this->email = $email;
		$this->hashedPassword = $hashedPassword;
		$this->role = $role;
		$this->createdAt = $createdAt;
	}
}