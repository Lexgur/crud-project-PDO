<?php

declare(strict_types=1);

namespace Crud\Factory;

use Crud\Model\Student;

class StudentFactory
{
    public function __construct()
    {
    }

    public static function create(array $data): Student
    {
        if ($data['age'] !== null) {
            $age = (int)$data['age'];
        }
        return new Student(
            firstName: $data['firstname'] ?? '',
            lastName: $data['lastname'] ?? '',
            age: $age,
            id: $data['id'] ?? null
        );
    }
}
