<?php declare(strict_types = 1);

use App\Cache\CacheEngineInterface;
use App\Cache\RedisCacheEngine;
use App\Parser\HtmlParser;
use App\Parser\HtmlParserInterface;
use App\Transport\Downloader;
use App\Transport\DownloaderInterface;

return [
  DownloaderInterface::class => Downloader::class,
  HtmlParserInterface::class => HtmlParser::class,
  CacheEngineInterface::class => RedisCacheEngine::class,
];
