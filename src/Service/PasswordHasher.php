<?php

declare(strict_types=1);

namespace Crud\Service;

use Crud\Exception\IncorrectPasswordException;

class PasswordHasher
{
    public static function hash(string $password): string
    {
        if (!empty($password)) {
            return password_hash($password, PASSWORD_DEFAULT);
        } else {
            throw new IncorrectPasswordException('Password is empty');
        }
    }
}