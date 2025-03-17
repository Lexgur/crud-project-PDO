<?php

declare(strict_types=1);

use Crud\Connection;
use Crud\DependencyInjection\Container;
use Crud\Exception\IncorrectIdException;
use Crud\Model\User;
use Crud\Repository\UserModelRepository;
use PHPUnit\Framework\TestCase;

class UserRepositoryTest extends TestCase
{
    public function setUp(): void
    {
        $this->testDbPath = __DIR__ . '/crud-test.sqlite';
        $dsn = 'sqlite:' . $this->testDbPath;
        $parameters = [
            'dsn' => $dsn,
            'username' => '',
            'password' => '',
        ];

        $this->container = new Container($parameters);

        $this->pdo = $this->container->get(Connection::class);
        $this->repository = $this->container->get(UserModelRepository::class);

        // Corrected Table Creation
        $this->pdo->connect()->exec("
            CREATE TABLE IF NOT EXISTS users (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                email TEXT NOT NULL UNIQUE,
                password TEXT NOT NULL
            )
        ");
    }

    public function testIfInsertingNewSavesUser(): void
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

    public function testIfFetchesById(): void
    {
        // Insert test user directly
        $this->pdo->connect()->exec("INSERT INTO users (email, password) VALUES ('Test@test.com', 'User12345')");
        $userId = (int)$this->pdo->connect()->lastInsertId();
        $user = $this->repository->fetchById($userId);

        $this->assertEquals($userId, $user->getUserId());
    }

    public function testIfFindByEmail(): void
    {
        // Insert test user directly
        $this->pdo->connect()->exec("INSERT INTO users (email, password) VALUES ('Test@test.com', 'User12345')");

        $user = $this->repository->findByEmail('Test@test.com');

        $this->assertEquals($user->getUserEmail(), 'Test@test.com');
    }

    public function testIfFailsToFetchWithIncorrectTypeId(): void
    {
        $this->expectException(PDOException::class);

        // Insert a row with an invalid ID
        $this->pdo->connect()->exec("INSERT INTO users (email, password, id) VALUES ('test@test.com', 'tesT12345', 'fail')");
        $userId = (int)$this->pdo->connect()->lastInsertId();
        $this->repository->fetchById($userId);
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

    public function testIfUpdateWorks(): void
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

    public function testIfDeleteWorks(): void
    {
        $this->expectException(IncorrectIdException::class);

        $user = new User(
            userEmail: 'dave@gmail.com',
            userPassword: '123Em778a'
        );
        $insertedUser = $this->repository->save($user);
        $this->repository->delete($insertedUser->getUserId());
        $userAfterDelete = $this->repository->fetchById($insertedUser->getUserId());

        $this->assertNull($userAfterDelete);
    }

    public function tearDown(): void
    {
        $this->pdo->connect()->exec('DROP TABLE IF EXISTS users');
    }
}
