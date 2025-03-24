<?php

declare(strict_types=1);

use Crud\Exception\AgeIsEmptyOrExceedsTheRangeException;
use Crud\Exception\NameOrLastnameContainsIncorrectCharactersException;
use Crud\Model\Student;
use Crud\Validation\StudentValidator;
use PHPUnit\Framework\TestCase;

class StudentValidatorTest extends TestCase
{
    public function testIfGivenValuesValidateCorrectly(): void
    {
        $validator = new StudentValidator();
        $firstName = 'Jon';
        $lastName = 'Snow';
        $age = 25;
        $student = new Student($firstName, $lastName, $age);

        $this->assertTrue($validator->validate($student));
    }

    public function testIfEmptyNameIsNotAllowed(): void
    {
        $this->expectException(NameOrLastnameContainsIncorrectCharactersException::class);

        $validator = new StudentValidator();
        $firstName = '';
        $validator->validateName($firstName);
    }

    public function testIfFailsWithIncorrectName(): void
    {
        $this->expectException(NameOrLastnameContainsIncorrectCharactersException::class);

        $validator = new StudentValidator();
        $name = '25';
        $validator->validateName($name);
    }

    public function testIfFailsWithIncorrectAgeRange(): void
    {
        $this->expectException(AgeIsEmptyOrExceedsTheRangeException::class);

        $validator = new StudentValidator();
        $age = 110;
        $validator->validateAge($age);
    }

    public function testIfFailsWithEmptyAge(): void
    {
        $this->expectException(AgeIsEmptyOrExceedsTheRangeException::class);

        $validator = new StudentValidator();
        $age = 0;
        $validator->validateAge($age);
    }
}
