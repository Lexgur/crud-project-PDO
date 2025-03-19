<?php

declare(strict_types=1);

namespace Crud\Repository;

use Crud\Model\Student;

interface StudentModelRepositoryInterface
{
    public function save(Student $student): Student;

    public function insert(Student $student): Student;

    public function fetchById(int $studentId): ?Student;

    public function update(Student $student): Student;

    public function delete(int $studentId): bool;
}


