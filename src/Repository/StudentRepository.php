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
        if ($student->getId() === null) {
            $newStudent = $this->insert($student);

            return $newStudent;
        } else {
            $updatedStudent = $this->update($student);

            return $updatedStudent;
        }
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

    public function update(Student $student) : Student
    {
        $statement = $this->connection->prepare('UPDATE `students` SET `firstname` = :firstname, `lastname` = :lastname, `age` = :age WHERE `id` = :id');
        $statement->bindValue(':firstname', $student->getFirstName());
        $statement->bindValue(':lastname', $student->getLastName());
        $statement->bindValue(':age', $student->getAge());
        $statement->bindValue(':id', $student->getId());

        $statement->execute();

        return $this->fetchById($student->getId());
    }

}