<?php

namespace Crud;

#[AllowDynamicProperties]
class Template
{
    public function __construct(private readonly string $templatePath)
    {
        // Resolve the absolute path based on the config
    }

    public function render(string $template, array $parameters = []): void
    {
        // Full path to the template file
        $templatePath = $this->templatePath . DIRECTORY_SEPARATOR . $template;

        if (file_exists($templatePath)) {
            extract($parameters);
            ob_start();
            include $templatePath;
            print ob_get_clean();
        } else {
            print 'Template not found: ' . $templatePath;
        }
    }
}