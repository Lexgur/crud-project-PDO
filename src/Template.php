<?php

namespace Crud;

use AllowDynamicProperties;

#[AllowDynamicProperties]
class Template
{
    public function __construct(

        private readonly string $templatePath)
    {

    }
    public function render(string $template, array $parameters = []): void
    {

        $templatePath = $this->templatePath . DIRECTORY_SEPARATOR . $template;

        if (file_exists($templatePath)) {
        extract($parameters);
        ob_start();
        include $templatePath;
        print ob_get_clean();
        } else {
            print 'Template not found ' . $templatePath;
        }
    }
}