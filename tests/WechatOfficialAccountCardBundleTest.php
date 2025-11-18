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
}
