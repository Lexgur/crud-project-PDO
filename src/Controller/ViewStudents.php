<?php

declare(strict_types=1);

namespace Crud\Controller;

class ViewStudents extends AbstractStudentController
{
    public function __invoke(): void
    {
        $students = $this->studentRepository->viewStudents();

        echo $this->render('view_students.php', ['students' => $students]);
    }
}
