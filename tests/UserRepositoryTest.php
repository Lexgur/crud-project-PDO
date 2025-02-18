<?php

declare(strict_types=1);

use Crud\Exception\IncorrectEmailException;
use Crud\Model\User;
use Crud\Repository\UserRepository;
use PHPUnit\Framework\TestCase;

class UserRepositoryTest extends TestCase
{
    public function setUp(): void
    {
        $this->testDbPath = __DIR__ . '/crud-test.sqlite';
        $this->dbh = new PDO('sqlite:' . $this->testDbPath);
        $this->repository = new UserRepository($this->dbh);
        $this->dbh->exec("
        CREATE TABLE IF NOT EXISTS users (
        user_id INTEGER PRIMARY KEY AUTOINCREMENT,
        email TEXT NOT NULL,
        password TEXT NOT NULL
    )
");
    }
    public function testIfFetchesById(): void
    {
        $statement = $this->dbh->prepare("INSERT INTO users (email, password) VALUES ('Test@test.com', 'User12345')");
        $statement->execute();
        $userId = (int)$this->dbh->lastInsertId();
        $user = $this->repository->fetchById($userId);

        $this->assertEquals($userId, $user->getUserId());
    }
    public function testIfInsertsNewUserWorks(): void
    {
        $user = new User(
            userEmail: 'dave@gmail.com',
            userPassword: '123Em778a'
        );
        $insertedUser = $this->repository->insert($user);

        $this->assertNotNull($insertedUser->getUserId());
        $this->assertEquals($user->getUserEmail(), $insertedUser->getUserEmail());
        $this->assertEquals($user->getUserPassword(), $insertedUser->getUserPassword());
    }
    public function testIfInsertingMultipleUsersWorkCorrectly(): void
    {
        $user1 = new User(
            userEmail: 'test@test.com',
            userPassword: 'tEst1799'
        );
        $insertedUser1 = $this->repository->insert($user1);
        $user2 = new User(
            userEmail: 'test2@test.com',
            userPassword: 'Test1799'
        );
        $insertedUser2 = $this->repository->insert($user2);

        $this->assertNotNull($insertedUser1->getUserId());
        $this->assertEquals($insertedUser1->getUserEmail(), $user1->getUserEmail());
        $this->assertEquals($insertedUser1->getUserPassword(), $user1->getUserPassword());

        $this->assertNotNull($insertedUser2->getUserId());
        $this->assertEquals($insertedUser2->getUserEmail(), $user2->getUserEmail());
        $this->assertEquals($insertedUser2->getUserPassword(), $user2->getUserPassword());

        $this->assertNotEquals($insertedUser1->getUserId(), $insertedUser2->getUserId());
    }

    public function testIfUpdateWorks():void
    {
        $user = new User(
            userEmail: 'dave@gmail.com',
            userPassword: '123Em778a'
        );
        $insertedUser = $this->repository->save($user);

        $insertedUser->setUserEmail('davenowmarried@gmail.com');
        $insertedUser->setUserPassword('newPassword123');

        $updatedUser = $this->repository->save($insertedUser);

        $this->assertNotNull($updatedUser->getUserId());
        $this->assertEquals('davenowmarried@gmail.com', $updatedUser->getUserEmail());
        $this->assertEquals('newPassword123', $updatedUser->getUserPassword());
    }
    public function tearDown(): void
    {
        $this->dbh->exec('DROP TABLE IF EXISTS users');
    }
}
