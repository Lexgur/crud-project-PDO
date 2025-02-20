<?php
//TODO User CRUD controlleriai, User login, User register
declare(strict_types=1);

use Crud\Model\User;

use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testIfItGettersAndConstructorWorks(): void
    {
        $userId = 1;
        $userPassword = "cocoIsCool";
        $userEmail = "bigboss@gmail.com";
        $user = new User($userEmail, $userPassword, $userId);

        $this->assertEquals($userId, $user->getUserId());
        $this->assertEquals($userEmail, $user->getUserEmail());
        $this->assertEquals($userPassword, $user->getUserPassword());
    }

    public function testIfWrongValuesFailTheTest(): void
    {
        $this->expectException(TypeError::class);

        $userId = '11';
        $userEmail = "";
        $userPassword = 12;
        $user = new User($userEmail, $userPassword, $userId);
    }
}
