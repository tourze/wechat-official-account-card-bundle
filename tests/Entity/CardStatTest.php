<?php

namespace WechatOfficialAccountCardBundle\Tests\Entity;

use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitDoctrineEntity\AbstractEntityTestCase;
use WechatOfficialAccountCardBundle\Entity\Card;
use WechatOfficialAccountCardBundle\Entity\CardStat;

/**
 * @internal
 */
#[CoversClass(CardStat::class)]
final class CardStatTest extends AbstractEntityTestCase
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
            'receiveCount' => ['receiveCount', 10],
            'useCount' => ['useCount', 5],
            'giveCount' => ['giveCount', 3],
            'viewCount' => ['viewCount', 100],
            'newFollowCount' => ['newFollowCount', 2],
            'unfollowCount' => ['unfollowCount', 1],
            'giveReceiveCount' => ['giveReceiveCount', 8],
            'statsDate' => ['statsDate', new \DateTimeImmutable()],
        ];
    }

    protected function createEntity(): CardStat
    {
        $entity = new CardStat();
        // 为必需的Card关联设置Mock对象
        $card = $this->createMock(Card::class);
        $entity->setCard($card);

        return $entity;
    }

    public function testCardStatInitialState(): void
    {
        $cardStat = new CardStat();

        $this->assertEquals(0, $cardStat->getId());
        $this->assertEquals(0, $cardStat->getReceiveCount());
        $this->assertEquals(0, $cardStat->getUseCount());
        $this->assertEquals(0, $cardStat->getGiveCount());
        $this->assertEquals(0, $cardStat->getViewCount());
        $this->assertEquals(0, $cardStat->getNewFollowCount());
        $this->assertEquals(0, $cardStat->getUnfollowCount());
        $this->assertEquals(0, $cardStat->getGiveReceiveCount());
    }

    public function testGetterSetterMethods(): void
    {
        $cardStat = $this->createEntity();
        // 使用Mock对象模拟Card实体
        // Entity测试中需要Mock关联实体，因为：
        // 1. Card是具体Entity类，没有对应接口
        // 2. 需要控制关联对象的行为以便测试
        // 3. 这是Entity测试的标准做法
        $card = new Card();

        $cardStat->setCard($card);
        $this->assertSame($card, $cardStat->getCard());

        $statsDate = new \DateTimeImmutable('2023-01-01');
        $cardStat->setStatsDate($statsDate);
        $this->assertEquals($statsDate, $cardStat->getStatsDate());

        $cardStat->setReceiveCount(100);
        $this->assertEquals(100, $cardStat->getReceiveCount());

        $cardStat->setUseCount(80);
        $this->assertEquals(80, $cardStat->getUseCount());

        $cardStat->setGiveCount(20);
        $this->assertEquals(20, $cardStat->getGiveCount());

        $cardStat->setViewCount(200);
        $this->assertEquals(200, $cardStat->getViewCount());

        $cardStat->setNewFollowCount(50);
        $this->assertEquals(50, $cardStat->getNewFollowCount());

        $cardStat->setUnfollowCount(5);
        $this->assertEquals(5, $cardStat->getUnfollowCount());

        $cardStat->setGiveReceiveCount(15);
        $this->assertEquals(15, $cardStat->getGiveReceiveCount());
    }
}
