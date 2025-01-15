<?php

declare(strict_types=1);

namespace Crud\Controller;

use Crud\Template;

class CreateStudent
{

    public function __construct(
        private \PDO $connection,
        private Template $template,
    )
    {

    }

    public function __invoke():void
    {
         if ($_SERVER['REQUEST_METHOD'] === 'POST') {
             $this->template->render(__DIR__ . '/../../templates/create_student_form.php', [
                 'string' => 'Hello wod!'
             ]);

             return;
         }

         $createStudentFormRequest = $_POST;

         $statement = $this->connection->prepare('INSERT INTO `students` (`id_student`, `student_first_name`, `student_last_name`, `student_age`) VALUES (:id_student, :student_first_name, :student_last_name, :student_age)');
         $statement->bindValue(':id_student', $_POST['id_student']);
         $statement->bindValue(':student_first_name', $_POST['first_name']);
         $statement->bindValue(':student_last_name', $_POST['last_name']);
         $statement->bindValue(':student_age', $_POST['age']);

         $result = $statement->execute();
         if ($result === true) {
             $this->template->render(__DIR__ . '/../../templates/create_student_success.php', [
                 'string' => 'Student creation success!'
             ]);

             return;
         }

         $this->template->render(__DIR__ . '/../../templates/create_student_error.php', [
             'string' => 'Student creation failed!'
         ]);
    }

}