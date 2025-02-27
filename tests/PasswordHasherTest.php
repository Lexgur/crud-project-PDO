<?php


use Crud\Service\PasswordHasher;
use PHPUnit\Framework\TestCase;

class PasswordHasherTest extends TestCase
{
    public function testPasswordHasher():void
    {
        $passwordHasher = new PasswordHasher();
        $password = 'testPassword1230';
        $hashedPassword = $passwordHasher->hash($password);

        $this->assertTrue(password_verify($password, $hashedPassword));
    }
}
