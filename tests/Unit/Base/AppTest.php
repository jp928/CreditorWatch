<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use App\Base\App;
use App\Parser\Dom;

class AppTest extends TestCase {
    protected function setUp(): void
    {
        parent::setUp();
        $this->app = $this->getMockBuilder(App::class)
          ->disableOriginalConstructor()
          ->setMethods(['domFactory'])
          ->getMock();
    }

    protected function tearDown(): void
    {
        unset($this->app);
        parent::tearDown();
    }

    public function testProcess()
    {
        $mockedDom = $this->createMock(Dom::class);
        $testKeyword = 'testKey Word';
        $mockedUrl = 'http://www.google.com/search?q=' . rawurlencode($testKeyword) . '&start=0&num=100';

        $this->app->expects($this->once())
          ->method('domFactory')
          ->willReturn($mockedDom);

        $mockedDom->expects($this->once())
          ->method('load')
          ->with($mockedUrl);

        $mockedDom->expects($this->once())
          ->method('parse');
        
        $this->app->setUrl($testKeyword);
        $this->app->process();
    }
}
