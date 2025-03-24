<?php

declare(strict_types=1);


use Crud\Exception\IncorrectPasswordException;
use Crud\Service\PasswordHasher;
use PHPUnit\Framework\TestCase;

class PasswordHasherTest extends TestCase
{
    public function testIfPasswordHashes(): void
    {
        $passwordHasher = new PasswordHasher();
        $password = 'testPassword1230';
        $hashedPassword = $passwordHasher->hash($password);

        $this->assertNotEquals($password, $hashedPassword);
    }
    public function testWithEmptyPassword(): void
    {
        $this->expectException(IncorrectPasswordException::class);

        $passwordHasher = new PasswordHasher();
        $password = '';
        $hashedPassword = $passwordHasher->hash($password);

        $this->assertNotEquals($password, $hashedPassword);
    }
}
