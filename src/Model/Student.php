<?php

declare(strict_types=1);

namespace Crud\Model;

use AllowDynamicProperties;

#[AllowDynamicProperties] class Student
{

    private ?int $id = null;
    private string $firstname;
    private string $lastname;
    private int $age;


    public function __construct(string $firstName, string $lastName, int $age, ?int $id = null)

    {
        $this->firstname = $firstName;
        $this->lastname = $lastName;
        $this->age = $age;
        $this->id = $id;
    }

    public function getFirstName(): string
    {
        return $this->firstname;
    }

    public function setFirstName(string $firstname): void
    {
        $this->firstname = $firstname;
    }

    public function getLastName(): string
    {
        return $this->lastname;
    }

    public function setLastName(string $lastname): void
    {
        $this->lastname = $lastname;
    }

    public function getAge(): int
    {
        return $this->age;
    }

    public function setAge(int $age): void
    {
        $this->age = $age;
    }

    public function getId(): ?int
    {
        return $this->id;
    }
}