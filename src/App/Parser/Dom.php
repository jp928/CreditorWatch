<?php declare (strict_types = 1);

namespace App\Parser;

use Webmozart\Assert\Assert;

class Dom
{

    /** @var \App\Parser\DownloaderInterface */
    private $downloader;

    public function __construct(DownloaderInterface $downloader)
    {
        Assert::isInstanceOf($downloader, '\App\Parser\DownloaderInterface');
        $this->downloader = $downloader;
    }

    public function load(string $url): void
    {
    
        $content = $this->downloader->download($url);

        $dom = $this->sanitizer($content);

        var_dump($dom);
    }

  /**
   * Sanitize the dom by removing useless elements
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
        // match <style tyle=""></script>
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
}
