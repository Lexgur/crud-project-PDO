<?php

declare(strict_types=1);

namespace Crud\Controller;

use Crud\Exception\AgeIsEmptyOrExceedsTheRangeException;
use Crud\Exception\IllegalTemplatePathException;
use Crud\Exception\NameOrLastnameContainsIncorrectCharactersException;
use Crud\Exception\TemplateNotFoundException;
use Crud\Model\Student;
use Crud\Repository\StudentRepository;
use Crud\Template;
use Crud\Validation\StudentValidator;

abstract class AbstractStudentController
{
    protected StudentValidator $studentValidator;
    protected StudentRepository $studentRepository;
    protected Template $template;

    public function __construct(
        StudentValidator  $studentValidator,
        StudentRepository $studentRepository,
        Template          $template,
    ) {
        $this->studentValidator = $studentValidator;
        $this->studentRepository = $studentRepository;
        $this->template = $template;

    }

    protected function isPostRequest(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    /**
     * @throws NameOrLastnameContainsIncorrectCharactersException
     * @throws AgeIsEmptyOrExceedsTheRangeException
     */
    protected function processStudent(Student $student, string $successMessage, string $errorTemplate): string
    {
        if ($this->studentValidator->validate($student)) {
            $student = $this->studentRepository->save($student);
            echo "{$successMessage} <link rel='stylesheet' href='style.css'> <button class='upd-btn'><a href='/index.php?action=update_student&id={$student->getId()}'>Update again</a></button>";
            return '';
        } else {
            return $this->render($errorTemplate, ['error' => "{$student->getFirstName()} was not processed."]);
        }
    }

    /**
     * @throws TemplateNotFoundException
     * @throws IllegalTemplatePathException
     */
    protected function render(string $template, array $data = []): string
    {
        return $this->template->render($template, $data);
    }
}
