<?php

namespace WechatOfficialAccountCardBundle\Tests\DependencyInjection;

use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitSymfonyUnitTest\AbstractDependencyInjectionExtensionTestCase;
use Tourze\SymfonyDependencyServiceLoader\AutoExtension;
use WechatOfficialAccountCardBundle\DependencyInjection\WechatOfficialAccountCardExtension;

/**
 * @internal
 */
#[CoversClass(WechatOfficialAccountCardExtension::class)]
final class WechatOfficialAccountCardExtensionTest extends AbstractDependencyInjectionExtensionTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testExtensionExtendsAutoExtension(): void
    {
        $extension = new WechatOfficialAccountCardExtension();
        $this->assertInstanceOf(AutoExtension::class, $extension);
    }

    public function testExtensionInstantiation(): void
    {
        $extension = new WechatOfficialAccountCardExtension();
        $this->assertInstanceOf(WechatOfficialAccountCardExtension::class, $extension);
    }

    public function testGetConfigDir(): void
    {
        $extension = new WechatOfficialAccountCardExtension();
        $reflection = new \ReflectionClass($extension);
        $method = $reflection->getMethod('getConfigDir');
        $method->setAccessible(true);

        $configDir = $method->invoke($extension);
        $this->assertIsString($configDir);

        $this->assertStringEndsWith('/Resources/config', $configDir);
        $this->assertDirectoryExists($configDir);
    }
}
