<?php

declare(strict_types=1);

namespace Crud\Service;

class PasswordVerifier
{
    public static function verify(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }
}
