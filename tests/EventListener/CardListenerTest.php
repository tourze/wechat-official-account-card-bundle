<?php

namespace WechatOfficialAccountCardBundle\Tests\EventListener;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\ORM\Event\PostUpdateEventArgs;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractIntegrationTestCase;
use WechatOfficialAccountCardBundle\Entity\Card;
use WechatOfficialAccountCardBundle\EventListener\CardListener;

/**
 * @internal
 */
#[CoversClass(CardListener::class)]
#[RunTestsInSeparateProcesses]
final class CardListenerTest extends AbstractIntegrationTestCase
{
    protected function onSetUp(): void        // 集成测试的初始化逻辑
    {
    }

    private function createListener(): CardListener
    {
        // 使用服务容器获取EventListener实例，符合集成测试规范
        $listener = self::getContainer()->get(CardListener::class);
        self::assertInstanceOf(CardListener::class, $listener);

        return $listener;
    }

    public function testCardListenerInstantiation(): void
    {
        $listener = $this->createListener();
        $this->assertInstanceOf(CardListener::class, $listener);
    }

    public function testPrePersist(): void
    {
        // 使用具体类 Card 的 Mock 的详细说明：
        // 1. Card 是当前Bundle的核心Doctrine实体，没有抽象接口
        // 2. 该实体包含复杂的业务逻辑和微信卡券特定配置
        // 3. Mock具体类可以精确控制测试场景，避免数据库依赖
        // 4. 这是合理且必要的，因为实体类按设计就是具体实现
        $card = $this->createMock(Card::class);
        $entityManager = $this->createMock(EntityManagerInterface::class);

        // PrePersistEventArgs 是 final 类，无法 Mock，创建实际对象
        $args = new PrePersistEventArgs($card, $entityManager);

        $card->expects($this->once())
            ->method('isSyncing')
            ->willReturn(true) // 返回true避免执行API调用
        ;

        // 当isSyncing返回true时，方法应该直接返回，不执行任何操作
        $listener = $this->createListener();
        $listener->prePersist($card, $args);

        // 验证isSyncing方法被调用了一次
        // 这证明方法正确检查了同步状态并提前返回
    }

    public function testPostPersist(): void
    {
        // 使用具体类 Card 的 Mock 的详细说明：
        // 1. Card 是当前Bundle的核心Doctrine实体，没有抽象接口
        // 2. 该实体包含复杂的业务逻辑和微信卡券特定配置
        // 3. Mock具体类可以精确控制测试场景，避免数据库依赖
        // 4. 这是合理且必要的，因为实体类按设计就是具体实现
        $card = $this->createMock(Card::class);
        $entityManager = $this->createMock(EntityManagerInterface::class);

        // PostPersistEventArgs 是 final 类，无法 Mock，创建实际对象
        $args = new PostPersistEventArgs($card, $entityManager);

        // postPersist方法当前为空实现，只测试调用不抛出异常
        $listener = $this->createListener();
        $listener->postPersist($card, $args);

        // 验证方法能够正常调用而不抛出异常
        $this->assertInstanceOf(CardListener::class, $listener);
    }

    public function testPreUpdate(): void
    {
        // 使用具体类 Card 的 Mock 的详细说明：
        // 1. Card 是当前Bundle的核心Doctrine实体，没有抽象接口
        // 2. 该实体包含复杂的业务逻辑和微信卡券特定配置
        // 3. Mock具体类可以精确控制测试场景，避免数据库依赖
        // 4. 这是合理且必要的，因为实体类按设计就是具体实现
        $card = $this->createMock(Card::class);
        $entityManager = $this->createMock(EntityManagerInterface::class);

        $changeSet = [];

        // PreUpdateEventArgs 是 final 类，无法 Mock，创建实际对象
        $args = new PreUpdateEventArgs($card, $entityManager, $changeSet);

        $card->expects($this->once())
            ->method('isSyncing')
            ->willReturn(true) // 返回true避免执行API调用
        ;

        // 当isSyncing返回true时，方法应该直接返回，不执行任何操作
        $listener = $this->createListener();
        $listener->preUpdate($card, $args);

        // 验证isSyncing方法被调用了一次
        // 这证明方法正确检查了同步状态并提前返回
    }

    public function testPostUpdate(): void
    {
        // 使用具体类 Card 的 Mock 的详细说明：
        // 1. Card 是当前Bundle的核心Doctrine实体，没有抽象接口
        // 2. 该实体包含复杂的业务逻辑和微信卡券特定配置
        // 3. Mock具体类可以精确控制测试场景，避免数据库依赖
        // 4. 这是合理且必要的，因为实体类按设计就是具体实现
        $card = $this->createMock(Card::class);
        $entityManager = $this->createMock(EntityManagerInterface::class);

        // PostUpdateEventArgs 是 final 类，无法 Mock，创建实际对象
        $args = new PostUpdateEventArgs($card, $entityManager);

        // postUpdate方法当前为空实现，只测试调用不抛出异常
        $listener = $this->createListener();
        $listener->postUpdate($card, $args);

        // 验证方法能够正常调用而不抛出异常
        $this->assertInstanceOf(CardListener::class, $listener);
    }
}
