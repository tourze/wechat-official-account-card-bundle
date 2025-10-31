<?php

namespace WechatOfficialAccountCardBundle\Tests\Entity;

use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitDoctrineEntity\AbstractEntityTestCase;
use WechatOfficialAccountCardBundle\Entity\Card;
use WechatOfficialAccountCardBundle\Entity\CardCode;

/**
 * @internal
 */
#[CoversClass(CardCode::class)]
final class CardCodeTest extends AbstractEntityTestCase
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
            'code' => ['code', 'TEST_CODE_123'],
            'usedAt' => ['usedAt', new \DateTimeImmutable()],
            'unavailableAt' => ['unavailableAt', new \DateTimeImmutable()],
        ];
    }

    protected function createEntity(): CardCode
    {
        $entity = new CardCode();
        // 为必需的Card关联设置一个Mock对象
        $card = $this->createMock(Card::class);
        $entity->setCard($card);

        return $entity;
    }

    public function testCardCodeInitialState(): void
    {
        $cardCode = new CardCode();

        $this->assertEquals(0, $cardCode->getId());
        $this->assertFalse($cardCode->isUsed());
        $this->assertFalse($cardCode->isUnavailable());
        $this->assertNull($cardCode->getUsedAt());
        $this->assertNull($cardCode->getUnavailableAt());
    }

    public function testGetterSetterMethods(): void
    {
        $cardCode = $this->createEntity();
        // 使用Mock对象模拟Card实体
        // Entity测试中需要Mock关联实体，因为：
        // 1. Card是具体Entity类，没有对应接口
        // 2. 需要控制关联对象的行为以便测试
        // 3. 这是Entity测试的标准做法
        $card = new Card();

        $cardCode->setCard($card);
        $this->assertSame($card, $cardCode->getCard());

        $cardCode->setCode('TEST_CODE_123');
        $this->assertEquals('TEST_CODE_123', $cardCode->getCode());

        $cardCode->setIsUsed(true);
        $this->assertTrue($cardCode->isUsed());
        $this->assertInstanceOf(\DateTimeImmutable::class, $cardCode->getUsedAt());

        $cardCode->setIsUnavailable(true);
        $this->assertTrue($cardCode->isUnavailable());
        $this->assertInstanceOf(\DateTimeImmutable::class, $cardCode->getUnavailableAt());

        $now = new \DateTimeImmutable();
        $cardCode->setUsedAt($now);
        $this->assertEquals($now, $cardCode->getUsedAt());

        $cardCode->setUnavailableAt($now);
        $this->assertEquals($now, $cardCode->getUnavailableAt());
    }

    public function testToString(): void
    {
        $cardCode = $this->createEntity();
        $cardCode->setCode('TEST_CODE');
        $this->assertEquals('TEST_CODE', (string) $cardCode);

        $newCardCode = new CardCode();
        $this->assertEquals('New CardCode', (string) $newCardCode);
    }

    public function testBooleanFields(): void
    {
        $cardCode = $this->createEntity();
        $cardCode->setIsUsed(false);
        $this->assertFalse($cardCode->isUsed());

        $cardCode->setIsUnavailable(false);
        $this->assertFalse($cardCode->isUnavailable());
    }
}
