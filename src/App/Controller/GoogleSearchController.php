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

    public function __construct(GoogleSearchService $googleSearchService)
    {
        $this->service = $googleSearchService;
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

        /** @var \App\Entity\CreditorWatchCollection $collection */
        $collection = $this->service->load($keyword)->parse();

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
