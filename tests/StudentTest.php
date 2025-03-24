<?php

declare(strict_types=1);

use Crud\Model\Student;
use PHPUnit\Framework\TestCase;

class StudentTest extends TestCase
{
    public function testIfItGettersAndConstructorWorks(): void
    {
        $id = 1;
        $firstName = "Dave";
        $lastName = "Bigjhonson";
        $age = 17;
        $student = new Student($firstName, $lastName, $age, $id);

        $this->assertEquals($id, $student->getId());
        $this->assertEquals($firstName, $student->getFirstName());
        $this->assertEquals($lastName, $student->getLastName());
        $this->assertEquals($age, $student->getAge());
    }

    public function testIfWrongValuesFailTheTest(): void
    {
        $this->expectException(TypeError::class);

        $studentId = 'Steve';
        $firstName = "";
        $lastName = "";
        $age = 12;
        $student = new Student($studentId, $firstName, $lastName, $age);
    }
}
