<?php

declare(strict_types=1);

use Crud\Model\Student;
use Crud\Repository\StudentRepository;
use PHPUnit\Framework\TestCase;

class StudentRepositoryTest extends TestCase

{
    public function setUp(): void
    {
        $this->testDbPath = __DIR__ . '/crud-test.sqlite';
        $this->dbh = new PDO('sqlite:' . $this->testDbPath);
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

    function testIfSaveInsertsNewStudent(): void
    {
        $student = new Student (
            firstName: 'John',
            lastName: 'McGee',
            age: 43
        );
        $savedStudent = $this->repository->save($student);

        $this->assertNotNull($savedStudent->getId());
        $this->assertEquals('John', $savedStudent->getFirstName());
        $this->assertEquals('McGee', $savedStudent->getLastName());
        $this->assertEquals(43, $savedStudent->getAge());
    }

    function testIfSaveUpdatesTheStudent(): void
    {
        $student = new Student(
            firstName: 'Dave',
            lastName: 'Lame',
            age: 44,
        );
        $insertedStudent = $this->repository->save($student);

        $insertedStudent->setFirstName('Mike');
        $insertedStudent->setLastName('McGee');
        $insertedStudent->setAge(44);

        $updatedStudent = $this->repository->save($insertedStudent);

        $this->assertNotNull($updatedStudent->getId());
        $this->assertEquals('Mike', $updatedStudent->getFirstName());
        $this->assertEquals('McGee', $updatedStudent->getLastName());
        $this->assertEquals(44, $updatedStudent->getAge());
    }

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
        $newStudent = $this->repository->insert($student);

        $this->assertNotNull($newStudent->getId());
        $this->assertEquals($student->getFirstName(), $newStudent->getFirstName());
        $this->assertEquals($student->getLastName(), $newStudent->getLastName());
        $this->assertEquals($student->getAge(), $newStudent->getAge());
    }

    function testIfInsertingMultipleStudentWorks(): void
    {
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
        $newStudent1 = $this->repository->insert($student1);
        $newStudent2 = $this->repository->insert($student2);

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
        $student1 = new Student (
            firstName: 'Greave',
            lastName: 'Leave',
            age: 27
        );
        $result1 = $this->repository->insert($student1);
        $student2 = new Student (
            firstName: 'Steve',
            lastName: 'Creve',
            age: 26
        );
        $result2 = $this->repository->insert($student2);

        $this->assertNotEquals($result1->getId(), $result2->getId());
        $this->assertNotEquals($result1->getFirstName(), $result2->getFirstName());
        $this->assertNotEquals($result1->getLastName(), $result2->getLastName());
    }

    function testIfStudentUpdateWorks(): void
    {
        $student = new Student (
            firstName: 'Mike',
            lastName: 'Hawk',
            age: 18
        );
        $insertedStudent = $this->repository->insert($student);

        $insertedStudent->setFirstName('Dave');
        $insertedStudent->setLastName('Make');
        $insertedStudent->setAge(28);

        $updatedStudent = $this->repository->update($insertedStudent);

        $this->assertEquals($insertedStudent->getId(), $updatedStudent->getId());
        $this->assertEquals('Dave', $updatedStudent->getFirstName());
        $this->assertEquals('Make', $updatedStudent->getLastName());
        $this->assertEquals(28, $updatedStudent->getAge());
    }

    function testIfDeleteWorks(): void
    {
        $student = new Student(
            firstName: 'Micheal',
            lastName: 'Hawktuah',
            age: 19
        );
        $insertedStudent = $this->repository->insert($student);
        $this->repository->delete($insertedStudent->getId());
        $studentAfterDelete = $this->repository->fetchById($insertedStudent->getId());

        $this->assertNull($studentAfterDelete);
    }

    function testIfViewAllStudentsWork(): void
    {
        $student = new Student(
            firstName: 'Micheal',
            lastName: 'Hawktuah',
            age: 27,
        );
        $savedStudent1 = $this->repository->save($student);
        $student2 = new Student(
            firstName: 'Cave',
            lastName: 'Mave',
            age: 19,
        );
        $savedStudent2 = $this->repository->save($student2);
        $result = $this->repository->viewStudents();

        $this->assertCount(2, $result);

        $this->assertEquals($savedStudent1->getFirstName(), $result[0]->getFirstName());
        $this->assertEquals($savedStudent1->getLastName(), $result[0]->getLastName());
        $this->assertEquals($savedStudent1->getAge(), $result[0]->getAge());

        $this->assertEquals($savedStudent2->getFirstName(), $result[1]->getFirstName());
        $this->assertEquals($savedStudent2->getLastName(), $result[1]->getLastName());
        $this->assertEquals($savedStudent2->getAge(), $result[1]->getAge());
    }

    public function tearDown(): void
    {
        $this->dbh->exec('DROP TABLE IF EXISTS students');
    }
}