<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use App\Parser\HtmlParser;

class HtmlParserTest extends TestCase {
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

    public function testParse()
    {
        $testDom = '<html><body><div>test<a><h3>CreditorWatch</h3></a></div><a><h3>creditor watch</h3></a></body></html>';

        $result = $this->parser->parse($testDom);

        $this->assertEquals(['CreditorWatch', 'creditor watch'], $result);
    }
}
