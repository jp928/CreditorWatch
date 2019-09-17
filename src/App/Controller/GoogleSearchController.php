<?php declare(strict_types = 1);

namespace App\Controller;

use App\Base\Response;
use App\Service\GoogleSearchService;
use App\View\View;

class GoogleSearchController
{

    /** @var \App\Service\GoogleSearchService $service */
    private $service;

    public function __construct(GoogleSearchService $googleSearchService)
    {
        $this->service = $googleSearchService;
    }

    /**
     * Invoke the controller
     *
     * @param \App\Controller\mix $request
     * @return \App\Base\Response
     */
    public function __invoke(mix $request): Response
    {
        $url = 'http://www.google.com/search?q=' . rawurlencode($request['keyword']) . '&start=0&num=100';
        /** @var \App\Entity\CreditorWatchCollection $collection */
        $collection = $this->service->load($url)->parse();

        return (new View())->render($collection);
    }
}
