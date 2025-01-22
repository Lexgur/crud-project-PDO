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
        $this->validateName($data['lastname']);
        $this->validateAge($data['age']);

        return true;
    }

    public function validateName(string $name): void
    {
        if (empty($name)) {
            throw new NameOrLastnameContainsIncorrectCharactersException('Name or Last name is empty');
        }
        if (preg_match('/[0-9!@#$%^&*()_+=\[\]\{}|;:<>,.?\/\\\\]/', $name)) {
            throw new NameOrLastnameContainsIncorrectCharactersException('Name or Last name contains incorrect characters');
        }
    }

    public function validateAge(int $age): void
    {
        if (empty($age) || $age > 99) {
            throw new AgeIsEmptyOrExceedsTheRangeException('Age must be between 1-99 and not empty');
        }
    }

}