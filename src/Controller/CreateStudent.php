<?php

declare(strict_types=1);

namespace Crud\Controller;

use AllowDynamicProperties;
use Crud\Template;
use http\Params;
use PDO;

#[AllowDynamicProperties] class CreateStudent
{

    public function __construct(
        private PDO     $connection,
        private Template $template,

    )
    {

    }

    public function __invoke(): void
    {

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $this->template->render(__DIR__ . '/../../templates/create_student_form.php');

            return;
        }

        $result = $this->save();

        if ($result === true) {
            $this->template->render(__DIR__ . '/../../templates/create_student_form.php', [
                'success' => 'Student creation success!'
            ]);

            return;
        }

        $this->template->render(__DIR__ . '/../../templates/create_student_form.php', [
            'error' => 'Student creation failed!'
        ]);


    }

    public function validate(): bool
    {

            if (filter_var('student_first_name', FILTER_VALIDATE_BOOL) === false) {
                return false;
            }
            if (filter_var('student_last_name', FILTER_VALIDATE_BOOL) === false) {
                return false;
            }
            if (filter_var('student_age', FILTER_VALIDATE_INT) === false) {
                return false;
            }
        return true;
    }

    public function save(): bool
    {
        $statement = $this->connection->prepare('INSERT INTO `students` (`id_student`, `student_first_name`, `student_last_name`, `student_age`) VALUES (:id_student, :student_first_name, :student_last_name, :student_age)');
        $statement->bindValue(':id_student', $_POST['id_student']);
        $statement->bindValue(':student_first_name', $_POST['first_name']);
        $statement->bindValue(':student_last_name', $_POST['last_name']);
        $statement->bindValue(':student_age', $_POST['age']);

        $result = $statement->execute();

        return $result;

    }


}