<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Crud\Template;

//TODO problema - mano render funkcija neturetu leisti renderinti failo, kuris nera nurodytojo directory templates

class TemplateTest extends TestCase
{
    function testIfTemplateFileExistsRenderHtml() : void
    {
        $templatePath = __DIR__ . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR;
        $template = new Template($templatePath);
        $result = $template->render('test_template.php');
        $this->assertEquals("Hello, world!", $result);
    }

    function testIfTemplateDoesNotExistGetError(): void
    {
        $this->expectException(\Crud\Exception\TemplateNotFoundException::class);
        $templatePath = __DIR__ . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR;
        $template = new Template($templatePath);
        $template->render('file_nonexistent.php');
    }

    function testIfPathIsNotSafe(): void
    {
        $this->expectException(\Crud\Exception\IllegalTemplatePathException::class);
        $templatePath = __DIR__ . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR;
        $template = new Template($templatePath);
        $template->render('/../illegaltemplates/illegal_file.php');
        $template->render('../illegaltemplates/illegal_file.php');
    }
}
