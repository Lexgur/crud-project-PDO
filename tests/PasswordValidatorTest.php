<?php


use Crud\Exception\IncorrectPasswordException;
use Crud\Validation\PasswordValidator;
use PHPUnit\Framework\TestCase;

class PasswordValidatorTest extends TestCase
{
    private PasswordValidator $validator;
    protected function setUp(): void
    {
        $this->validator = new PasswordValidator();
    }
    public function testIfFailsWithShortPassword(): void
    {
        $this->expectException(IncorrectPasswordException::class);

        $password = 'daviD77';
        $this->validator->validate($password);
    }

    public function testIfFailsWithPasswordWithoutNumbers(): void
    {
        $this->expectException(IncorrectPasswordException::class);

        $password = 'justapassword';
        $this->validator->validate($password);
    }

    public function testIfFailsWithPasswordWithoutUppercaseLetters(): void
    {
        $this->expectException(IncorrectPasswordException::class);

        $password = 'password17774';
        $this->validator->validate($password);
    }

    public function testIfFailsWithPasswordWithoutLowercaseLetters(): void
    {
        $this->expectException(IncorrectPasswordException::class);

        $password = 'PASSWORD17774';
        $this->validator->validate($password);
    }
}
