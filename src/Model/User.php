<?php

declare(strict_types=1);

namespace Crud\Model;

class User
{
    private ?int $userId = null;
    private $userEmail;
    private $userPassword;

    public function __construct(string $userEmail, string $userPassword, ?int $userId = null)
    {
        $this->userEmail = $userEmail;
        $this->userPassword = $userPassword;
        $this->userId = $userId;
    }

    public function getUserEmail(): string
    {
        return $this->userEmail;
    }

    public function setUserEmail(string $userEmail): void
    {
        $this->userEmail = $userEmail;
    }

    public function getUserPassword(): string
    {
        return $this->userPassword;
    }

    public function setUserPassword(string $userPassword): void
    {
        $this->userPassword = $userPassword;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }
}
