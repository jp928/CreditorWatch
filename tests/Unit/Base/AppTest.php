<?php declare(strict_types = 1);

namespace Tests\Unit\Base;

use App\Base\App;
use App\Base\Request;
use App\Base\Router;
use App\Container\ContainerInterface;
use PHPUnit\Framework\TestCase;

class AppTest extends TestCase
{
    /** @var \App\Container\ContainerInterface $mockedContainer */
    private $mockedContainer;

    protected function tearDown(): void
    {
        unset($this->mockedContainer);
        unset($this->app);
        parent::tearDown();
    }

    public function testProcess(): void
    {
        $mockedRouter = $this->getMockBuilder(Router::class)
          ->setMethods(['bindContainer', 'dispatch', 'registerControllers'])
          ->getMock();


        $mockedRequest = $this->getMockBuilder(Request::class)
          ->setMethods(['parseRequest'])
          ->getMock();

        $this->mockedContainer = $this->createMock(ContainerInterface::class);

        $this->mockedContainer->expects($this->at(0))
          ->method('resolve')
          ->with(Router::class)
          ->willReturn($mockedRouter);

        $this->mockedContainer->expects($this->at(1))
          ->method('resolve')
          ->with(Request::class)
          ->willReturn($mockedRequest);
        
                
        $mockedRouter->expects($this->once())
          ->method('bindContainer')
          ->with($this->mockedContainer)
          ->willReturn($mockedRouter);  

        $this->app = $this->getMockBuilder(App::class)
          ->setConstructorArgs([$this->mockedContainer])
          ->setMethods()
          ->getMock();
                
        $mockedRouter->expects($this->once())
          ->method('dispatch')
          ->with($this->anything());

        $this->app->run();
    }
}
