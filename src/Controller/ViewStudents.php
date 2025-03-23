<?php

declare(strict_types=1);

namespace Crud\Controller;
use Crud\Attribute\Path;

#[Path('/students')]
class ViewStudents extends AbstractStudentController
{
    public function __invoke(): void
    {
        $students = $this->studentRepository->viewStudents();

        echo $this->render('view_students.php', ['students' => $students]);
    }
}
