<?php declare(strict_types = 1);

namespace App\Base;

use App\Container\Container;
use App\Controller\GoogleSearchController;

class App
{

    /** @var string[] $definition */
    private $definition = [];
    
    /**
     * @param string[] $definition Dedependcies definition
     */
    public function __construct(array $definition)
    {
        $this->definition = $definition;

        $this->router = new Router();

        $googleSearchController = Container::getInstance()->setDefinition($definition)->resolve(GoogleSearchController::class);

        $this->registerRoute($googleSearchController);
    }
    
    protected function configureContainer(ContainerInterface $container): void
    {
        $container->wire($this->definition);
    }

    public function run(): void
    {
        try {
            $request = $_POST;
            // var_dump($_POST);
            // $request = $this->request->parse();
            $response = $this->process($request, $response);
        } catch (InvalidMethodException $e) {
            // $response = $this->processInvalidMethod($e->getRequest(), $response);
        } finally {
        }

        $this->render($response);
    }

    protected function render(ResponseInterface $response): void
    {
        echo $response;
    }
}
