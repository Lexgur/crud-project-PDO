<?php

declare(strict_types=1);

namespace Crud\Controller;

use Crud\Factory\StudentFactory;
use Crud\Repository\StudentRepository;
use Crud\Template;
use Crud\Validation\StudentValidator;

class CreateStudent
{
    public function __construct(

        protected StudentValidator $studentValidator,
        private StudentRepository  $studentRepository,
        private Template           $template,
        private StudentFactory     $studentFactory
    )
    {

    }

    public function __invoke(): string
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $_POST;
            $student = $this->studentFactory->create($data);

            if ($this->studentValidator->validate($student)) {
                $student = $this->studentRepository->save($student);
                echo "Student {$student->getFirstName()} has been created! here is a link to update his profile: <a class='upd-btn' href='/index.php?action=update_student&id={$student->getId()}'>Update</a>'";
            } else {
                return $this->template->render('create_student_form.php', [
                    'error' => "Studento {$student->getFirstName()} sukurti nepavyko..."]);
            }
        }

        return $this->template->render('create_student_form.php', [
        ]);
    }
}