<?php

declare(strict_types=1);

namespace Crud\Controller;

use Crud\Factory\StudentFactory;

class CreateStudent extends AbstractStudentController
{

    public function __invoke(): string
    {

        if ($this->isPostRequest()) {
            $data = $_POST;
            $student = StudentFactory::create($data);

            return $this->processStudent(
                $student,
                "Student {$student->getFirstName()} has been created!",
                'create_student_form.php'
            );
        }

        return $this->render('create_student_form.php');
    }
}

