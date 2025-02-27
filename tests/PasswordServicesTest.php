<?php

declare(strict_types=1);


use Crud\Service\PasswordHasher;
use Crud\Service\PasswordVerifier;
use PHPUnit\Framework\TestCase;

class PasswordServicesTest extends TestCase
{
    public function testIfPasswordHashes():void
    {
        $passwordHasher = new PasswordHasher();
        $password = 'testPassword1230';
        $hashedPassword = $passwordHasher->hash($password);

        $this->assertNotEquals($password, $hashedPassword);
    }

    public function testIfPasswordVerifies():void
    {
        $passwordHasher = new PasswordHasher();
        $passwordVerifier = new PasswordVerifier();
        $password = 'testPassword1230';
        $hashedPassword = $passwordHasher->hash($password);
        $verifiedPassword = $passwordVerifier->verify($password, $hashedPassword,);

        $this->assertTrue($verifiedPassword);
    }
}
