<?php declare(strict_types = 1);

namespace App\Controller;

use App\Base\Request;
use App\Base\Response;
use App\Cache\Cache;
use App\Exceptions\BadRequestException;
use App\Service\GoogleSearchService;
use App\View\View;

class GoogleSearchController extends AbstractController
{

    /** @var \App\Service\GoogleSearchService $service */
    private $service;

    /** @var \App\Cache\Cache $cache */
    private $cache;

    public function __construct(GoogleSearchService $googleSearchService, Cache $cache)
    {
        $this->service = $googleSearchService;
        $this->cache = $cache;
    }

    public function getRouterPath(): string
    {
        return '/';
    }

    /**
     * Serve post request from frontend
     *
     * @param \App\Base\Request $request
     * @return \App\Base\Response;
     */
    public function post(Request $request): Response
    {
        $keyword = $request->getData('keyword');

        if (empty($keyword)) {
            throw new BadRequestException();
        }

        $keyword = rawurlencode($keyword);

        $collection = $this->cache->obtain($keyword);

        if (is_null($collection)) {
            $url = 'http://www.google.com/search?q=' . $keyword  . '&start=0&num=100';
            /** @var \App\Entity\CreditorWatchCollection $collection */
            $collection = $this->service->load($url)->parse();

            $this->cache->persist($keyword, $collection);
        }

        return (new View())->render($collection);
    }

    /**
     * Serve get request from frontend
     *
     * @return \App\Base\Response
     */
    public function get(): Response
    {
        return (new View())->render();
    }
}
