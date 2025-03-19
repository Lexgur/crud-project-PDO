<?php

declare(strict_types=1);

namespace Crud\Controller;

use Crud\Exception\IllegalTemplatePathException;
use Crud\Exception\TemplateNotFoundException;
use Crud\Repository\UserModelRepository;
use Crud\Template;
use Crud\Validation\UserValidator;

abstract class AbstractUserController
{
    protected UserValidator $userValidator;
    protected UserModelRepository $userRepository;
    protected Template $template;

    public function __construct(
        UserValidator       $userValidator,
        UserModelRepository $userRepository,
        Template            $template,
    ) {
        $this->userValidator = $userValidator;
        $this->userRepository = $userRepository;
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
}
