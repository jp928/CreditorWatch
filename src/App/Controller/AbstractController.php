<?php declare(strict_types = 1);

namespace App\Controller;

use App\Base\Request;
use App\Base\Response;
use function call_user_func;

abstract class AbstractController
{

    abstract public function getRouterPath(): string;

    public function post(Request $request): ?Response
    {
        return null;
    }

    public function get(): ?Response
    {
        return null;
    }

    public function put(Request $request): ?Response
    {
        return null;
    }

    public function delete(Request $request): ?Response
    {
        return null;
    }

    /**
     * Invoke the controller
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
        
        throw new ControllerDoesNotSupportHttpRequestException($httpMethod);
    }
}
