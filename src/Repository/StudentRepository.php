<?php

declare(strict_types=1);

namespace Crud\Repository;

use Crud\Connection;
use Crud\Model\Student;
use PDO;

class StudentRepository
{
    public function __construct(
        protected PDO $connection
    )

    {

    }

    public function save(Student $student): Student

    {



        return $student;
    }

    public function insertNewStudent(Student $student) : Student
    {
        $statement = $this->connection->prepare('INSERT INTO `students` (`id_student`, `student_first_name`, `student_last_name`, `student_age`) VALUES (:id_student, :student_first_name, :student_last_name, :student_age)');
        $statement->bindValue(':student_first_name', $student->getFirstName(), PDO::PARAM_STR_CHAR);
        $statement->bindValue(':student_last_name', $student->getLastName(), PDO::PARAM_STR_CHAR);
        $statement->bindValue(':student_age', $student->getAge(), PDO::PARAM_INT);

        $statement->execute();

        $this->connection->lastInsertId('students');

        return $student;


    }

    public function fetchById (int $id)
    {
        $statement = $this->connection->prepare('SELECT * FROM `students` WHERE `id_student`= ?');
        $statement->execute([$id]);
        return $statement->fetchObject(Student::class);
    }

//    public function updateStudent(Student $student) : Student
//    {
//        $statement = $this->connection->prepare('UPDATE `students` SET `student_first_name` = :student_first_name, `student_last_name` = :student_last_name, `student_age` = :student_age WHERE `id_student` = :id_student');
//        $statement->bindValue(':id_student', $student->getId());
//        $statement->bindValue(':student_first_name', $student->getFirstName());
//        $statement->bindValue(':student_last_name', $student->getLastName());
//        $statement->bindValue(':student_age', $student->getAge());
//
//        $statement->execute();
//
//        return $student;
//    }

}