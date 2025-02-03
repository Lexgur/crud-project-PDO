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
                $this->studentRepository->save($student);
                return $this->template->render('create_student_form.php', [
                    'success' => "Sveikinu sukurus {$data['name']}!"]);
            } else {
                return $this->template->render('create_student_form.php', [
                    'error' => "Studento {$data['name']} sukurti nepavyko..."]);
            }
        }

        return $this->template->render('create_student_form.php', [
        ]);
    }
}