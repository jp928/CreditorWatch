<?php declare (strict_types = 1);

namespace App\Service;

use App\Cache\Cache;
use App\Entity\CreditorWatchCollection;
use App\Parser\HtmlParserInterface;
use App\Transport\DownloaderInterface;
use Webmozart\Assert\Assert;

class GoogleSearchService
{

    /** @var \App\Transport\DownloaderInterface $downloader */
    private $downloader;

    /** @var \App\Parser\HtmlParserInterface $parser */
    private $parser;

    /** @var string $content */
    private $content;

    /** @var string $keyword */
    private $keyword;

    /** @var \App\Cache\Cache $cache */
    private $cache;

    public function __construct(DownloaderInterface $downloader, HtmlParserInterface $parser, Cache $cache)
    {
        Assert::isInstanceOf($downloader, '\App\Transport\DownloaderInterface', 'Downloader is not right.');
        Assert::isInstanceOf($parser, '\App\Parser\HtmlParserInterface', 'Parser is not right.');
        Assert::isInstanceOf($cache, '\App\Cache\Cache', 'Cache is not right.');

        $this->downloader = $downloader;
        $this->parser = $parser;
        $this->cache = $cache;
    }

    public function load(string $keyword): self
    {
        $this->setKeyword($keyword);
        $url = $this->getUrl();
        $content = $this->getContent($url);
        $this->content = $this->sanitizer($content);

        return $this;
    }

    /**
     * Parse GoogleSearchService
     *
     * @param string $htmlString
     * @return string[]
     */
    public function parse(): CreditorWatchCollection
    {
        $collection = $this->cache->obtain($this->keyword);

        if (is_null($collection)) {
            $collection = $this->parser->parse($this->content);
            $this->cache->persist($this->keyword, $collection);
        }

        return $collection;
    }

    /**
     * Sanitize the GoogleSearchService by removing useless elements
     *
     * @param string $html
     */
    protected function sanitizer(string $html): string
    {
        // remove doctype
        $html = mb_eregi_replace("<!doctype(.*?)>", '', $html);

        // remove <head> tags
        $html = mb_eregi_replace("<\s*head\s*>(.*?)<\s*/\s*head\s*>", '', $html);

        /** remove <style> tags **/
        // match <style style=""></script>
        $html = mb_eregi_replace("<\s*style[^>]*[^/]>(.*?)<\s*/\s*style\s*>", '', $html);

        // match <style></style>
        $html = mb_eregi_replace("<\s*style\s*>(.*?)<\s*/\s*style\s*>", '', $html);

        /** remove <script> tags **/
        // match <script src=""></script>
        $html = mb_eregi_replace("<\s*script[^>]*[^/]>(.*?)<\s*/\s*script\s*>", '', $html);

        // match <script></script>
        $html = mb_eregi_replace("<\s*script\s*>(.*?)<\s*/\s*script\s*>", '', $html);

        // remove comments
        $html = mb_eregi_replace("<!--(.*?)-->", '', $html);

        // remove out cdata
        $html = mb_eregi_replace("<!\[CDATA\[(.*?)\]\]>", '', $html);

        // remove white space before closing tags
        $html = mb_eregi_replace("'\s+>", "'>", $html);
        $html = mb_eregi_replace('"\s+>', '">', $html);

        // remove line breaks
        $html = str_replace(["\r\n", "\r", "\n"], ' ', $html);

        return $html;
    }

    /**
     * Get the content from cache if cache doesn't exist fetch from internet
     *
     * @param string $url
     * @return string Html
     */
    protected function getContent(string $url): string
    {
        return $this->downloader->download($url);
    }

    /**
     * Setter for keyword
     */
    private function setKeyword(string $keyword): void
    {
        $this->keyword = rawurlencode($keyword);
    }

    /**
     * Generate google search url
     *
     * @return string
     */
    private function getUrl(): string
    {
        return sprintf('http://www.google.com/search?q=%s&start=0&num=100', $this->keyword);
    }
}
