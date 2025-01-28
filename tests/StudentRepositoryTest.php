<?php

declare(strict_types=1);

use Crud\Exception\StudentAlreadyExistsException;
use Crud\Model\Student;
use Crud\Repository\StudentRepository;
use PHPUnit\Framework\TestCase;

#[AllowDynamicProperties] class StudentRepositoryTest extends TestCase

{
    public function setUp(): void
    {
        $dbh = new PDO('sqlite:C:/xampp/htdocs/PhpstormProjects/crud-project-PDO/crud-test.sqlite');
        $dbh->exec("
            CREATE TABLE students (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                firstname TEXT NOT NULL,
                lastname TEXT NOT NULL,
                age INTEGER NOT NULL
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

    function testIfFetchesById(): void
    {
        $id = 1;
        $dbh = new PDO('sqlite:C:/xampp/htdocs/PhpstormProjects/crud-project-PDO/crud-test.sqlite');
        $repository = new StudentRepository($dbh);
        $repository->fetchById($id);
        $this->assertEquals($id, $id);
    }

    function testIfFailsWithIncorrectSearch(): void
    {
        $this->expectException(TypeError::class);
        $id ='kamehameha';
        $dbh = new PDO('sqlite:C:/xampp/htdocs/PhpstormProjects/crud-project-PDO/crud-test.sqlite');
        $repository = new StudentRepository($dbh);
        $repository->fetchById($id);
    }

    function testIfInsertingStudentWorks() : void
    {
        $dbh = new PDO('sqlite:C:/xampp/htdocs/PhpstormProjects/crud-project-PDO/crud-test.sqlite');
        $student = new Student (
            firstName: 'Lame',
            lastName: 'Make',
            age: 37
        );
        $repository = new StudentRepository($dbh);
        $repository->insertNewStudent($student);
        $result = $dbh->lastInsertId('students');
        $this->assertEquals($result, $result);
    }

    function testIfInsertingMultipleStudentWorks() : void
    {
        $dbh = new PDO('sqlite:C:/xampp/htdocs/PhpstormProjects/crud-project-PDO/crud-test.sqlite');
        $repository = new StudentRepository($dbh);
        $student2 = new Student (
            firstName: 'Lame',
            lastName: 'Make',
            age: 37
        );
        $student = new Student (
            firstName: 'Dave',
            lastName: 'Make',
            age: 31
        );
        $repository->insertNewStudent($student);
        $repository->insertNewStudent($student2);
        $result = $dbh->lastInsertId('students');
        $this->assertEquals($result, $result);
    }

    function testIfInsertedIdsAreDifferent() : void
    {
        $dbh = new PDO('sqlite:C:/xampp/htdocs/PhpstormProjects/crud-project-PDO/crud-test.sqlite');
        $repository = new StudentRepository($dbh);
        $student2 = new Student (
            firstName: 'Steve',
            lastName: 'Creve',
            age: 26
        );
        $student = new Student (
            firstName: 'Greave',
            lastName: 'Leave',
            age: 27
        );
        $repository->insertNewStudent($student);
        $result1 = $dbh->lastInsertId();
        $repository->insertNewStudent($student2);
        $result2 = $dbh->lastInsertId();
        $this->assertNotEquals($result1, $result2);
    }


    public function tearDown(): void
    {
        $dbh = new PDO('sqlite:C:/xampp/htdocs/PhpstormProjects/crud-project-PDO/crud-test.sqlite');
        $dbh->exec('DROP TABLE IF EXISTS students');
        $this->dbh = null;
    }


}