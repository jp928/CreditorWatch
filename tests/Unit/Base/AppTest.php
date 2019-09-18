<?php declare(strict_types = 1);

namespace Tests\Unit\Base;

use App\Base\App;
use App\Base\Request;
use App\Base\Router;
use App\Container\ContainerInterface;
use PHPUnit\Framework\TestCase;

class AppTest extends TestCase
{

    public function testProcess(): void
    {
        $mockedRouter = $this->getMockBuilder(Router::class)
          ->setMethods(['bindContainer', 'dispatch', 'registerControllers'])
          ->getMock();

        $mockedRequest = $this->getMockBuilder(Request::class)
          ->setMethods(['parseRequest'])
          ->getMock();

        $mockedContainer = $this->createMock(ContainerInterface::class);

        $mockedContainer->expects($this->at(0))
          ->method('resolve')
          ->with(Router::class)
          ->willReturn($mockedRouter);

        $mockedContainer->expects($this->at(1))
          ->method('resolve')
          ->with(Request::class)
          ->willReturn($mockedRequest);
        
        $mockedRouter->expects($this->once())
          ->method('bindContainer')
          ->with($mockedContainer)
          ->willReturn($mockedRouter);

        $app = $this->getMockBuilder(App::class)
          ->setConstructorArgs([$mockedContainer])
          ->setMethods()
          ->getMock();
                
        $mockedRouter->expects($this->once())
          ->method('dispatch')
          ->with($this->anything());

        $app->run();
    }
}
