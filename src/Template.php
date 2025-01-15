<?php

namespace Crud;

class Template
{
    public function render(string $template, array $parameters): void
    {
        extract($parameters);
        ob_start();

        include $template;

        print ob_get_clean();
    }
}