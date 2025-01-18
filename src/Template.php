<?php

namespace Crud;

use AllowDynamicProperties;

#[AllowDynamicProperties]
class Template
{
    public function __construct()
    {
        $this->config = include __DIR__ . '/../config.php';
    }
    public function render(string $template, array $parameters = []): void
    {
        $paths = $this->config['paths'];

        $templatePath = $paths['templates'] . $template;

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