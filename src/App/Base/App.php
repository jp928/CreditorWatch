<?php declare(strict_types = 1);

namespace App\Base;

use App\Parser\Dom;
use App\Parser\Downloader;
use App\Parser\HtmlParser;
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
        $this->url = 'http://www.google.com/search?q=' . rawurlencode($keyword) . '&start=0&num=100&ie=utf-8&oe=utf-8';
    }

    public function process(): void
    {
        $downloader = new Downloader();

        $parser = new HtmlParser();

        $dom = new Dom($downloader, $parser);

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
}
