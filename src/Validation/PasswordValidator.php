<?php

declare(strict_types=1);

namespace Crud\Validation;

use Crud\Exception\IncorrectPasswordException;

class PasswordValidator
{
    public static function validate(string $userPassword): bool
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
        } else {
            return true;
        }
    }
}