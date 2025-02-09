<?php

declare(strict_types=1);

namespace Crud\Controller;

use Crud\Model\Student;
use Crud\Repository\StudentRepository;
use Crud\Template;
use Crud\Validation\StudentValidator;

abstract class AbstractStudentController
{
    protected StudentValidator $studentValidator;
    protected StudentRepository $studentRepository;
    protected Template $template;


    public function __construct(
        StudentValidator $studentValidator,
        StudentRepository $studentRepository,
        Template $template,
    ) {
        $this->studentValidator = $studentValidator;
        $this->studentRepository = $studentRepository;
        $this->template = $template;

    }

    protected function isPostRequest(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    protected function processStudent(Student $student, )
    {

    }
}
