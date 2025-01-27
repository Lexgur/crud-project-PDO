<?php

namespace Crud\Repository;

use Crud\Exception\StudentAlreadyExistsException;

class StudentRepositoryDuplicateCheck
{
    public function checkForDuplicates(): bool
    {
        $checkQuery = 'SELECT COUNT(*) FROM `students` WHERE `student_first_name` = :student_first_name AND `student_last_name` = :student_last_name';
        $checkStatement = $this->connection->prepare($checkQuery);
        $checkStatement->bindValue(':student_first_name', $data['first_name']);
        $checkStatement->bindValue(':student_last_name', $data['last_name']);
        $checkStatement->execute();
        $exists = $checkStatement->fetchColumn() > 0;

        if ($exists) {
            throw new StudentAlreadyExistsException("Student {$data['first_name']} {$data['last_name']} already exists in the database.");
        }
        return true;
    }
}
