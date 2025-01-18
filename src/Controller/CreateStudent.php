<?php

declare(strict_types=1);

namespace Crud\Controller;

use Crud\Validation\StudentValidator;
use Crud\Repository\StudentRepository;
use Crud\Template;

class CreateStudent
{
    public function __construct(

        protected StudentValidator $studentValidator,
        private StudentRepository  $studentRepository,
        private Template           $template
    )
    {

    }
    public function __invoke(): void
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $data = $_POST;
            $validates = $this->studentValidator->validate($data);
            $saves = $this->studentRepository->save($data);

            if ($validates && $saves) {
                $this->template->render(__DIR__ . '/../../templates/create_student_form.php', [
                    'success' => 'Student creation success!'
                ]);
            } else {
                $this->template->render(__DIR__ . '/../../templates/create_student_form.php', [
                    'error' => 'Student creation failed!'
                ]);
            }
        } else {
            $this->template->render(__DIR__ . '/../../templates/create_student_form.php');
        }
    }
}