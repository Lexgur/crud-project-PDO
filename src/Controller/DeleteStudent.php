<?php

declare(strict_types=1);

namespace Crud\Controller;

use Crud\Attribute\Path;

#[Path('/student/:id/delete')]
class DeleteStudent extends AbstractStudentController
{
    public function __invoke(): string
    {
        if ($this->isPostRequest()) {
            $studentId = (int)($_GET['id']);

            try {

                $this->studentRepository->fetchById($studentId);

                $this->studentRepository->delete($studentId);

                echo "Student {$studentId} has been deleted! here is a link to create a new student: <button class='add-btn'><a href='/student/create'>CREATE</a></button>'";

            } catch (\Throwable $throwable) {
                return $this->render('delete_student_form.php', [
                    'error' => $throwable->getMessage()
                ]);
            }
        }
        return $this->render('delete_student_form.php');
    }
}
