<?php

declare(strict_types=1);

namespace Crud\Controller;

use Crud\Factory\StudentFactory;
use Crud\Repository\StudentRepository;
use Crud\Template;
use Crud\Validation\StudentValidator;

class ViewStudents
{
    public function __construct(
        protected StudentValidator $studentValidator,
        private StudentRepository $studentRepository,
        private Template $template,
        private StudentFactory $studentFactory
    ) {

    }
    public function __invoke(): void
    {
        $students = $this->studentRepository->viewStudents();

        echo $this->template->render('view_students.php', ['students' => $students]);
    }
}
