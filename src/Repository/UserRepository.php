<?php

declare(strict_types=1);

namespace Crud\Repository;

use Crud\Factory\UserFactory;
use Crud\Model\User;
use PDO;

class UserRepository
{
    public function __construct(
        protected PDO $connection
    ) {

    }
    public function insert(User $user): User
    {
        $statement = $this->connection->prepare('INSERT INTO `users` (`email`, `password`) VALUES (:email, :password)');
        $statement->bindValue(':email', $user->getUserEmail());
        $statement->bindValue(':password', $user->getUserPassword());

        $statement->execute();

        $newId = (int)$this->connection->lastInsertId();

        return $this->fetchById($newId);

    }

    public function fetchById(int $userId): ?User
    {
        $statement = $this->connection->prepare('SELECT * FROM users WHERE user_id = :user_id');
        $statement->execute(['user_id' => $userId]);
        $row = $statement->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }
        return UserFactory::create($row);
    }
}
