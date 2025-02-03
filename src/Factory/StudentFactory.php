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

        $age = $data['age'] !==null ? (int)$data['age'] : null;

            return new Student(
                firstName: $data['name'],
                lastName: $data['lastname'],
                age: $age,
                id: $data['id'] ?? null);
        }
}

