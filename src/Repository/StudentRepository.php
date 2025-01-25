<?php

declare(strict_types=1);

namespace Crud\Repository;
//TODO PDOStatement keliauja cia
use Crud\Exception\StudentAlreadyExistsException;
use PDO;
use PDOException;

class StudentRepository
{
    public function __construct(
        protected PDO $connection
    )

    {

    }

    public function save(array $data): bool

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


        $statement = $this->connection->prepare('INSERT INTO `students` (`id_student`, `student_first_name`, `student_last_name`, `student_age`) VALUES (:id_student, :student_first_name, :student_last_name, :student_age)');
        $statement->execute()

        return $result;

    }

}