<?php
// NOTICE: Not working because the of ob_get_contents() not working properly

/*
| Definition:
| is a creational design pattern, which solves the problem of creating entire product families without specifying their concrete classes.
|
| Author:
| RefactoringGuru
*/

interface TitleTemplate 
{
    public function getTemplateString(): string;
}
interface PageTemplate 
{
    public function getTemplateString(): string;
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


/*
|-----------------------------------------------------
| Create OWN_ Template
|-----------------------------------------------------
*/


# For this time we will be creating a BasePageTemplate that will implement PageTemplate
abstract class BasePageTemplate implements PageTemplate
{
    protected $titleTemplate;
    public function __construct(TitleTemplate $titleTemplate)
    {
        $this->titleTemplate = $titleTemplate;
    }
}
# Adheres OWN_ Template for each title, page, renderer templates
class OWN_TitleTemplate implements TitleTemplate
{
    public function getTemplateString(): string
    {
        return "<h1>{{ title }}</h1>";
    }
}
# Use BasePageTemplate to create OWN_ interface of PageTemplate
class OWN_PageTemplate extends BasePageTemplate
{
    public function getTemplateString(): string
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
# Use TemplateRenderer to create OWN_
class OWN_Renderer implements TemplateRenderer
{
    public function render(string $templateString, array $arguments = []): string {
        extract($arguments);
        ob_start();
        eval(' ?>' . $templateString . '<?php ');
        $result = ob_get_contents();
        ob_end_clean();
        return $result;
    }
}

# Define OWN_ factory
class OWN_TemplateFactory implements TemplateFactory
{
    public function createTitleTemplate(): TitleTemplate
    {
        return new OWN_TitleTemplate;
    }
    public function createPageTemplate(): PageTemplate
    {
        return new OWN_PageTemplate($this->createTitleTemplate());
    }
    public function getRenderer(): TemplateRenderer
    {
        return new OWN_Renderer;
    }
}

# Usage
class Page 
{
    public $title;
    public $content;
    public function __construct($title, $content)
    {
        $this->title = $title;
        $this->content = $content;
    }
    public function render(TemplateFactory $factory): string
    {
        $pageTemplate = $factory->createPageTemplate();
        $renderer = $factory->getRenderer();

        return $renderer
            ->render($pageTemplate->getTemplateString(), [
                    'title' => $this->title,
                    'content' => $this->content,
                ]);
    }
}

$page = new Page('Sample Page', 'This is the body.');
echo $page->render(new OWN_TemplateFactory);