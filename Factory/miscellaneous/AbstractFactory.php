<?php
/*
| Definition:
| is a creational design pattern, which solves the problem of creating entire product families without specifying their concrete classes.
|
| Author:
| RefactoringGuru
*/

interface TitleTemplate 
{
    public function getTemlateString(): string;
}
interface PageTemplate 
{
    public function getTemlateString(): string;
}
interface TemplateRenderer
{
    public function render(string $templateString, array $arguments = []): string;
}
# Create central Factory
interface TemplateFactory
{
    public function createTitleTemplate(): TitleTemplate;
    public function createPageTemplate(): PageTemplate;
    public function getRenderer(): TemplateRenderer;
}
# Adheres Twig Template for each title, page, renderer templates
class TwigTitleTemplate implements TitleTemplate
{
    public function getTemlateString(): string
    {
        return "<h1>{{ title }}</h1>";
    }
}
# For this time we will be creating a BasePageTemplate that will implement PageTemplate
abstract class BasePageTemplate implements PageTemplate
{
    protected $titleTemplate;
    public function __construct(TitleTemplate $titleTemplate)
    {
        $this->titleTemplate = $titleTemplate;
    }
}
# Use BasePageTemplate to create Twig
class TwigPageTemplate extends BasePageTemplate
{
    public function getTemlateString(): string
    {
        $renderedTitle = $this->titleTemplate->getTemplateString();
return<<<HTML
<div class="page">
    $renderedTitle
<article class="content">{{ content }}</article>
</div>
HTML;
    }
}

# Define a factory that will create a TwigTemplate
class TwigTemplateFactory implements TemplateFactory
{
    public function createTitleTemplate(): TitleTemplate
    {
        return new TwigTitleTemplate();
    }
    public function createPageTemplate(): PageTemplate
    {
        return 0;
    }
    public function getRenderer(): TemplateRenderer
    {
        return 0;
    }
}
