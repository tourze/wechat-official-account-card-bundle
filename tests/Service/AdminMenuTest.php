<?php

namespace WechatOfficialAccountCardBundle\Tests\Service;

use Knp\Menu\ItemInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use PHPUnit\Framework\MockObject\MockObject;
use Tourze\EasyAdminMenuBundle\Service\LinkGeneratorInterface;
use Tourze\PHPUnitSymfonyWebTest\AbstractEasyAdminMenuTestCase;
use WechatOfficialAccountCardBundle\Entity\Card;
use WechatOfficialAccountCardBundle\Entity\CardCode;
use WechatOfficialAccountCardBundle\Entity\CardReceive;
use WechatOfficialAccountCardBundle\Entity\CardStat;
use WechatOfficialAccountCardBundle\Service\AdminMenu;

/**
 * @internal
 */
#[CoversClass(AdminMenu::class)]
#[RunTestsInSeparateProcesses]
final class AdminMenuTest extends AbstractEasyAdminMenuTestCase
{
    private AdminMenu $adminMenu;

    private LinkGeneratorInterface&MockObject $linkGenerator;

    protected function onSetUp(): void
    {
        // 在每个测试方法中按需初始化
    }

    protected function prepareTest(): void
    {
        $this->linkGenerator = $this->createMock(LinkGeneratorInterface::class);

        // 注入Mock的LinkGenerator
        self::getContainer()->set(LinkGeneratorInterface::class, $this->linkGenerator);
        $this->adminMenu = self::getService(AdminMenu::class);
    }

    public function testInvokeCreatesCompleteMenuStructure(): void
    {
        $this->prepareTest();

        $rootItem = $this->createMock(ItemInterface::class);
        $wechatMenu = $this->createMock(ItemInterface::class);
        $cardMenu = $this->createMock(ItemInterface::class);

        // 模拟第一次调用返回null，第二次返回wechatMenu
        $rootItem->expects($this->exactly(2))
            ->method('getChild')
            ->with('微信管理')
            ->willReturnOnConsecutiveCalls(null, $wechatMenu)
        ;

        $rootItem->expects($this->once())
            ->method('addChild')
            ->with('微信管理')
            ->willReturn($this->createMock(ItemInterface::class))
        ;

        // 模拟第一次调用返回null，第二次返回cardMenu
        $wechatMenu->expects($this->exactly(2))
            ->method('getChild')
            ->with('卡券管理')
            ->willReturnOnConsecutiveCalls(null, $cardMenu)
        ;

        $cardMenuItem = $this->createMock(ItemInterface::class);
        $cardMenuItem->expects($this->once())
            ->method('setAttribute')
            ->with('icon', 'fas fa-ticket-alt')
            ->willReturnSelf()
        ;

        $wechatMenu->expects($this->once())
            ->method('addChild')
            ->with('卡券管理')
            ->willReturn($cardMenuItem)
        ;

        // 设置链接生成器期望
        $this->linkGenerator->method('getCurdListPage')
            ->willReturnCallback(function (string $entityClass): string {
                return match ($entityClass) {
                    Card::class => '/admin/card',
                    CardCode::class => '/admin/card-code',
                    CardReceive::class => '/admin/card-receive',
                    CardStat::class => '/admin/card-stat',
                    default => '/admin/unknown',
                };
            })
        ;

        // 创建菜单项mocks
        $cardListItem = $this->createMock(ItemInterface::class);
        $cardCodeItem = $this->createMock(ItemInterface::class);
        $cardReceiveItem = $this->createMock(ItemInterface::class);
        $cardStatItem = $this->createMock(ItemInterface::class);

        // 设置菜单项的链式调用
        $cardListItem->method('setUri')->willReturnSelf();
        $cardListItem->method('setAttribute')->willReturnSelf();
        $cardCodeItem->method('setUri')->willReturnSelf();
        $cardCodeItem->method('setAttribute')->willReturnSelf();
        $cardReceiveItem->method('setUri')->willReturnSelf();
        $cardReceiveItem->method('setAttribute')->willReturnSelf();
        $cardStatItem->method('setUri')->willReturnSelf();
        $cardStatItem->method('setAttribute')->willReturnSelf();

        // 设置addChild期望，按顺序返回不同的菜单项
        $cardMenu->method('addChild')
            ->willReturnCallback(function (string $title) use ($cardListItem, $cardCodeItem, $cardReceiveItem, $cardStatItem) {
                return match ($title) {
                    '卡券列表' => $cardListItem,
                    '卡券码管理' => $cardCodeItem,
                    '领取记录' => $cardReceiveItem,
                    '统计数据' => $cardStatItem,
                    default => $this->createMock(ItemInterface::class),
                };
            })
        ;

        ($this->adminMenu)($rootItem);

        // 验证方法被正确调用 - 通过测试不抛出异常来验证
        $this->assertTrue(true, 'Menu creation completed without exceptions');
    }

    public function testInvokeBasicFunctionality(): void
    {
        $this->prepareTest();

        $rootItem = $this->createMock(ItemInterface::class);
        $wechatMenu = $this->createMock(ItemInterface::class);
        $cardMenu = $this->createMock(ItemInterface::class);

        // 验证getChild方法会被调用
        $rootItem->expects($this->atLeastOnce())
            ->method('getChild')
            ->with('微信管理')
            ->willReturn($wechatMenu)
        ;

        $wechatMenu->expects($this->atLeastOnce())
            ->method('getChild')
            ->with('卡券管理')
            ->willReturn($cardMenu)
        ;

        // 验证菜单项会被添加
        $menuItem = $this->createMock(ItemInterface::class);
        $menuItem->expects($this->atLeastOnce())
            ->method('setUri')
            ->willReturnSelf()
        ;
        $menuItem->expects($this->atLeastOnce())
            ->method('setAttribute')
            ->willReturnSelf()
        ;

        $cardMenu->expects($this->atLeastOnce())
            ->method('addChild')
            ->willReturn($menuItem)
        ;

        // 验证链接生成器会被调用
        $this->linkGenerator->expects($this->atLeastOnce())
            ->method('getCurdListPage')
            ->willReturn('/admin/mock')
        ;

        // 执行并验证没有异常
        ($this->adminMenu)($rootItem);

        // 验证执行成功
        $this->assertInstanceOf(AdminMenu::class, $this->adminMenu);
    }
}
