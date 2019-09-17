<?php declare(strict_types = 1);

namespace App\Base;

use App\Container\ContainerInterface;

class App
{

    /** @var \App\Base\Router $router */
    private $router;

    /** @var \App\Base\Request */
    private $request;
    
    public function __construct(ContainerInterface $container)
    {
        $this->router = $container->resolve(Router::class);
        $this->request = $container->resolve(Request::class)->parseRequest();
        $this->router->bindContainer($container)->registerControllers();
    }


    public function run(): void
    {
        $response = $this->process();
        $this->render($response);
    }

    protected function render(ResponseInterface $response): void
    {
        echo $response;
    }

    private function process(): Response
    {
        return $this->router->dispatch($this->request);
    }
}
