<?php

declare(strict_types=1);

use Crud\Template;
use PHPUnit\Framework\TestCase;

class TemplateTest extends TestCase
{
    public function testIfTemplateFileExistsRenderHtml(): void
    {
        $templatePath = __DIR__ . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR;
        $template = new Template($templatePath);
        $result = $template->render('test_template.php');

        $this->assertEquals("Hello, world!", $result);
    }

    public function testIfTemplateDoesNotExistGetError(): void
    {
        $this->expectException(\Crud\Exception\TemplateNotFoundException::class);

        $templatePath = __DIR__ . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR;
        $template = new Template($templatePath);
        $template->render('file_nonexistent.php');
    }

    public function testIfPathIsNotSafe(): void
    {
        $this->expectException(\Crud\Exception\IllegalTemplatePathException::class);

        $templatePath = __DIR__ . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR;
        $template = new Template($templatePath);
        $template->render('/../illegaltemplates/illegal_file.php');
        $template->render('../illegaltemplates/illegal_file.php');
    }
}
