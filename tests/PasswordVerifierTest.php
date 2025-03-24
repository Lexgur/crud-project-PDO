<?php

declare(strict_types=1);

use Crud\Service\PasswordHasher;
use Crud\Service\PasswordVerifier;
use PHPUnit\Framework\TestCase;

class PasswordVerifierTest extends TestCase
{
    public function testIfPasswordVerifies(): void
    {
        $passwordHasher = new PasswordHasher();
        $passwordVerifier = new PasswordVerifier();
        $password = 'testPassword1230';
        $hashedPassword = $passwordHasher->hash($password);
        $verifiedPassword = $passwordVerifier->verify($password, $hashedPassword,);

        $this->assertTrue($verifiedPassword);
    }
}
