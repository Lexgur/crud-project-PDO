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

        if (empty($data['first_name']) || !is_string($data['first_name'])) {
            return false;
        }
        if (empty($data['last_name']) || !is_string($data['last_name'])) {
            return false;
        }
        $forbidden = "1234567890!@#$%^&*()_+=[]\{}|;:<>,.?/))";
        if (strpbrk($data['first_name'], $forbidden) !== false || strpbrk($data['last_name'], $forbidden) !== false) {
            throw new NameOrLastnameContainsIncorrectCharactersException('Name or Last Name is empty or incorrect characters');
        }

        $min = 1;
        $max = 99;

        if (empty($data['age']) || filter_var($data['age'], FILTER_VALIDATE_INT, array("options" => array("min_range" => $min, "max_range" => $max))) === false) {
            throw new AgeIsEmptyOrExceedsTheRangeException('Age must be between 1-99 and not empty');
        }
        return true;
    }
}