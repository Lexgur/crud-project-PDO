<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class ReflectionClassTest extends TestCase
{
    public function testClassExists(): void
    {
        $reflection  = new ReflectionClass(SampleClass::class);
        $reflectionChild = new ReflectionClass(SampleClassTwo::class);

        $this->assertTrue(class_exists($reflection->getName()));
        $this->assertFalse(class_exists('NonExistentClass'));
        $this->assertTrue(class_exists($reflectionChild->getName()));
    }

    public function testClassHasProperty(): void
    {
        $this->assertTrue(property_exists(SampleClass::class, 'id'));
        $this->assertFalse(property_exists(SampleClass::class, 'name'));

        $this->assertTrue(property_exists(SampleClassTwo::class, 'id')); //paveldi is SampleClass id
    }

    public function testReflectionHasProperty(): void
    {
        $reflection = new ReflectionClass(SampleClass::class);
        $reflectionChild = new ReflectionClass(SampleClassTwo::class);

        $this->assertTrue($reflection->hasProperty('id'));
        $this->assertFalse($reflection->hasProperty('name'));

        $this->assertTrue($reflectionChild->hasProperty('id')); //the child inherits
    }

    public function testReflectionGetAttributes(): void
    {
        $class = new ReflectionClass('Pill'); //you can do routing with attributes
        $attributes = $class->getAttributes();

        $this->assertTrue(is_array($attributes));

        $this->assertCount(2, $attributes);
        print_r(array_map(fn ($attribute) => $attribute->getName(), $attributes));
    }

    public function testReflectionGetAttributeWithInstanceOf(): void
    {
        $class = new ReflectionClass('Pill'); //you can do routing with attributes
        $attributes = $class->getAttributes(Color::class, ReflectionAttribute::IS_INSTANCEOF);

        $this->assertTrue(is_array($attributes));
        $this->assertCount(1, $attributes);

        print_r(array_map(fn ($attribute) => $attribute->getName(), $attributes));
    }

    public function testIsPrivateProperty(): void
    {
        $reflection = new ReflectionClass(SampleClass::class);

        $this->assertTrue($reflection->getProperty('email')->isPrivate());
    }
}
class SampleClass
{
    public string $id;
    private string $email;
}

class SampleClassTwo extends SampleClass
{
}

interface Color
{
}

#[Attribute]
class Red
{
}

#[Attribute]
class Blue implements Color
{
}

#[Red]
#[Blue]
class Pill
{
}
