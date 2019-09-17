<?php declare(strict_types = 1);

namespace App\Parser;

use App\Exceptions\HtmlParseException;
use DOMDocument;
use DOMXPath;
use Exception;
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

        try {
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
        } catch (Exception $e) {
            throw new HtmlParseException();
        }

        return $result;
    }
}
