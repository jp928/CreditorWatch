<?php declare(strict_types = 1);

namespace App\View;

use App\Base\Response;
use App\Exceptions\TemplateNotFoundException;
use Iterator;
use function implode;

class View
{

    /** @var string[] $indices */
    private $indices = [];

    public function __construct()
    {
        $this->loadTemplate();
    }

    public function render(?Iterator $collection = null): Response
    {
        $content = '';
        $keyword = '';

        if (!is_null($collection)) {
            $content = $this->generateContent($collection);
        }

        if (count($this->indices) > 0) {
            $keyword = implode(', ', $this->indices);
        }

        $html = str_replace(
            ['%keyword%', '%content%'],
            compact('keyword', 'content'),
            $this->template
        );
        
        $response = new Response();

        $response->setBody($html);

        return $response;
    }

    private function generateContent(Iterator $collection): string
    {
        $content = '';
        
        $collection->rewind();

        while ($collection->valid()) {
            /** @var \App\Entity\CreditorWatch  $creditorWatch */
            $creditorWatch = $collection->current();
            $key = htmlspecialchars($creditorWatch->getKey());
            $value = htmlspecialchars($creditorWatch->getValue());

            $this->indices[] = $key;
            $content .= '<li class="list-group-item d-flex justify-content-between align-items-center">'
                . $value . '<span class="badge badge-primary badge-pill">' . $key . '</span></li>';

            $collection->next();
        }

        return $content;
    }

    private function loadTemplate(): void
    {
        $template = file_get_contents(__DIR__ . '/../Template/result.tpl');

        if ($template === false) {
            throw new TemplateNotFoundException();
        }

        $this->template = $template;
    }
}
