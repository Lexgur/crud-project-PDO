<?php

declare(strict_types=1);

namespace Crud\Validation;

use Crud\Exception\IncorrectEmailException;
use Crud\Exception\IncorrectPasswordException;
use Crud\Model\User;

class UserValidator
{
    /**
     * @throws IncorrectPasswordException
     * @throws IncorrectEmailException
     */
    public function validate(User $user): bool
    {
        $this->validateEmail($user->getUserEmail());
        $this->validatePassword($user->getUserPassword());

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

    public function validatePassword(string $userPassword): void
    {
        if (strlen($userPassword) < 8) {
            throw new IncorrectPasswordException('Password must be at least 8 characters long');
        }
        if (!preg_match("/[0-9]/", $userPassword)) {
            throw new IncorrectPasswordException('Password must include at least one number');
        }
        if (!preg_match("/\p{Lu}/u", $userPassword)) {
            throw new IncorrectPasswordException('Password must include at least one uppercase letter');
        }
        if (!preg_match("/\p{Ll}/u", $userPassword)) {
            throw new IncorrectPasswordException('Password must include at least one lowercase letter');
        }
    }
}
