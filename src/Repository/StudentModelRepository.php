<?php

declare(strict_types=1);

namespace Crud\Repository;

use Crud\Factory\StudentFactory;
use PDO;

class StudentModelRepository implements ModelRepositoryInterface
{
    public function __construct(
        protected PDO $connection
    ) {

    }

    public function save(object $entity): object
    {
        if ($entity->getUserId() === null){
            return $this->insert($entity);
        } else {
            return $this->update($entity);
        }
    }

    public function insert(object $entity): object
    {
        $statement = $this->connection->prepare('INSERT INTO `students` (`firstname`, `lastname`, `age`) VALUES (:firstname, :lastname, :age)');
        $statement->bindValue(':firstname', $entity->getFirstName());
        $statement->bindValue(':lastname', $entity->getLastName());
        $statement->bindValue(':age', $entity->getAge());

        $statement->execute();

        $newId = (int)$this->connection->lastInsertId();

        return $this->fetchById($newId);

    }

    public function fetchById(int $id): ?object
    {
        $statement = $this->connection->prepare('SELECT * FROM students WHERE id = :id');
        $statement->execute([':id' => $id]);
        $row = $statement->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }
        return StudentFactory::create($row);
    }

    public function update(object $entity): object
    {
        $statement = $this->connection->prepare('UPDATE `students` SET `firstname` = :firstname, `lastname` = :lastname, `age` = :age WHERE `id` = :id');
        $statement->bindValue(':firstname', $entity->getFirstName());
        $statement->bindValue(':lastname', $entity->getLastName());
        $statement->bindValue(':age', $entity->getAge());
        $statement->bindValue(':id', $entity->getId());

        $statement->execute();

        return $this->fetchById($entity->getId());
    }

    public function delete(int $id): bool
    {
        $statement = $this->connection->prepare('DELETE FROM students WHERE id = :id');
        $statement->bindValue(':id', $id);
        $statement->execute();
        return true;
    }

    public function viewStudents(): array
    {
        $statement = $this->connection->prepare('SELECT * FROM students');
        $statement->execute();
        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);

        $students = [];
        foreach ($rows as $row) {
            $students[] = StudentFactory::create($row);
        }
        return $students;
    }
}
