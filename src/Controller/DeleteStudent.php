<?php

namespace Crud\Controller;

use Crud\Template;

class DeleteStudent
{
    public function __construct(
        private PDO     $connection,
        private Template $template,

    )
    {

    }
}