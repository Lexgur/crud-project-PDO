<?php

use Crud\Exception\IncorrectEmailException;
use Crud\Exception\IncorrectPasswordException;
use Crud\Exception\IncorrectUserNameException;
use Crud\Validation\UserValidator;
use PHPUnit\Framework\TestCase;
use Crud\Model\User;

class UserValidatorTest extends TestCase
{
    public function testIfGivenValuesValidateCorrectly(): void
    {
        $validator = new UserValidator();
        $userEmail = 'david.jones@gmail.com';
        $userName = 'david';
        $password = 'daviD789A';
        $user = new User($userEmail, $userName, $password);

        $this->assertTrue($validator->validate($user));
    }

    public function testIfFailsWithIncorrectEmail(): void
    {
        $this->expectException(IncorrectEmailException::class);

        $validator = new UserValidator();
        $userEmail = '121465465465';
        $userName = 'david';
        $password = 'daviD789A';
        $user = new User($userEmail, $userName, $password);
        $validator->validate($user);
    }

    public function testIfFailsWithEmptyEmail(): void
    {
        $this->expectException(IncorrectEmailException::class);

        $validator = new UserValidator();
        $userEmail = '';
        $userName = 'david';
        $password = 'daviD789A';
        $user = new User($userEmail, $userName, $password);
        $validator->validate($user);
    }

    public function testIfFailsWithIncorrectUserName(): void
    {
        $this->expectException(IncorrectUserNameException::class);

        $validator = new UserValidator();
        $userEmail = 'david.jones@gmail.com';
        $userName = "12315465aaaz";
        $password = 'daviD789A';
        $user = new User($userEmail, $userName, $password);
        $validator->validate($user);
    }

    public function testIfFailsWithEmptyUserName(): void
    {
        $this->expectException(IncorrectUserNameException::class);

        $validator = new UserValidator();
        $userEmail = 'david.jones@gmail.com';
        $userName = "";
        $password = 'daviD789A';
        $user = new User($userEmail, $userName, $password);
        $validator->validate($user);
    }

    public function testIfFailsWithShortPassword(): void
    {
        $this->expectException(IncorrectPasswordException::class);

        $validator = new UserValidator();
        $userEmail = 'david.jones@gmail.com';
        $userName = "david";
        $password = 'daviD77';
        $user = new User($userEmail, $userName, $password);
        $validator->validate($user);
    }

    public function testIfFailsWithPasswordWithoutNumbers(): void
    {
        $this->expectException(IncorrectPasswordException::class);

        $validator = new UserValidator();
        $userEmail = 'david.jones@gmail.com';
        $userName = "david";
        $password = 'justapassword';
        $user = new User($userEmail, $userName, $password);
        $validator->validate($user);
    }

    public function testIfFailsWithPasswordWithoutNoUppercaseLetters(): void
    {
        $this->expectException(IncorrectPasswordException::class);

        $validator = new UserValidator();
        $userEmail = 'david.jones@gmail.com';
        $userName = "david";
        $password = 'password17774';
        $user = new User($userEmail, $userName, $password);
        $validator->validate($user);
    }

    public function testIfFailsWithPasswordWithoutNoLowercaseLetters(): void
    {
        $this->expectException(IncorrectPasswordException::class);

        $validator = new UserValidator();
        $userEmail = 'david.jones@gmail.com';
        $userName = "david";
        $password = 'PASSWORD17774';
        $user = new User($userEmail, $userName, $password);
        $validator->validate($user);
    }
}
