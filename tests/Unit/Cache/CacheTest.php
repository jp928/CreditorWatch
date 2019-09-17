<?php declare(strict_types = 1);

namespace Tests\Unit\Base;

use App\Cache\Cache;
use App\Cache\CacheEngineInterface;
use PHPUnit\Framework\TestCase;
use function serialize;

class CacheTest extends TestCase
{
    public function testObtain(): void
    {
        $mockedCacheEngine = $this->createMock(CacheEngineInterface::class);

        $cache = $this->getMockBuilder(Cache::class)
            ->setConstructorArgs([$mockedCacheEngine])
            ->setMethods()
            ->getMock();

        $testReulst = serialize('test');
        $mockedCacheEngine->expects($this->once())
          ->method('obtain')
          ->with('testKey')
          ->willReturn($testReulst);

        $result = $cache->obtain('testKey');

        $this->assertEquals('test', $result);
    }

    public function testPersist(): void
    {
        $mockedCacheEngine = $this->createMock(CacheEngineInterface::class);

        $cache = $this->getMockBuilder(Cache::class)
            ->setConstructorArgs([$mockedCacheEngine])
            ->setMethods()
            ->getMock();

        $mockedCacheEngine->expects($this->once())
          ->method('persist')
          ->with('testKey', 'test')
          ->willReturn(true);

        $result = $cache->persist('testKey', 'test');

        $this->assertEquals(true, $result);
    }
}
