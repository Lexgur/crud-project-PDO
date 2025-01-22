<?php

declare(strict_types=1);

use Crud\Exception\AgeIsEmptyOrExceedsTheRangeException;
use Crud\Exception\NameOrLastnameContainsIncorrectCharactersException;
use Crud\Validation\StudentValidator;
use PHPUnit\Framework\TestCase;

class StudentValidatorTest extends TestCase
{
    /**
     * @throws NameOrLastnameContainsIncorrectCharactersException
     * @throws AgeIsEmptyOrExceedsTheRangeException
     */
    function testIfGivenValuesValidateCorrectly(): void
    {
        $validator = new StudentValidator();
        $name = 'Jon';
        $lastname = 'Snow';
        $age = 25;
        $data = [
            'name' => $name,
            'lastname' => $lastname,
            'age' => $age,
        ];
        $this->assertTrue($validator->validate($data));
    }

    function testIfEmptyNameIsNotAllowed(): void
    {
        $this->expectException(NameOrLastnameContainsIncorrectCharactersException::class);
        $validator = new StudentValidator();
        $name = '';
        $validator->validateName($name);
    }

    function testIfFailsWithIncorrectName(): void
    {
        $this->expectException(NameOrLastnameContainsIncorrectCharactersException::class);
        $validator = new StudentValidator();
        $name = '25';
        $validator->validateName($name);
    }

    function testIfFailsWithIncorrectAgeRange(): void
    {
        $this->expectException(AgeIsEmptyOrExceedsTheRangeException::class);
        $validator = new StudentValidator();
        $age = 110;
        $validator->validateAge($age);

    }

    function testIfFailsWithEmptyAge(): void
    {
        $this->expectException(AgeIsEmptyOrExceedsTheRangeException::class);
        $validator = new StudentValidator();
        $age = 0;
        $validator->validateAge($age);

    }
}