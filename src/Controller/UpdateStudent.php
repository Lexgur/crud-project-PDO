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

            try {

                $student = $this->studentRepository->fetchById($studentId);

                $student->setFirstName($data['name']);
                $student->setLastName($data['lastname']);
                $student->setAge((int)$data['age']);

                $this->studentValidator->validate($student);

                $student = $this->studentRepository->save($student);

                echo "Student {$student->getFirstName()} has been updated! here is a link to update his profile again: <a class='upd-btn'  href='/index.php?action=update_student&id={$student->getId()}'>UPDATE</a>'
or to delete it : <a class='dlt-btn' href='/index.php?action=delete_student&id={$student->getId()}'>DELETE</a>";
            } catch (\Throwable $throwable) {
                return $this->template->render('update_student_form.php', [
                    'error' => $throwable->getMessage()
                ]);
            }
        }
        return $this->template->render('update_student_form.php');
    }
}
