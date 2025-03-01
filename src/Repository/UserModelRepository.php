<?php

declare(strict_types=1);

namespace Crud\Repository;

use Crud\Exception\IncorrectEmailException;
use Crud\Exception\IncorrectIdException;
use Crud\Factory\UserFactory;
use PDO;

class UserModelRepository extends BaseRepositoryClass implements UserModelInterface
{
    public function save(object $entity): object
    {
        if ($entity->getUserId() === null) {
            return $this->insert($entity);
        } else {
            return $this->update($entity);
        }
    }
    public function insert(object $entity): object
    {
        $statement = $this->connection->prepare('INSERT INTO `users` (`email`, `password`) VALUES (:email, :password)');
        $statement->bindValue(':email', $entity->getUserEmail());
        $statement->bindValue(':password', $entity->getUserPassword());

        $statement->execute();

        $newId = (int)$this->connection->lastInsertId();

        return $this->fetchById($newId);

    }

    public function fetchById(int $entityId): ?object
    {
        $statement = $this->connection->prepare('SELECT * FROM users WHERE id = :id');
        $statement->execute([':id' => $entityId]);
        $row = $statement->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            throw new IncorrectIdException('Asked id does not exist');
        }
        return UserFactory::create($row);
    }

    public function findByEmail(string $userEmail): ?object
    {
        $statement = $this->connection->prepare('SELECT * FROM users WHERE email = :email');
        $statement->execute([':email' => $userEmail]);
        $row = $statement->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            throw new IncorrectEmailException('Email does not exist');
        }
        return UserFactory::create($row);
    }

    public function update(object $entity): object
    {
        $statement = $this->connection->prepare('UPDATE `users` SET `email` = :email, `password` = :password WHERE id = :id');
        $statement->bindValue(':email', $entity->getUserEmail());
        $statement->bindValue(':password', $entity->getUserPassword());
        $statement->bindValue(':id', $entity->getUserId());

        $statement->execute();

        return $this->fetchById($entity->getUserId());
    }

    public function delete(int $entityId): bool
    {
        $statement = $this->connection->prepare('DELETE FROM users WHERE id = :id');
        $statement->bindValue(':id', $entityId);
        $statement->execute();

        return true;
    }

    public function viewUser(int $userId): ?object
    {
        $statement = $this->connection->prepare('SELECT * FROM users WHERE id = :id');
        $statement->execute([':id' => $userId]);
        $row = $statement->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        return UserFactory::create($row);
    }
}
