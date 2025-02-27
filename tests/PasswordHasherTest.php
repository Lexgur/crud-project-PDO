<?php

declare(strict_types=1);


use Crud\Service\PasswordHasher;
use PHPUnit\Framework\TestCase;

class PasswordHasherTest extends TestCase
{
    public function testIfPasswordHashes():void
    {
        $passwordHasher = new PasswordHasher();
        $password = 'testPassword1230';
        $hashedPassword = $passwordHasher->hash($password);

        $this->assertNotEquals($password, $hashedPassword);
    }
}
