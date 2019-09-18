<?php declare(strict_types = 1);

namespace App\Base;

use App\Container\ContainerInterface;
use App\Controller\AbstractController;
use App\Exceptions\ControllerNotFoundException;

class Router
{

    /** @var string[] $repository */
    private $repository = [];

    /** @var \App\Container\ContainerInterface $container */
    private $container;

    const CONTROLLER_NS = '\App\Controller\\';

    public function bindContainer(ContainerInterface $container): self
    {
        $this->container = $container;

        return $this;
    }

    /**
     * Register controllers
     */
    public function registerControllers(): void
    {
        foreach (glob(__DIR__ . '/../Controller/*.php') as $filename) {
            include_once $filename;

            $controller = basename($filename, '.php');

            /** @var \App\Controller\AbstractController $controllerInstance */
            $controllerInstance = $this->container->resolve(Router::CONTROLLER_NS . $controller);

            if (!($controllerInstance instanceof AbstractController)) {
                continue;
            }

            $condition = $controllerInstance->getRouterPath();
            $this->repository[$condition] = $controllerInstance;
        }
    }

    /**
     * Dispatch the request to corresponding controller
     *
     * @param string[] $request
     * @return \App\Base\Response
     */
    public function dispatch(Request $request): Response
    {
        $path = $request->getUri();

        if (array_key_exists($path, $this->repository)) {
            return $this->repository[$path]($request);
        }

        throw new ControllerNotFoundException();
    }
}
