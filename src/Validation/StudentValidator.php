<?php

namespace Crud\Validation;

use Crud\Model\Student;
use Crud\Exception\AgeIsEmptyOrExceedsTheRangeException;
use Crud\Exception\NameOrLastnameContainsIncorrectCharactersException;

class StudentValidator
{
    /**
     * @throws NameOrLastnameContainsIncorrectCharactersException
     * @throws AgeIsEmptyOrExceedsTheRangeException
     */
    public function validate(Student $student): bool
    {
        $this->validateName($student->getFirstName());
        $this->validateName($student->getLastName());
        $this->validateAge($student->getAge());

        return true;
    }

    public function validateName($firstName): void
    {
        if (empty($firstName)) {
            throw new NameOrLastnameContainsIncorrectCharactersException('Name or Last name is empty');
        }
        if (preg_match('/[^a-zA-Z\w+\-]/', $firstName)) {
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