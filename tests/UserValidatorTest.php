<?php

declare(strict_types=1);

use Crud\Exception\IncorrectEmailException;
use Crud\Exception\IncorrectPasswordException;
use Crud\Validation\UserValidator;
use PHPUnit\Framework\TestCase;
use Crud\Model\User;
use Crud\Repository\UserModelRepository;

class UserValidatorTest extends TestCase
{
    private PDO $dbh;
    private UserValidator $validator;
    private UserModelRepository $repository;

    protected function setUp(): void
    {
        $this->testDbPath = __DIR__ . '/crud-test.sqlite';
        $this->dbh = new PDO('sqlite:' . $this->testDbPath);
        $this->repository = new UserModelRepository($this->dbh);
        $this->dbh->exec("
        CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        email TEXT NOT NULL,
        password TEXT NOT NULL
    )
");

        // Pass the repository to the UserValidator constructor
        $this->validator = new UserValidator($this->repository);
    }

    public function testIfGivenValuesValidateCorrectly(): void
    {
        $userEmail = 'david.jones@gmail.com';
        $userPassword = 'password123Szzz';
        $user = new User($userEmail, $userPassword);

        $this->assertTrue($this->validator->validate($user));
    }

    public function testIfFailsWithIncorrectEmail(): void
    {
        $this->expectException(IncorrectEmailException::class);

        $userEmail = '121465465465';
        $password = 'daviD789A';
        $user = new User($userEmail, $password);
        $this->validator->validate($user);
    }

    public function testIfFailsWithEmptyEmail(): void
    {
        $this->expectException(IncorrectEmailException::class);

        $userEmail = '';
        $password = 'daviD789A';
        $user = new User($userEmail, $password);
        $this->validator->validate($user);
    }

    public function testIfDuplicateEmailsAreNotAllowed(): void
    {
        $this->expectException(IncorrectEmailException::class);

        $userEmail = 'david.jones@gmail.com';
        $password = 'daviD789A';
        $user = new User($userEmail, $password);

        $statement = $this->dbh->prepare("INSERT INTO users (email, password) VALUES (:email, :password)");
        $statement->execute([':email' => $userEmail, ':password' => $password]);

        $this->assertTrue($this->validator->validate($user));

        $userEmail2 = 'david.jones@gmail.com';
        $password2 = 'daviD789A';
        $user2 = new User($userEmail2, $password2);

        $this->validator->validate($user2);
    }

    public function testIfOneCanValidateWithSameIdAndEmail(): void
    {
        $userEmail = 'david.jones@gmail.com';
        $password = 'daviD789A';
        $userId = 1;
        $user = new User($userEmail, $password, $userId);

        $statement = $this->dbh->prepare("INSERT INTO users (email, password) VALUES (:email, :password)");
        $statement->execute([':email' => $userEmail, ':password' => $password]);

        $this->assertTrue($this->validator->validate($user));

        $userEmail2 = 'david.jones@gmail.com';
        $password2 = 'daviD789A';
        $userId2 = 1;
        $user2 = new User($userEmail2, $password2, $userId2);

        $this->validator->validate($user2);
    }

    public function testIfFindsByEmailAndChecksPasswordCorrectly(): void
    {
        $userEmail = 'david.jones@gmail.com';
        $password = 'test123Test';
        $user = new User($userEmail, $password);

        $statement = $this->dbh->prepare("INSERT INTO users (email, password) VALUES (:email, :password)");
        $statement->execute([':email' => $userEmail, ':password' => $password]);

        $existingUser = $this->repository->findByEmail($userEmail);
        $checkedPassword = $this->validator->passwordExists($password, $userEmail);

        $this->assertNotNull($existingUser, 'User should exist in the database');
        $this->assertTrue($checkedPassword);
    }

    public function testIfFindsByEmailAndChecksPasswordWithIncorrectPassword(): void
    {
        $this->expectException(IncorrectPasswordException::class);

        $userEmail = 'david.jones@gmail.com';
        $password = 'test123Test';
        $user = new User($userEmail, $password);
        $passwordWrong = 'test321Test';

        $statement = $this->dbh->prepare("INSERT INTO users (email, password) VALUES (:email, :password)");
        $statement->execute([':email' => $userEmail, ':password' => $password]);

        $existingUser = $this->repository->findByEmail($userEmail);
        $checkedPassword = $this->validator->passwordExists($passwordWrong, $userEmail);
    }



    public function tearDown(): void
    {
        $this->dbh->exec('DROP TABLE IF EXISTS users');
    }
}
