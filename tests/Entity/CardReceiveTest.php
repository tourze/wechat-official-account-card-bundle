<?php

namespace WechatOfficialAccountCardBundle\Tests\Entity;

use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitDoctrineEntity\AbstractEntityTestCase;
use WechatOfficialAccountCardBundle\Entity\Card;
use WechatOfficialAccountCardBundle\Entity\CardCode;
use WechatOfficialAccountCardBundle\Entity\CardReceive;

/**
 * @internal
 */
#[CoversClass(CardReceive::class)]
final class CardReceiveTest extends AbstractEntityTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // AbstractEntityTest 没有 onSetUp 方法，所以不需要调用 parent::onSetUp()
    }

    /**
     * @return iterable<string, array{0: string, 1: mixed}>
     */
    public static function propertiesProvider(): iterable
    {
        return [
            'usedAt' => ['usedAt', new \DateTimeImmutable()],
            'unavailableAt' => ['unavailableAt', new \DateTimeImmutable()],
            'givenAt' => ['givenAt', new \DateTimeImmutable()],
            'givenToOpenId' => ['givenToOpenId', 'test_open_id'],
        ];
    }

    protected function createEntity(): CardReceive
    {
        $entity = new CardReceive();
        // 为必需的Card和CardCode关联设置Mock对象
        $card = $this->createMock(Card::class);
        $cardCode = $this->createMock(CardCode::class);
        $entity->setCard($card);
        $entity->setCardCode($cardCode);

        return $entity;
    }

    public function testCardReceiveInitialState(): void
    {
        $cardReceive = new CardReceive();

        $this->assertEquals(0, $cardReceive->getId());
        $this->assertFalse($cardReceive->isUsed());
        $this->assertFalse($cardReceive->isUnavailable());
        $this->assertFalse($cardReceive->isGiven());
        $this->assertNull($cardReceive->getUsedAt());
        $this->assertNull($cardReceive->getUnavailableAt());
        $this->assertNull($cardReceive->getGivenAt());
        $this->assertNull($cardReceive->getGivenToOpenId());
    }

    public function testGetterSetterMethods(): void
    {
        $cardReceive = $this->createEntity();
        // 使用Mock对象模拟Card实体
        // Entity测试中需要Mock关联实体，因为：
        // 1. Card是具体Entity类，没有对应接口
        // 2. 需要控制关联对象的行为以便测试
        // 3. 这是Entity测试的标准做法
        $card = new Card();

        // 使用Mock对象模拟CardCode实体
        // Entity测试中需要Mock关联实体，因为：
        // 1. CardCode是具体Entity类，没有对应接口
        // 2. 需要控制关联对象的行为以便测试
        // 3. 这是Entity测试的标准做法
        $cardCode = new CardCode();

        $cardReceive->setCard($card);
        $this->assertSame($card, $cardReceive->getCard());

        $cardReceive->setCardCode($cardCode);
        $this->assertSame($cardCode, $cardReceive->getCardCode());

        $cardReceive->setOpenId('test_openid');
        $this->assertEquals('test_openid', $cardReceive->getOpenId());

        $cardReceive->setIsUsed(true);
        $this->assertTrue($cardReceive->isUsed());
        $this->assertInstanceOf(\DateTimeImmutable::class, $cardReceive->getUsedAt());

        $cardReceive->setIsUnavailable(true);
        $this->assertTrue($cardReceive->isUnavailable());
        $this->assertInstanceOf(\DateTimeImmutable::class, $cardReceive->getUnavailableAt());

        $cardReceive->setIsGiven(true);
        $this->assertTrue($cardReceive->isGiven());
        $this->assertInstanceOf(\DateTimeImmutable::class, $cardReceive->getGivenAt());

        $cardReceive->setGivenToOpenId('target_openid');
        $this->assertEquals('target_openid', $cardReceive->getGivenToOpenId());
    }

    public function testToString(): void
    {
        $cardReceive = $this->createEntity();
        $cardReceive->setOpenId('test_openid');
        $this->assertEquals('test_openid', (string) $cardReceive);

        $newCardReceive = new CardReceive();
        $this->assertEquals('New CardReceive', (string) $newCardReceive);
    }
}
