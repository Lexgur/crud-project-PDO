<?php

declare(strict_types=1);

use Crud\Model\Student;
use Crud\Repository\StudentRepository;
use PHPUnit\Framework\TestCase;

#[AllowDynamicProperties] class StudentRepositoryTest extends TestCase

{
    public function setUp(): void
    {
        $this->dbh = new PDO('sqlite:C:/xampp/htdocs/PhpstormProjects/crud-project-PDO/crud-test.sqlite');
        $this->repository = new StudentRepository($this->dbh);
        $this->dbh->exec("
            CREATE TABLE students (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                firstname TEXT NOT NULL,
                lastname TEXT NOT NULL,
                age INTEGER NOT NULL
            )
        ");
    }
//    function testIfSavesToDatabase(): void
//    {
//        $dbh = $this->dbh;
//        $data = [
//            'first_name' => 'John',
//            'last_name' => 'Doe',
//            'age'=> 18,
//        ];
//        $repository = new StudentRepository($dbh);
//        $this->assertTrue($repository->save($data));
//    }

//    function testIfFailsBecauseDuplicateNameAndLastName(): void
//    {
//        $this->expectException(StudentAlreadyExistsException::class);
//        $dbh = $this->dbh;
//        $data = [
//            'first_name' => 'John',
//            'last_name' => 'Doe',
//            'age'=> 18,
//        ];
//        $repository = new StudentRepository($dbh);
//        $repository->save($data);
//        $repository->save($data);
//    }

    function testIfFetchesById(): void
    {
        $statement = $this->dbh->prepare("INSERT INTO students (firstname, lastname, age) VALUES ('Test', 'Student', 25)");
        $statement->execute();
        $id = (int)$this->dbh->lastInsertId();
        $student = $this->repository->fetchById($id);

        $this->assertEquals($id, $student->getId());
    }

    function testIfFailsToFetchWithIncorrectType(): void
    {
        $this->expectException(PDOException::class);

        $statement = $this->dbh->prepare("INSERT INTO students (firstname, lastname, age, id) VALUES ('Test', 'Student', 25, 'kamehameha')");
        $statement->execute();
        $id = (int)$this->dbh->lastInsertId();
        $this->repository->fetchById($id);
    }

    function testIfInsertingStudentWorks(): void
    {
        $student = new Student (
            firstName: 'Dave',
            lastName: 'Make',
            age: 31
        );
        $newStudent = $this->repository->insertNewStudent($student);

        $this->assertNotNull($newStudent->getId());
        $this->assertEquals($student->getFirstName(), $newStudent->getFirstName());
        $this->assertEquals($student->getLastName(), $newStudent->getLastName());
        $this->assertEquals($student->getAge(), $newStudent->getAge());
    }

    function testIfInsertingMultipleStudentWorks(): void
    {
        $dbh = $this->dbh;
        $repository = new StudentRepository($dbh);
        $student2 = new Student (
            firstName: 'Lame',
            lastName: 'Make',
            age: 44
        );
        $student1 = new Student (
            firstName: 'Dave',
            lastName: 'Make',
            age: 31
        );
        $newStudent1 = $repository->insertNewStudent($student1);
        $newStudent2 = $repository->insertNewStudent($student2);

        $this->assertNotNull($newStudent1->getId());
        $this->assertEquals($newStudent1->getFirstName(), $student1->getFirstName());
        $this->assertEquals($newStudent1->getLastName(), $student1->getLastName());
        $this->assertEquals($newStudent1->getAge(), $student1->getAge());
        $this->assertNotNull($newStudent2->getId());
        $this->assertEquals($newStudent2->getFirstName(), $student2->getFirstName());
        $this->assertEquals($newStudent2->getLastName(), $student2->getLastName());
        $this->assertEquals($newStudent2->getAge(), $student2->getAge());

    }

    function testIfInsertedIdsAreDifferent(): void
    {
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
        $this->repository->insertNewStudent($student);
        $result1 = $this->dbh->lastInsertId();
        $this->repository->insertNewStudent($student2);
        $result2 = $this->dbh->lastInsertId();

        $this->assertNotEquals($result1, $result2);
    }


    public function tearDown(): void
    {
        $this->dbh->exec('DROP TABLE IF EXISTS students');
        $this->dbh = null;
    }


}