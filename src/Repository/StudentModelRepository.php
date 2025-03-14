<?php

declare(strict_types=1);

namespace Crud\Repository;

use Crud\Connection;
use Crud\Exception\IncorrectIdException;
use Crud\Factory\StudentFactory;
use Crud\Model\Student;
use PDO;

class StudentModelRepository extends BaseRepositoryClass implements StudentModelInterface
{
    private PDO $pdo;

    public function __construct(Connection $connection)
    {
        parent::__construct($connection);
        $this->pdo = $connection->connect();
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
        $statement = $this->pdo->prepare('INSERT INTO `students` (`firstname`, `lastname`, `age`) VALUES (:firstname, :lastname, :age)');
        $statement->bindValue(':firstname', $student->getFirstName());
        $statement->bindValue(':lastname', $student->getLastName());
        $statement->bindValue(':age', $student->getAge());

        $statement->execute();

        $newId = (int)$this->pdo->lastInsertId();

        return $this->fetchById($newId);

    }

    public function fetchById(int $studentId): ?Student
    {
        $statement = $this->pdo->prepare('SELECT * FROM students WHERE id = :id');
        $statement->execute([':id' => $studentId]);
        $row = $statement->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            throw new IncorrectIdException('Asked id does not exist');
        }
        return StudentFactory::create($row);
    }

    public function update(Student $student): Student
    {
        $statement = $this->pdo->prepare('UPDATE `students` SET `firstname` = :firstname, `lastname` = :lastname, `age` = :age WHERE `id` = :id');
        $statement->bindValue(':firstname', $student->getFirstName());
        $statement->bindValue(':lastname', $student->getLastName());
        $statement->bindValue(':age', $student->getAge());
        $statement->bindValue(':id', $student->getId());

        $statement->execute();

        return $this->fetchById($student->getId());
    }

    public function delete(int $studentId): bool
    {
        $statement = $this->pdo->prepare('DELETE FROM students WHERE id = :id');
        $statement->bindValue(':id', $studentId);
        $statement->execute();
        return true;
    }

    public function viewStudents(): array
    {
        $statement = $this->pdo->prepare('SELECT * FROM students');
        $statement->execute();
        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);

        $students = [];
        foreach ($rows as $row) {
            $students[] = StudentFactory::create($row);
        }
        return $students;
    }
}
