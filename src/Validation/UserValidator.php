<?php

declare(strict_types=1);

namespace Crud\Validation;

use Crud\Exception\IncorrectEmailException;
use Crud\Exception\IncorrectPasswordException;
use Crud\Model\User;
use Crud\Repository\UserModelRepository;

class UserValidator
{
    private UserModelRepository $repository;

    public function __construct(UserModelRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @throws IncorrectEmailException
     */
    public function validate(User $user): bool
    {
        $this->validateEmail($user->getUserEmail());
        $this->validateIfEmailDoesNotExist($user->getUserEmail(), $user->getUserId());

        return true;
    }

    public function validateEmail(string $userEmail): void
    {
        if (empty($userEmail)) {
            throw new IncorrectEmailException('User email cannot be empty');
        }
        if (!filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {
            throw new IncorrectEmailException('Invalid email format');
        }
    }

    public function validateIfEmailDoesNotExist(string $userEmail, ?int $userId = null): void
    {
        $existingUser = $this->repository->findByEmail($userEmail);

        if ($existingUser !== null && $existingUser->getUserId() !== $userId) {
            throw new IncorrectEmailException('Email is already in use');
        }
    }

    public function passwordExists(string $userPassword, string $userEmail): bool
    {
        $existingUser = $this->repository->findByEmail($userEmail);

        if ($existingUser === null) {
            throw new IncorrectEmailException('No user found with this email');
        }

        if ($existingUser->getUserPassword() !== $userPassword) {
            throw new IncorrectPasswordException('Password does not match');
        }

        return true;
    }

}
