<?php

declare(strict_types=1);

use Crud\Exception\StudentAlreadyExistsException;
use Crud\Repository\StudentRepository;
use PHPUnit\Framework\TestCase;

#[AllowDynamicProperties] class StudentRepositoryTest extends TestCase

{
    public function setUp(): void
    {
        $dbh = new PDO('sqlite:C:/xampp/htdocs/PhpstormProjects/crud-project-PDO/crud-test.sqlite');
        $dbh->exec("
            CREATE TABLE students (
                id_student INTEGER PRIMARY KEY AUTOINCREMENT,
                student_first_name TEXT NOT NULL,
                student_last_name TEXT NOT NULL,
                student_age INTEGER NOT NULL
            )
        ");


    }
    function testIfSavesToDatabase(): void
    {
        $dbh = new PDO('sqlite:C:/xampp/htdocs/PhpstormProjects/crud-project-PDO/crud-test.sqlite');
        $data = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'age'=> 18,
        ];
        $repository = new StudentRepository($dbh);
        $this->assertTrue($repository->save($data));
    }

    function testIfFailsBecauseDuplicateNameAndLastName(): void
    {
        $this->expectException(StudentAlreadyExistsException::class);
        $dbh = new PDO('sqlite:C:/xampp/htdocs/PhpstormProjects/crud-project-PDO/crud-test.sqlite');
        $data = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'age'=> 18,
        ];
        $repository = new StudentRepository($dbh);
        $repository->save($data);
        $repository->save($data);
    }

    public function tearDown(): void
    {
        $dbh = new PDO('sqlite:C:/xampp/htdocs/PhpstormProjects/crud-project-PDO/crud-test.sqlite');
        $dbh->exec('DROP TABLE IF EXISTS students');
        $this->dbh = null;
    }

}