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
            return $this->insert($student);
        } else {
            return $this->update($student);
        }
    }

    public function insert(Student $student): Student
    {
        $statement = $this->connection->prepare('INSERT INTO `students` (`firstname`, `lastname`, `age`) VALUES (:firstname, :lastname, :age)');
        $statement->bindValue(':firstname', $student->getFirstName());
        $statement->bindValue(':lastname', $student->getLastName());
        $statement->bindValue(':age', $student->getAge());

        $statement->execute();

        $newId = (int)$this->connection->lastInsertId();

        return $this->fetchById($newId);

    }

    public function fetchById(int $id): ?Student
    {
        $statement = $this->connection->prepare('SELECT * FROM students WHERE id = :id');
        $statement->execute([':id' => $id]);
        $row = $statement->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }
        return new Student(
            firstName: $row['firstname'] ?? '',
            lastName: $row['lastname'] ?? '',
            age: (int)$row['age'] ?? 0,
            id: (int)$row['id'] ?? null
        );
    }

    public function update(Student $student): Student
    {
        $statement = $this->connection->prepare('UPDATE `students` SET `firstname` = :firstname, `lastname` = :lastname, `age` = :age WHERE `id` = :id');
        $statement->bindValue(':firstname', $student->getFirstName());
        $statement->bindValue(':lastname', $student->getLastName());
        $statement->bindValue(':age', $student->getAge());
        $statement->bindValue(':id', $student->getId());

        $statement->execute();

        return $this->fetchById($student->getId());
    }

    public function delete(int $id): void
    {
        $statement = $this->connection->prepare('DELETE FROM students WHERE id = :id');
        $statement->bindValue(':id', $id);
        $statement->execute();
    }

    public function viewStudents(): array
    {
        $statement = $this->connection->prepare('SELECT * FROM students');
        $statement->execute();
        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);

        $students = [];
        foreach ($rows as $row) {
            $students[] = new Student(
                firstName: $row['firstname'] ?? '',
                lastName: $row['lastname'] ?? '',
                age: (int) $row['age'] ?? 0,
                id: (int) $row['id'] ?? null
            );
        }
        return $students;
    }

}