<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Crud\Attribute\Path;

class PathAttributeTest extends TestCase
{
    public function testGetPath(): void
    {
        $class = new ReflectionClass(Route::class);
        $attributes = $class->getAttributes();

        $this->assertIsArray($attributes);

        $attributeNames = array_map(fn ($attribute) => $attribute->getName(), $attributes);
        $this->assertEquals([Path::class], $attributeNames);
        print_r($attributeNames);
    }
}

#[Path('/something/create')]
class CreateSomethingController
{

}

#[Path('/something/create')]
class Route
{

}
