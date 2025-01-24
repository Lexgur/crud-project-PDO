<?php

declare(strict_types=1);

use Crud\Exception\StudentAlreadyExistsException;
use Crud\Repository\StudentRepository;
use PHPUnit\Framework\TestCase;

class StudentRepositoryTest extends TestCase

{
    function testIfSavesToDatabase(): void
    {
        $dbh = new PDO('sqlite:crud-test.sqlite');
        $data = [
            'first_name' => 'Dave',
            'last_name' => 'Maven',
            'age'=> 18,
        ];
        $repository = new StudentRepository($dbh);
        $this->assertTrue($repository->save($data));
    }

    function testIfFailsBecauseDuplicateNameAndLastName(): void
    {
        $this->expectException(StudentAlreadyExistsException::class);
        $dbh = new PDO('sqlite:crud-test.sqlite');
        $data = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'age'=> 18,
        ];
        $repository = new StudentRepository($dbh);
        $repository->save($data);
        $repository->save($data);
    }
}