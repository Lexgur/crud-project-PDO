<?php

declare(strict_types=1);

namespace Crud\Controller;

class DeleteStudent extends AbstractStudentController
{

    public function __invoke(): string
    {
        if ($this->isPostRequest()) {
            $studentId = (int)($_GET['id']);

            try {

                $this->studentRepository->fetchById($studentId);

                $this->studentRepository->delete($studentId);

                echo "Student {$studentId} has been deleted! here is a link to create a new student: <button class='add-btn'><a href='/index.php?action=create_student'>CREATE</a></button>'";

            } catch (\Throwable $throwable) {
                return $this->render('delete_student_form.php', [
                    'error' => $throwable->getMessage()
                ]);
            }
        }
        return $this->render('delete_student_form.php');
    }
}
