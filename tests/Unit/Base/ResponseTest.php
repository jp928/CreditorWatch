<?php declare(strict_types = 1);

namespace Tests\Unit\Base;

use App\Base\Response;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class ResponseTest extends TestCase
{
    public function testSetGetBody(): void
    {
        $class = new ReflectionClass(Response::class);
        $method = $class->getMethod('getBody');
        $method->setAccessible(true);

        $response = new Response();
        
        $response->setBody('testing body');

        $this->assertEquals('testing body', $method->invokeArgs($response, []));
    }
}
