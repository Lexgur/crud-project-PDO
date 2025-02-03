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
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $_POST;
            $studentId = (int)($_GET['id']);
            $student = $this->studentRepository->fetchById($studentId);

            $updatedStudent = new Student(
                $data['name'],
                $data['lastname'],
                (int)($data['age']),
                $student->getId()
            );

            if ($this->studentValidator->validate($updatedStudent)) {
                $this->studentRepository->update($updatedStudent);
                return $this->template->render('update_student_form.php', [
                    'success' => "{$updatedStudent->getFirstName()} updated successfully"
                ]);
            } else {
                return $this->template->render('update_student_form.php', [
                    'error' => "{$updatedStudent->getFirstName()}was not updated"
                ]);
            }
        }
        return $this->template->render('update_student_form.php');
    }
}