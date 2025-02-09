<?php

declare(strict_types=1);

namespace Crud\Controller;

class UpdateStudent extends AbstractStudentController
{
    public function __invoke(): string
    {
        if ($this->isPostRequest()) {
            $data = $_POST;
            $studentId = (int)($_GET['id']);
            $student = $this->studentRepository->fetchById($studentId);
            $student->setFirstName($data['name']);
            $student->setLastName($data['lastname']);
            $student->setAge((int)$data['age']);

            return $this->processStudent(
                $student,
                "Student {$student->getFirstName()} has been updated!",
                'update_student_form.php'
            );
        }

        return $this->render('update_student_form.php');
    }
}
