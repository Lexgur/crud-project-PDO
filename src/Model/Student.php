<?php

declare(strict_types=1);

namespace Crud\Model;

use Crud\Exception\ValueAskedIsEmptyOrIncorrectTypeException;

class Student
{

    private int $id;
    private string $firstname;
    private string $lastname;
    private int $age;



    public function __construct(int $id,string $firstName, string $lastName, int $age)

    {
        $this->id = $id;
        $this->firstname = $firstName;
        $this->lastname = $lastName;
        $this->age = $age;
    }
    public function getId(): int
    {
        return $this->id;
    }
    public function getFirstName(): string
    {
        return $this->firstname;
    }

    public function getLastName(): string
    {
        return $this->lastname;
    }

    public function getAge(): int
    {
        return $this->age;
    }
}