<?php

declare(strict_types=1);

namespace Crud\Controller;

use Crud\Factory\StudentFactory;
use Crud\Repository\StudentRepository;
use Crud\Template;
use Crud\Validation\StudentValidator;
//todo pirma padaryk CreateStudent

//todo tada UpdateStudent

//todo tada  DeleteStudent

//todo tada ViewStudent

//todo tada ViewStudents
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
            $validates = $this->studentValidator->validate($student);

            if ($validates) {

                $this->studentRepository->save($student);

                return $this->template->render('create_student_form.php', [
                    'success' => "Sveikinu sukurus {$student->getFirstName()}!"
                ]);
            } else {
                return $this->template->render('create_student_form.php', [
                    'error' => "Studento {$student->getFirstName()} sukurti nepavyko..."
                ]);
            }
        } else {
            return $this->template->render('create_student_form.php');
        }
    }
}