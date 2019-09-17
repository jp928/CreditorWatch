<?php declare(strict_types = 1);

namespace App\Parser;

use App\Entity\CreditorWatchCollection;

/**
 * Interface HtmlParserInterface
 *
 * @package App\Parser
 * @throws \App\Parser\App\Exceptions\HtmlParseException
 */
interface HtmlParserInterface
{

    /**
     * Parse result
     *
     * @return string[]
     */
    public function parse(string $html): CreditorWatchCollection;
}
