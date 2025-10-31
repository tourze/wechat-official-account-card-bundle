<?php

declare(strict_types=1);

namespace WechatOfficialAccountCardBundle\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractBundleTestCase;
use WechatOfficialAccountCardBundle\WechatOfficialAccountCardBundle;

/**
 * @internal
 */
#[CoversClass(WechatOfficialAccountCardBundle::class)]
#[RunTestsInSeparateProcesses]
final class WechatOfficialAccountCardBundleTest extends AbstractBundleTestCase
{
    public function testBundleInstantiation(): void
    {
        $bundleClass = static::getBundleClass();
        $bundle = new $bundleClass();
        $this->assertInstanceOf(WechatOfficialAccountCardBundle::class, $bundle);
    }

    public function testGetBundleDependencies(): void
    {
        $dependencies = WechatOfficialAccountCardBundle::getBundleDependencies();

        $this->assertArrayHasKey('Doctrine\Bundle\DoctrineBundle\DoctrineBundle', $dependencies);
        $this->assertArrayHasKey('WechatOfficialAccountBundle\WechatOfficialAccountBundle', $dependencies);

        $this->assertEquals(['all' => true], $dependencies['Doctrine\Bundle\DoctrineBundle\DoctrineBundle']);
        $this->assertEquals(['all' => true], $dependencies['WechatOfficialAccountBundle\WechatOfficialAccountBundle']);
    }
}
