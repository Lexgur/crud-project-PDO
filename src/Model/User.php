<?php

declare(strict_types=1);

namespace Crud\Model;

class User
{
    private ?int $userId = null;
    private string $userEmail;
    private string $userName;
    private string $userPassword;

    public function __construct(string $userEmail, string $userName, string $userPassword, ?int $userId = null)
    {
        $this->userEmail = $userEmail;
        $this->userName = $userName;
        $this->userPassword = $userPassword;
        $this->userId = $userId;
    }

    public function getUserEmail(): string
    {
        return $this->userEmail;
    }

    public function getUserName(): string
    {
        return $this->userName;
    }

    public function getUserPassword(): string
    {
        return $this->userPassword;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }
}
