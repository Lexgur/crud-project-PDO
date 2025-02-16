<?php

declare(strict_types=1);

use Crud\Model\User;
use PHPUnit\Framework\TestCase;

class UserFactoryTest extends TestCase
{
    public function testIfReturnsNewUser(): void
    {
        $user = new User(
            userEmail: "bigboss@gmail.com",
            userPassword: "incorrect",
            userId: 5
        );

        $this->assertNotNull($user->getUserId());
        $this->assertEquals('bigboss@gmail.com', $user->getUserEmail());
        $this->assertEquals('incorrect', $user->getUserPassword());
        $this->assertEquals(5, $user->getUserId());
    }

    public function testIfFactoryFailsWithWrongTypeValues(): void
    {
        $this->expectException(TypeError::class);

        $user = new User(
            userEmail: 123,
            userPassword: 778,
            userId: 4
        );
    }
}
