<?php

namespace Crud\Validation;


use Crud\Exception\AgeIsEmptyOrExceedsTheRangeException;
use Crud\Exception\NameOrLastnameContainsIncorrectCharactersException;

class StudentValidator
{
    /**
     * @throws NameOrLastnameContainsIncorrectCharactersException
     * @throws AgeIsEmptyOrExceedsTheRangeException
     */
    public function validate(array $data): bool
    {
        $this->validateName($data['name']);
        $this->validateLastName($data['lastname']);
        $this->validateAge($data['age']);

        return true;
    }

    function validateName(string $name): void
    {
        if (empty($name)) {
            throw new NameOrLastnameContainsIncorrectCharactersException('Name is empty');
        }
        if (preg_match('/[0-9!@#$%^&*()_+=\[\]\{}|;:<>,.?\/\\\\]/', $name)) {
            throw new NameOrLastnameContainsIncorrectCharactersException('Name contains incorrect characters');
        }
    }

    function validateLastName(string $lastname):void
    {
        if (empty($lastname)) {
            throw new NameOrLastnameContainsIncorrectCharactersException('Last name is empty');
        }
        else if (preg_match('/[0-9!@#$%^&*()_+=\[\]\{}|;:<>,.?\/\\\\]/', $lastname)) {
            throw new NameOrLastnameContainsIncorrectCharactersException('Last name contains incorrect characters');
        }
    }

    function validateAge(int $age): void
    {
        if (empty($age) || $age > 99) {
            throw new AgeIsEmptyOrExceedsTheRangeException('Age must be between 1-99 and not empty');
        }
    }

}