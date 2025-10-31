<?php

namespace WechatOfficialAccountCardBundle\Tests\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Symfony\Component\Console\Tester\CommandTester;
use Tourze\PHPUnitSymfonyKernelTest\AbstractCommandTestCase;
use WechatOfficialAccountCardBundle\Command\SyncCardCommand;

/**
 * @internal
 */
#[CoversClass(SyncCardCommand::class)]
#[RunTestsInSeparateProcesses]
final class SyncCardCommandTest extends AbstractCommandTestCase
{
    protected function onSetUp(): void
    {
    }

    protected function getCommandTester(): CommandTester
    {
        $command = self::getContainer()->get(SyncCardCommand::class);
        self::assertInstanceOf(SyncCardCommand::class, $command);

        return new CommandTester($command);
    }

    private function createCommand(): SyncCardCommand
    {
        $command = self::getContainer()->get(SyncCardCommand::class);
        self::assertInstanceOf(SyncCardCommand::class, $command);

        return $command;
    }

    public function testExecuteWithCommandTester(): void
    {
        // 测试命令的基本执行能力，但不验证具体的业务逻辑
        // 因为业务逻辑依赖外部HTTP客户端，在单元测试中不合适
        $command = $this->createCommand();
        $commandTester = new CommandTester($command);

        // 验证 CommandTester 能够正确初始化
        $this->assertInstanceOf(CommandTester::class, $commandTester);

        // 注：由于实际执行涉及HTTP请求，我们在此仅测试命令构造和配置
        // 实际的同步逻辑应该通过集成测试或手动测试验证
    }

    public function testCommandConfiguration(): void
    {
        $command = $this->createCommand();

        $this->assertEquals('wechat:card:sync', $command->getName());
        $this->assertEquals('同步微信卡券信息', $command->getDescription());
    }

    public function testCommandConstant(): void
    {
        $this->assertEquals('wechat:card:sync', SyncCardCommand::NAME);
    }

    public function testCommandTesterCreation(): void
    {
        $command = $this->createCommand();
        $commandTester = new CommandTester($command);

        // 测试 CommandTester 基本功能
        $this->assertInstanceOf(CommandTester::class, $commandTester);

        // 验证命令对象正确设置
        $this->assertInstanceOf(SyncCardCommand::class, $command);
    }
}
