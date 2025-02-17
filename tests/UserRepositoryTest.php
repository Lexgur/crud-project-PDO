<?php

declare(strict_types=1);

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
    public function tearDown(): void
    {
        $this->dbh->exec('DROP TABLE IF EXISTS users');
    }
}
