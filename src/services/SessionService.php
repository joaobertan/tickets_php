<?php

namespace App\services;

class SessionService
{
    public static function init(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function login($user): void
    {
        self::init();
        $_SESSION['id_user'] = $user->id;
        $_SESSION['email'] = $user->email;
        $_SESSION['role'] = $user->role;
    }

	public static function loggedUser(): bool
    {
        self::init();
        return isset($_SESSION['id_user']) && isset($_SESSION['email']);
    }

    public static function logout(): void
    {
        self::init();
        session_destroy();
    }

    public static function getEmail(): ?string
    {
        return $_SESSION['email'] ?? null;
    }

    public static function getId(): ?string
    {
        return $_SESSION['id_user'] ?? null;
    }

    public static function getRole(): ?string
    {
        return $_SESSION['role'] ?? null;
    }
}
