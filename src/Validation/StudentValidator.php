<?php

namespace Crud\Validation;


class StudentValidator
{
    public function validate(array $data): bool
    {

        if (empty($data['first_name']) || !is_string($data['first_name'])) {
            return false;
        }
        if (empty($data['last_name']) || !is_string($data['last_name'])) {
            return false;
        }

        $min = 1;
        $max = 99;

        if (empty($data['age']) || filter_var($data['age'], FILTER_VALIDATE_INT, array("options" => array("min_range" => $min, "max_range" => $max))) === false) {
            return false;
        }
        return true;
    }
}