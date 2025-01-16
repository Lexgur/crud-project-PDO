<?php

declare(strict_types=1);

namespace Crud\Controller;

use AllowDynamicProperties;
use Crud\Template;
use http\Params;
use PDO;

class CreateStudent
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

            if (empty($_POST['first_name']) || !is_string($_POST['first_name'])) {
                return false;
            }
            if (empty($_POST['last_name']) || !is_string($_POST['last_name'])) {
                return false;
            }

            $min = 1;
            $max = 99;

            if (empty($_POST['age']) || filter_var($_POST['age'], FILTER_VALIDATE_INT, array("options" => array("min_range"=>$min, "max_range"=>$max))) === false) {
                return false;
            }
        return true;
    }

    public function save(): bool
    {
        if(!$this->validate()){
            return false;
        }

        $statement = $this->connection->prepare('INSERT INTO `students` (`id_student`, `student_first_name`, `student_last_name`, `student_age`) VALUES (:id_student, :student_first_name, :student_last_name, :student_age)');
        $statement->bindValue(':id_student', $_POST['id_student']);
        $statement->bindValue(':student_first_name', $_POST['first_name']);
        $statement->bindValue(':student_last_name', $_POST['last_name']);
        $statement->bindValue(':student_age', $_POST['age']);

        $result = $statement->execute();

        return $result;

    }


}