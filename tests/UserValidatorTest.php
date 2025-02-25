<?php

declare(strict_types=1);

use Crud\Exception\IncorrectEmailException;
use Crud\Exception\IncorrectPasswordException;
use Crud\Validation\UserValidator;
use PHPUnit\Framework\TestCase;
use Crud\Model\User;

class UserValidatorTest extends TestCase
{
    private PDO $pdo;
    private UserValidator $validator;

    protected function setUp(): void
    {
        $this->pdo = new PDO('sqlite::memory:');
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $this->pdo->exec("
            CREATE TABLE users (
                id INTEGER PRIMARY KEY,
                email TEXT UNIQUE NOT NULL,
                password TEXT NOT NULL
            )
        ");

        $this->validator = new UserValidator($this->pdo);
    }

    public function testIfGivenValuesValidateCorrectly(): void
    {
        $userEmail = 'david.jones@gmail.com';
        $password = 'daviD789A';
        $user = new User($userEmail, $password);

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

        $statement = $this->pdo->prepare("INSERT INTO users (email, password) VALUES (:email, :password)");
        $statement->execute([':email' => $userEmail, ':password' => $password]);

        $this->assertTrue($this->validator->validate($user));

        $userEmail2 = 'david.jones@gmail.com';
        $password2 = 'daviD789A';
        $user2 = new User($userEmail2, $password2);

        $this->validator->validate($user2);
    }
    public function testIfFailsWithShortPassword(): void
    {
        $this->expectException(IncorrectPasswordException::class);

        $userEmail = 'david.jones@gmail.com';
        $password = 'daviD77';
        $user = new User($userEmail, $password);
        $this->validator->validate($user);
    }

    public function testIfFailsWithPasswordWithoutNumbers(): void
    {
        $this->expectException(IncorrectPasswordException::class);

        $userEmail = 'david.jones@gmail.com';
        $password = 'justapassword';
        $user = new User($userEmail, $password);
        $this->validator->validate($user);
    }

    public function testIfFailsWithPasswordWithoutUppercaseLetters(): void
    {
        $this->expectException(IncorrectPasswordException::class);

        $userEmail = 'david.jones@gmail.com';
        $password = 'password17774';
        $user = new User($userEmail, $password);
        $this->validator->validate($user);
    }

    public function testIfFailsWithPasswordWithoutLowercaseLetters(): void
    {
        $this->expectException(IncorrectPasswordException::class);

        $userEmail = 'david.jones@gmail.com';
        $password = 'PASSWORD17774';
        $user = new User($userEmail, $password);
        $this->validator->validate($user);
    }
    public function tearDown(): void
    {
        $this->pdo->exec('DROP TABLE IF EXISTS users');
    }
}
