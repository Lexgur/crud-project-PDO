<?php

namespace Crud\Repository;

use PDO;
//TODO gali succedinti arba gali failinti issaugoti arba neissaugoti students, ne unikalus id
class StudentRepository
{
    public function __construct(
        protected PDO $connection
    )

    {

    }

    public function save(array $data): bool
    {

        $statement = $this->connection->prepare('INSERT INTO `students` (`id_student`, `student_first_name`, `student_last_name`, `student_age`) VALUES (:id_student, :student_first_name, :student_last_name, :student_age)');
        $statement->bindValue(':id_student', $data['id_student']);
        $statement->bindValue(':student_first_name', $data['first_name']);
        $statement->bindValue(':student_last_name', $data['last_name']);
        $statement->bindValue(':student_age', $data['age']);

        $result = $statement->execute();

        return $result;

    }

}