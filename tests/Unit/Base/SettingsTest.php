<?php declare(strict_types = 1);

namespace Tests\Unit\Base;

use App\Base\Settings;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class SettingsTest extends TestCase
{
    public function testSetGetBody(): void
    {
        $settings = new Settings();

        $this->assertEquals([
          'expire' => 36000,
          'prefix' => 'CreditorWatch',
          'host' => 'redis'
        ], $settings['redis']);
    }
}
