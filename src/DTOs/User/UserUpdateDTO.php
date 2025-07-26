<?php
namespace App\DTOs\User;

class UserUpdateDTO {
	public string $id;
	public string $name;
	public string $email;
	public string $password;

	public function __construct(string $id, string $name, string $email, string $password) {
		$this->id = $id;
		$this->name = $name;
		$this->email = $email;
		$this->password = $password;
	}

	public function setHashedPassword(string $hashedPassword): void {
		$this->password = $hashedPassword;
	}
}