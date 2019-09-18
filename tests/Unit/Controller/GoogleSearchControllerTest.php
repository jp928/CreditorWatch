<?php declare(strict_types = 1);

namespace Tests\Unit\Parser;

use App\Base\Request;
use App\Cache\Cache;
use App\Controller\GoogleSearchController;
use App\Entity\CreditorWatch;
use App\Entity\CreditorWatchCollection;
use App\Parser\HtmlParserInterface;
use App\Service\GoogleSearchService;
use App\Transport\DownloaderInterface;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use function file_get_contents;

class GoogleSearchControllerTest extends TestCase
{

    public function testPost(): void
    {
        $mockedRequest = $this->getMockBuilder(Request::class)
          ->setMethods(['getData'])
          ->getMock();

        $mockedRequest->expects($this->once())
          ->method('getData')
          ->with('keyword')
          ->willReturn('Creditor Watch');
        
        $mockedDownloader = $this->createMock(DownloaderInterface::class);
        $mockedPaser = $this->createMock(HtmlParserInterface::class);
        $mockedCache = $this->createMock(Cache::class);
        $mockedGoogleSearchService = $this->getMockBuilder(GoogleSearchService::class)
          ->setConstructorArgs([$mockedDownloader, $mockedPaser, $mockedCache])
          ->setMethods(['load', 'parse'])
          ->getMock();

        $mockedGoogleSearchService->expects($this->once())
          ->method('load')
          ->with('Creditor Watch')
          ->willReturn($mockedGoogleSearchService);
        
        $cw = new CreditorWatch();
        $cw->setKey('1');
        $cw->setValue('Creditor Watch Company');

        $cwc = new CreditorWatchCollection();
        $cwc->push($cw);

        $mockedGoogleSearchService->expects($this->once())
          ->method('parse')
          ->willReturn($cwc);

        $controller = $this->getMockBuilder(GoogleSearchController::class)
          ->setConstructorArgs([$mockedGoogleSearchService])
          ->setMethods()
          ->getMock();

        $result = $controller->post($mockedRequest);

        $reflection = new ReflectionClass(get_class($result));
        $method = $reflection->getMethod('getBody');
        $method->setAccessible(true);
        $actualResult = $method->invokeArgs($result, []);

        $expectedHtml = file_get_contents(__DIR__ . '/../../assets/post_result.html');
        $this->assertEquals($expectedHtml, $actualResult);
    }
}
