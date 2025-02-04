<?php

declare(strict_types=1);

namespace Crud\Controller;

use Crud\Factory\StudentFactory;
use Crud\Model\Student;
use Crud\Repository\StudentRepository;
use Crud\Template;
use Crud\Validation\StudentValidator;

class UpdateStudent
{
    public function __construct(
        protected StudentValidator $studentValidator,
        private StudentRepository $studentRepository,
        private Template $template,
        private StudentFactory $studentFactory
    ) {}

    public function __invoke(): string
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['_method'] ?? '') === 'PATCH') {
            $data = $_POST;
            $studentId = (int)($_GET['id']);
            $student = $this->studentRepository->fetchById($studentId);


                $student->setFirstName($data['name']);
                $student->setLastName($data['lastname']);
                $student->setAge((int)$data['age']);

            if ($this->studentValidator->validate($student)) {
                $this->studentRepository->update($student);
                return $this->template->render('update_student_form.php', [
                    'success' => "{$student->getFirstName()} updated successfully"
                ]);
            } else {
                return $this->template->render('update_student_form.php', [
                    'error' => "{$student->getFirstName()}was not updated"
                ]);
            }
        }
        return $this->template->render('update_student_form.php');
    }
}