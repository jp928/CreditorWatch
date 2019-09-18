<?php declare(strict_types = 1);

namespace App\Controller;

use App\Base\Request;
use App\Base\Response;
use App\Exception\PageNotFoundException;
use function call_user_func;

abstract class AbstractController
{

    abstract public function getRouterPath(): string;

    /**
     * Handler for HTTP POST method
     * Throw exception if the controller doesn't override it.
     *
     * @throws \App\Exceptions\PageNotFoundException
     * @param \App\Base\Request $request
     * @return \App\Base\Response|null
     */
    public function post(Request $request): ?Response
    {
        throw new PageNotFoundException();
    }

    /**
     * Handler for HTTP GET method
     * Throw exception if the controller doesn't override it.
     *
     * @throws \App\Controller\App\Exceptions\PageNotFoundException
     * @return \App\Base\Response|null
     */
    public function get(): ?Response
    {
        throw new PageNotFoundException();
    }

    /**
     * Handler for HTTP PUT method
     * Throw exception if the controller doesn't override it.
     *
     * @throws \App\Controller\App\Exceptions\PageNotFoundException
     * @param \App\Base\Request $request
     * @return \App\Base\Response|null
     */
    public function put(Request $request): ?Response
    {
        throw new PageNotFoundException();
    }

    /**
     * Handler for HTTP DELETE method
     * Throw exception if the controller doesn't override it.
     *
     * @throws \App\Controller\App\Exceptions\PageNotFoundException
     * @param \App\Base\Request $request
     * @return \App\Base\Response|null
     */
    public function delete(Request $request): ?Response
    {
        throw new PageNotFoundException();
    }

    /**
     * Invoke the controller
     * This logic would be shared with multiple controlers
     *
     * @param \App\Base\Request $request
     * @return \App\Base\Response
     */
    public function __invoke(Request $request): Response
    {
        $httpMethod = $request->getHttpMethod();

        if (method_exists($this, $httpMethod)) {
            return call_user_func([$this, $httpMethod], $request);
        }
        
        // For PATCH, OPTIONS etc raise exception for now.
        throw new ControllerDoesNotSupportHttpRequestException($httpMethod);
    }
}
