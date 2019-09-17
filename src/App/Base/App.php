<?php declare(strict_types = 1);

namespace App\Base;

use App\Parser\Dom;
use App\Parser\HtmlParser;
use App\Transport\Downloader;
use Webmozart\Assert\Assert;
use function file_get_contents;

class App
{

    /** @var string $keyword */
    private $keyword;

    /** @var string $url */
    private $url;
    
    public function __construct(string $keyword)
    {
        Assert::stringNotEmpty($keyword, 'Please set keyword in setting.php.');

        $this->keyword = $keyword;

        $this->setUrl($keyword);
    }

    public function process(): void
    {
        $dom = $this->domFactory();

        $dom->load($this->url);

        echo $this->render($dom->parse());
    }

    /**
     * Render the result into HTML
     *
     * @param string[] $result
     * @return string HTMl
     */
    protected function render(array $result): string
    {
        $template = file_get_contents(__DIR__ . '/../View/result.tpl');

        $keyword = $this->keyword;

        array_walk($result, function ($value, $key) use (&$content): void {
            $content .= '<li>' . $key . '&nbsp;<b>' . $value . '</b></li>';
        });

        $template = str_replace(['%keyword%', '%content%'], compact('keyword', 'content'), $template);

        return $template;
    }

    /**
     * Generate Dom Object
     *
     * @return /App/Parser/Dom
     */
    protected function domFactory(): Dom
    {
        $downloader = new Downloader();

        $parser = new HtmlParser();

        return new Dom($downloader, $parser);
    }

    /**
     * Setter of $keyword
     *
     * @param string $keyword
     */
    private function setUrl(string $keyword): void
    {
        $this->url = 'http://www.google.com/search?q=' . rawurlencode($keyword) . '&start=0&num=100';
    }
}
