<?php declare(strict_types = 1);

namespace App\Base;

class Router
{

    /** @var string[] $repository */
    private $repository = [];

    /**
     * Register controller
     *
     * @param Controller
     */
    public function register(mixed $controller): void
    {
        array_push($this->repository, $controller);
    }

    /**
     * Dispatch the request to corresponding controller
     *
     * @param string[] $request
     * @return \App\Base\Response
     */
    public function dispatch(array $request): Response
    {
        return $this->repository[0]($request);
    }
}
