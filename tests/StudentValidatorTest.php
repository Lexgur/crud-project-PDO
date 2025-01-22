<?php

declare(strict_types=1);

use Crud\Validation\StudentValidator;
use PHPUnit\Framework\TestCase;

class StudentValidatorTest extends TestCase
{
    /**
     * @throws \Crud\Exception\NameOrLastnameContainsIncorrectCharactersException
     * @throws \Crud\Exception\AgeIsEmptyOrExceedsTheRangeException
     */
    function testIfGivenValuesValidateCorrectly(): void
    {
        $validator = new StudentValidator();
        $data = [
            'first_name' => 'Jon',
            'last_name' => 'Snow',
            'age' => 25
        ];
        $this->assertTrue($validator->validate($data));
    }

    function testIfGivenValuesAreNotAllowed(): void
    {
        $validator = new StudentValidator();
        $data = [
            'first_name' => '',
            'last_name' => '',
            'age' => 25
        ];
        $this->assertFalse($validator->validate($data));
    }

    function testIfFailsWithIncorrectName(): void
    {
        $this->expectException(\Crud\Exception\NameOrLastnameContainsIncorrectCharactersException::class);
        $validator = new StudentValidator();
        $data = [
            'first_name' => '25',
            'last_name' => 'Gee',
            'age' => 25
        ];
        $validator->validate($data);

    }

    function testIfFailsWithIncorrectLastName(): void
    {
        $this->expectException(\Crud\Exception\NameOrLastnameContainsIncorrectCharactersException::class);
        $validator = new StudentValidator();
        $data = [
            'first_name' => 'Dave',
            'last_name' => '22',
            'age' => 25
        ];
        $validator->validate($data);

    }

    function testIfFailsWithIncorrectAgeRange(): void
    {
        $this->expectException(\Crud\Exception\AgeIsEmptyOrExceedsTheRangeException::class);
        $validator = new StudentValidator();
        $data = [
            'first_name' => 'Dave',
            'last_name' => 'Mave',
            'age' => 110
        ];
        $validator->validate($data);

    }

    function testIfFailsWithEmptyAge(): void
    {
        $this->expectException(\Crud\Exception\AgeIsEmptyOrExceedsTheRangeException::class);
        $validator = new StudentValidator();
        $data = [
            'first_name' => 'Dave',
            'last_name' => 'Mave',
            'age' => ''
        ];
        $validator->validate($data);

    }
}