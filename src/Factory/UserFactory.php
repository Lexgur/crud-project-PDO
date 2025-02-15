<?php

declare(strict_types=1);

namespace Crud\Factory;

use Crud\Model\User;

class UserFactory
{
    public function __construct()
    {

    }

    public static function create(array $data): User
    {
        return new User(
            userEmail: $data['email'] ?? '',
            userPassword: $data['password'] ?? '',
            userId: $data['userid'] ?? null
        );
    }
}
