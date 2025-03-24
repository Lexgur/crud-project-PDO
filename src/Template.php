<?php

namespace Crud;

use Crud\Exception\IllegalTemplatePathException;
use Crud\Exception\TemplateNotFoundException;

class Template
{
    public function __construct(private readonly string $templatePath)
    {
    }

    public function render(string $template, array $parameters = []): string
    {
        if (str_starts_with($template, '.') || str_starts_with($template, '/')) {
            throw new IllegalTemplatePathException('No hola for you');
        }

        $templatePath = $this->templatePath . DIRECTORY_SEPARATOR . $template;

        if (file_exists($templatePath)) {
            extract($parameters);
            ob_start();
            include $templatePath;
            return ob_get_clean();
        } else {
            throw new TemplateNotFoundException('template not found: ' . $templatePath);
        }
    }
}
