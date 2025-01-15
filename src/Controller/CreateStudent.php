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
         if (empty($_POST)) {
             $this->template->render(__DIR__ . '/../../templates/create_student_form.php', [
                 'string' => 'Hello wod!'
             ]);

             return;
         }

         $createStudentFormRequest = $_POST;

         $statement = $this->connection->prepare('INSERT INTO `students` (`id_student`, `student_first_name`, `student_last_name`, `student_age`) VALUES (:id_student, :student_first_name, :student_last_name, :student_age)');
         $statement->bindValue(':id_student', value: 21);
         $statement->bindValue(':student_first_name', value: 'Dave');
         $statement->bindValue(':student_last_name', value: 'Bigjhonson');
         $statement->bindValue(':student_age', value: 17);

         $result = $statement->execute();
         if ($result === true) {
             $this->template->render(__DIR__ . '/../../templates/create_student_success.php', [
                 'string' => 'Hello wo!'
             ]);

             return;
         }

         $this->template->render(__DIR__ . '/../../templates/create_student_error.php', [
             'string' => 'You failure!'
         ]);
    }

}