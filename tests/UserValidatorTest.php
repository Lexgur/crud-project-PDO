<?php

declare(strict_types=1);

use Crud\Exception\IncorrectEmailException;
use Crud\Exception\IncorrectPasswordException;
use Crud\Validation\UserValidator;
use PHPUnit\Framework\TestCase;
use Crud\Model\User;

class UserValidatorTest extends TestCase
{
    public function testIfGivenValuesValidateCorrectly(): void
    {
        $validator = new UserValidator();
        $userEmail = 'david.jones@gmail.com';
        $password = 'daviD789A';
        $user = new User($userEmail, $password);

        $this->assertTrue($validator->validate($user));
    }

    public function testIfFailsWithIncorrectEmail(): void
    {
        $this->expectException(IncorrectEmailException::class);

        $validator = new UserValidator();
        $userEmail = '121465465465';
        $password = 'daviD789A';
        $user = new User($userEmail, $password);
        $validator->validate($user);
    }

    public function testIfFailsWithEmptyEmail(): void
    {
        $this->expectException(IncorrectEmailException::class);

        $validator = new UserValidator();
        $userEmail = '';
        $password = 'daviD789A';
        $user = new User($userEmail, $password);
        $validator->validate($user);
    }

    public function testIfFailsWithShortPassword(): void
    {
        $this->expectException(IncorrectPasswordException::class);

        $validator = new UserValidator();
        $userEmail = 'david.jones@gmail.com';
        $password = 'daviD77';
        $user = new User($userEmail, $password);
        $validator->validate($user);
    }

    public function testIfFailsWithPasswordWithoutNumbers(): void
    {
        $this->expectException(IncorrectPasswordException::class);

        $validator = new UserValidator();
        $userEmail = 'david.jones@gmail.com';
        $password = 'justapassword';
        $user = new User($userEmail, $password);
        $validator->validate($user);
    }

    public function testIfFailsWithPasswordWithoutNoUppercaseLetters(): void
    {
        $this->expectException(IncorrectPasswordException::class);

        $validator = new UserValidator();
        $userEmail = 'david.jones@gmail.com';
        $password = 'password17774';
        $user = new User($userEmail, $password);
        $validator->validate($user);
    }

    public function testIfFailsWithPasswordWithoutNoLowercaseLetters(): void
    {
        $this->expectException(IncorrectPasswordException::class);

        $validator = new UserValidator();
        $userEmail = 'david.jones@gmail.com';
        $password = 'PASSWORD17774';
        $user = new User($userEmail, $password);
        $validator->validate($user);
    }
}
