<?php

declare(strict_types=1);

namespace Crud\Controller;

use Crud\Exception\IllegalTemplatePathException;
use Crud\Exception\InvalidRequestMethodException;
use Crud\Exception\TemplateNotFoundException;
use Crud\Repository\StudentModelRepository;
use Crud\Template;
use Crud\Validation\StudentValidator;

abstract class AbstractStudentController
{
    protected StudentValidator $studentValidator;
    protected StudentModelRepository $studentRepository;
    protected Template $template;

    public function __construct(
        StudentValidator $studentValidator,
        StudentModelRepository $studentRepository,
        Template $template,
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
     * @throws TemplateNotFoundException
     * @throws IllegalTemplatePathException
     */
    protected function render(string $template, array $data = []): string
    {
        return $this->template->render($template, $data);
    }

    /**
     * @throws InvalidRequestMethodException
     */
}
