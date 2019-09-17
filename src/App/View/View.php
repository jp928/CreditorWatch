<?php declare(strict_types = 1);

namespace App\View;

use App\Base\Response;
use App\Entity\CreditorWatchCollection;
use App\Exceptions\TemplateNotFoundException;

class View
{

    public function __construct()
    {
        $this->loadTemplate();
    }

    public function render(CreditorWatchCollection $collection): Response
    {
        $content = '';

        $collection->rewind();

        while ($collection->valid()) {
            /** @var \App\Entity\CreditorWatch  $creditorWatch */
            $creditorWatch = $collection->current();
            $key = htmlspecialchars($creditorWatch->getKey());
            $value = htmlspecialchars($creditorWatch->getValue());

            $content .= '<li>' . $key . '&nbsp;<b>' . $value . '</b></li>';

            $collection->next();
        }

        $html = str_replace(
            ['%keyword%', '%content%'],
            compact('keyword', 'content'),
            $this->template,
        );

        $response = new Response();

        $response->setBody($html);

        return $response;
    }

    private function loadTemplate(): void
    {
        $template = file_get_contents(__DIR__ . '/../Template/result.tpl');

        // var_dump($template);
        if ($template === false) {
            throw new TemplateNotFoundException();
        }

        $this->template = $template;
    }
}
