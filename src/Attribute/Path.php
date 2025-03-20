<?php

declare(strict_types=1);

namespace Crud\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class Path
{
    public function __construct(
        private readonly string $path
    ) {

    }
    public function getPath(): string
    {
        return $this->path;
    }
}
