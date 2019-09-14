<?php declare(strict_types = 1);

namespace App\Parser;

use DOMDocument;
use DOMXPath;
use function preg_match;

class HtmlParser implements HtmlParserInterface
{

    /**
     * A simple curl implementation to get the content of the url.
     *
     * @param string $url
     * @return string[]
     * @throws \App\Exceptions\HtmlParseException
     */
    public function parse(string $html): array
    {
        $result = [];
        $dom = new DOMDocument();
        @$dom->loadHTML($html);
      
        $xpath = new DOMXPath($dom);
        $nodes = $xpath->query('//a//h3');

        foreach ($nodes as $key => $node) {
            if (!preg_match("/creditor\s?watch/i", $node->nodeValue)) {
                continue;
            }

            $result[$key] = $node->nodeValue;
        }

        return $result;
    }
}
