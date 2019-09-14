<?php declare(strict_types = 1);

namespace Tests\Unit\Parser;

use App\Parser\Dom;
use App\Parser\Downloader;
use App\Parser\HtmlParser;
use PHPUnit\Framework\TestCase;

class DomTest extends TestCase
{

    /** @var \App\Parser\Downloader */
    private $mockedDownloader;

    /** @var \App\Parser\HtmlParser */
    private $mockedHtmlParser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->mockedDownloader = $this->createMock(Downloader::class);
        $this->mockedHtmlParser = $this->createMock(HtmlParser::class);

        $this->dom = $this->getMockBuilder(Dom::class)
            ->setConstructorArgs([$this->mockedDownloader, $this->mockedHtmlParser])
            ->setMethods(['persistCache', 'getContentFromCache'])
            ->getMock();
    }

    protected function tearDown(): void
    {
        unset($this->dom);
        unset($this->mockedDownloader);
        unset($this->mockedHtmlParser);
        parent::tearDown();
    }

    public function testLoad(): void
    {
        $testUrl = 'http://google.com/search=test';
        $testDom = '<html><body><div>test</div></body></html>';
        $this->mockedDownloader->expects($this->once())
          ->method('download')
          ->with($testUrl)
          ->willReturn($testDom);

        $this->dom->expects($this->once())
          ->method('getContentFromCache')
          ->willReturn(null);
        
        $this->dom->expects($this->once())
          ->method('persistCache')
          ->with($testDom);

        $this->dom->load($testUrl);
    }

    public function testParse(): void
    {
        $testUrl = 'http://google.com/search=test';
        $testDom = '<html><head><scrip>var a = b;</script></head><body><div>test</div><script></script></body></html>';

        $this->mockedDownloader->expects($this->never())
          ->method('download');

        $this->dom->expects($this->once())
          ->method('getContentFromCache')
          ->willReturn($testDom);
        
        $this->dom->expects($this->never())
          ->method('persistCache');

        $this->mockedHtmlParser->expects($this->once())
          ->method('parse')
          ->with('<html><body><div>test</div></body></html>');

        $this->dom->load($testUrl);

        $this->dom->parse();
    }
}
