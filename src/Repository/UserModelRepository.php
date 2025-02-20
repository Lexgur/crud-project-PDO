<?php

declare(strict_types=1);

namespace Crud\Repository;

use Crud\Factory\UserFactory;
use PDO;

class UserModelRepository implements ModelRepositoryInterface
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
        $statement = $this->connection->prepare('INSERT INTO `users` (`email`, `password`) VALUES (:email, :password)');
        $statement->bindValue(':email', $entity->getUserEmail());
        $statement->bindValue(':password', $entity->getUserPassword());

        $statement->execute();

        $newId = (int)$this->connection->lastInsertId();

        return $this->fetchById($newId);

    }

    public function fetchById(int $id): ?object
    {
        $statement = $this->connection->prepare('SELECT * FROM users WHERE user_id = :user_id');
        $statement->execute(['user_id' => $id]);
        $row = $statement->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }
        return UserFactory::create($row);
    }

    public function update(object $entity): object
    {
        $statement = $this->connection->prepare('UPDATE `users` SET `email` = :email, `password` = :password WHERE user_id = :user_id');
        $statement->bindValue(':email', $entity->getUserEmail());
        $statement->bindValue(':password', $entity->getUserPassword());
        $statement->bindValue(':user_id', $entity->getUserId());

        $statement->execute();

        return $this->fetchById($entity->getUserId());
    }

    public function delete(int $id): bool
    {
        $statement = $this->connection->prepare('DELETE FROM users WHERE user_id = :user_id');
        $statement->bindValue(':user_id', $id);
        $statement->execute();

        return true;
    }
}
