<?php

declare(strict_types=1);

namespace Crud\Repository;

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

    public function insert(Student $student) : Student
    {
        $statement = $this->connection->prepare('INSERT INTO `students` (`firstname`, `lastname`, `age`) VALUES (:firstname, :lastname, :age)');
        $statement->bindValue(':firstname', $student->getFirstName());
        $statement->bindValue(':lastname', $student->getLastName());
        $statement->bindValue(':age', $student->getAge());

        $statement->execute();

        $newId = (int) $this->connection->lastInsertId();

        return $this->fetchById($newId);

    }

    public function fetchById (int $id) : Student
    {
        $statement = $this->connection->prepare('SELECT * FROM students WHERE id = :id');
        $statement->execute([':id' => $id]);
        $row = $statement->fetch(PDO::FETCH_ASSOC);
        return new Student(
            firstName:  $row['firstname'],
            lastName:  $row['lastname'],
            age: (int) $row['age'],
            id: (int) $row['id']
        );
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