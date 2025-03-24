<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Crud\Attribute\Path;

class PathAttributeTest extends TestCase
{
    public function testGetPath(): void
    {
        $class = new ReflectionClass(SomethingCreateController::class);
        $attributes = $class->getAttributes(Path::class);
        $this->assertEquals('/something/create', $attributes[0]->newInstance()->getPath());
    }
}

#[Path('/something/create')]
class SomethingCreateController
{
}
