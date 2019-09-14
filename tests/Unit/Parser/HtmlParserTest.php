<?php declare(strict_types = 1);

namespace Tests\Unit\Parser;

use App\Parser\HtmlParser;
use PHPUnit\Framework\TestCase;

class HtmlParserTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();
        $this->parser = new HtmlParser();
    }

    protected function tearDown(): void
    {
        unset($this->parser);
        parent::tearDown();
    }

    public function testParse(): void
    {
        $testDom = '<html><body><div>test<a><h3>CreditorWatch</h3></a></div><a><h3>creditor watch</h3></a><a><h3>swiss watch</h3></a></body></html>';

        $result = $this->parser->parse($testDom);

        $this->assertEquals(['CreditorWatch', 'creditor watch'], $result);
    }
}
