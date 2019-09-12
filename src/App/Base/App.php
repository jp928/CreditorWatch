<?php declare(strict_types = 1);

namespace App\Base;

use App\Parser\Curl;
use App\Parser\Dom;

class App
{

    public function process(): void
    {
        $curl = new Curl();

        $dom = new Dom($curl);

        $keywords = rawurlencode('creditor watch');

        $url = "http://www.google.com/search?q=${keywords}&start=0&num=100&ie=utf-8&oe=utf-8";

        $content = $dom->load($url);

        var_dump($content);
    }
}
