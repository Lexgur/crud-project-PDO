<?php

declare(strict_types=1);

namespace Crud\Controller;

use Crud\Factory\StudentFactory;
use Crud\Repository\StudentRepository;
use Crud\Template;
use Crud\Validation\StudentValidator;

class DeleteStudent
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
            $studentId = (int)($_GET['id']);
            $student = $this->studentRepository->fetchById($studentId);

            if ($student) {
                $this->studentRepository->delete($studentId);
                echo "Student {$studentId} has been deleted! here is a link to create a new student: <a class='add-btn' href='/index.php?action=create_student'>CREATE</a>'";

            } else {
                return $this->template->render('delete_student_form.php', [
                    'error' => "Student not found, deletion failed"
                ]);
            }
        }
        return $this->template->render('delete_student_form.php');
    }
}
