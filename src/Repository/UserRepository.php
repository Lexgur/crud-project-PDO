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

    public function save(User $user): User
    {
        if ($user->getUserId() === null){
            return $this->insert($user);
        } else {
            return $this->update($user);
        }
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

    public function update(User $user): User
    {
        $statement = $this->connection->prepare('UPDATE `users` SET `email` = :email, `password` = :password WHERE user_id = :user_id');
        $statement->bindValue(':email', $user->getUserEmail());
        $statement->bindValue(':password', $user->getUserPassword());
        $statement->bindValue(':user_id', $user->getUserId());

        $statement->execute();

        return $this->fetchById($user->getUserId());
    }

    public function delete(int $userId): bool
    {
        $statement = $this->connection->prepare('DELETE FROM users WHERE user_id = :user_id');
        $statement->bindValue(':user_id', $userId);
        $statement->execute();

        return true;
    }
}
