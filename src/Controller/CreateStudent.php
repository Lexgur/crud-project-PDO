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

            if ($this->studentValidator->validate($student)) {
                $student = $this->studentRepository->save($student);
                echo "Student {$student->getFirstName()} has been created! here is a link to update his profile: <a class='upd-btn' href='/index.php?action=update_student&id={$student->getId()}'>Update</a>'";
            } else {
                return $this->render('create_student_form.php', [
                    'error' => "Studento {$student->getFirstName()} sukurti nepavyko..."]);
            }
        }

        return $this->render('create_student_form.php', [
        ]);
    }
}

