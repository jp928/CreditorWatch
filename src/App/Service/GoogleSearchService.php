<?php declare (strict_types = 1);

namespace App\Service;

use App\Entity\CreditorWatchCollection;
use App\Parser\HtmlParserInterface;
use App\Transport\DownloaderInterface;
use DateTime;
use Webmozart\Assert\Assert;
use function file_get_contents;
use function is_array;
use function preg_match;

class GoogleSearchService
{

    const CACHE_DIR = __DIR__ . '/../../../cache';
    
    /** @var \App\Transport\DownloaderInterface */
    private $downloader;

    /** @var \App\Parser\HtmlParserInterface */
    private $parser;

    /** @var string */
    private $content;

    public function __construct(DownloaderInterface $downloader, HtmlParserInterface $parser)
    {
        Assert::isInstanceOf($downloader, '\App\Transport\DownloaderInterface', 'Downloader is not right.');
        Assert::isInstanceOf($parser, '\App\Parser\HtmlParserInterface', 'Parser is not right.');

        $this->downloader = $downloader;
        $this->parser = $parser;
    }

    public function load(string $url): self
    {
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
        return $this->parser->parse($this->content);
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
        $cache = $this->getContentFromCache();

        if ($cache !== null) {
            return $cache;
        } else {
            $content = $this->downloader->download($url);
            $this->persistCache($content);
        }

        return $content;
    }

    /**
     * Get content from cache
     *
     * @codeCoverageIgnore
     */
    protected function getContentFromCache(): ?string
    {
        $cacheDir = GoogleSearchService::CACHE_DIR;

        $files = scandir($cacheDir);

        if (is_array($files)) {
            $cacheFile = array_filter($files, function ($file) {
                return preg_match("/result_.*[0-9].*.html/", $file) > 0;
            });

            if (count($cacheFile) === 0) {
                return null;
            }

            $cacheFileName = reset($cacheFile);
            $expiredAfter = (new DateTime())->setTimestamp((int) substr($cacheFileName, 7, -5));
            $now = new DateTime();

            if ($now <= $expiredAfter) {
                return file_get_contents($cacheDir . DIRECTORY_SEPARATOR . $cacheFileName);
            }
        }

        return null;
    }

    /**
     * Persist content into a local file
     *
     * @codeCoverageIgnore
     * @param string html
     */
    protected function persistCache(string $content): void
    {
        $expireOn = new DateTime();
        
        $expireOn->modify('+10 hours');

        $cacheFile = GoogleSearchService::CACHE_DIR . '/result_ ' . $expireOn->getTimestamp() . '.html';

        file_put_contents($cacheFile, $content);
    }
}