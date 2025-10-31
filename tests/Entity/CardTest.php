<?php

namespace WechatOfficialAccountCardBundle\Tests\Entity;

use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitDoctrineEntity\AbstractEntityTestCase;
use WechatOfficialAccountBundle\Entity\Account;
use WechatOfficialAccountCardBundle\Entity\Card;
use WechatOfficialAccountCardBundle\Entity\Embed\CardBaseInfo;
use WechatOfficialAccountCardBundle\Entity\Embed\CardDateInfo;
use WechatOfficialAccountCardBundle\Enum\CardColor;
use WechatOfficialAccountCardBundle\Enum\CardStatus;
use WechatOfficialAccountCardBundle\Enum\CardType;
use WechatOfficialAccountCardBundle\Enum\CodeType;
use WechatOfficialAccountCardBundle\Enum\DateType;

/**
 * @internal
 */
#[CoversClass(Card::class)]
final class CardTest extends AbstractEntityTestCase
{
    private Account $account;

    private CardBaseInfo $baseInfo;

    protected function setUp(): void
    {
        parent::setUp();
        // AbstractEntityTest 没有 onSetUp 方法，所以不需要调用 parent::onSetUp()
        // 使用Mock对象模拟Account实体
        // Entity测试中需要Mock关联实体，因为：
        // 1. Account是具体Entity类，没有对应接口
        // 2. 需要控制关联对象的行为以便测试
        // 3. 这是Entity测试的标准做法
        $this->account = new Account();

        $dateInfo = new CardDateInfo();
        $dateInfo->setType(DateType::DATE_TYPE_FIX_TIME_RANGE);
        $dateInfo->setBeginTimestamp(time());
        $dateInfo->setEndTimestamp(time() + 86400 * 30); // 30天后

        $this->baseInfo = new CardBaseInfo();
        $this->baseInfo->setLogoUrl('https://example.com/logo.png');
        $this->baseInfo->setBrandName('测试品牌');
        $this->baseInfo->setCodeType(CodeType::CODE_TYPE_QRCODE);
        $this->baseInfo->setTitle('测试卡券');
        $this->baseInfo->setColor(CardColor::COLOR_010);
        $this->baseInfo->setNotice('使用提醒');
        $this->baseInfo->setDescription('使用说明详情');
        $this->baseInfo->setQuantity(1000);
        $this->baseInfo->setUseLimit(1);
        $this->baseInfo->setGetLimit(1);
        $this->baseInfo->setCanShare(true);
        $this->baseInfo->setCanGiveFriend(true);
        $this->baseInfo->setServicePhone('13800138000');
        $this->baseInfo->setDateInfo($dateInfo);

        $card = new Card();
        $card->setAccount($this->account);
        $card->setCardId('cardId123');
        $card->setCardType(CardType::CASH);
        $card->setStatus(CardStatus::VERIFY_OK);
        $card->setBaseInfo($this->baseInfo);
    }

    public function testCardInitialState(): void
    {
        $card = new Card();

        // 检查初始状态下的默认值
        $this->assertNull($card->getId());
        $this->assertNull($card->getCreatedBy());
        $this->assertNull($card->getUpdatedBy());
        $this->assertNull($card->getCreateTime());
        $this->assertNull($card->getUpdateTime());
        $this->assertFalse($card->isSyncing());
    }

    public function testGetterSetterMethods(): void
    {
        $card = $this->createEntity();
        // 测试 ID
        $this->assertNull($card->getId());

        // 测试 CreatedBy
        $card->setCreatedBy('user1');
        $this->assertEquals('user1', $card->getCreatedBy());

        // 测试 UpdatedBy
        $card->setUpdatedBy('user2');
        $this->assertEquals('user2', $card->getUpdatedBy());

        // 测试 Syncing
        $this->assertFalse($card->isSyncing());
        $card->setSyncing(true);
        $this->assertTrue($card->isSyncing());

        // 测试 Account
        $this->assertInstanceOf(Account::class, $card->getAccount());

        // 测试 CardId
        $card->setCardId('newCardId');
        $this->assertEquals('newCardId', $card->getCardId());

        // 测试 CardType
        $card->setCardType(CardType::DISCOUNT);
        $this->assertEquals(CardType::DISCOUNT, $card->getCardType());

        // 测试 Status
        $card->setStatus(CardStatus::DISPATCH);
        $this->assertEquals(CardStatus::DISPATCH, $card->getStatus());

        // 测试 BaseInfo
        $this->assertInstanceOf(CardBaseInfo::class, $card->getBaseInfo());

        // 测试时间
        $now = new \DateTimeImmutable();
        $card->setCreateTime($now);
        $this->assertEquals($now, $card->getCreateTime());

        $card->setUpdateTime($now);
        $this->assertEquals($now, $card->getUpdateTime());
    }

    public function testCardWithNullableFields(): void
    {
        $card = new Card();

        // 测试可空字段设置为null
        $card->setCreatedBy(null);
        $this->assertNull($card->getCreatedBy());

        $card->setUpdatedBy(null);
        $this->assertNull($card->getUpdatedBy());

        $card->setCreateTime(null);
        $this->assertNull($card->getCreateTime());

        $card->setUpdateTime(null);
        $this->assertNull($card->getUpdateTime());
    }

    public function testCardWithRequiredFields(): void
    {
        $card = new Card();

        // 设置必要字段
        // 使用Mock对象模拟Account实体
        // Entity测试中需要Mock关联实体，因为：
        // 1. Account是具体Entity类，没有对应接口
        // 2. 需要控制关联对象的行为以便测试
        // 3. 这是Entity测试的标准做法
        $account = new Account();
        $card->setAccount($account);
        $this->assertSame($account, $card->getAccount());

        $card->setCardId('test-card-id');
        $this->assertEquals('test-card-id', $card->getCardId());

        $card->setCardType(CardType::GIFT);
        $this->assertEquals(CardType::GIFT, $card->getCardType());

        $card->setStatus(CardStatus::NOT_VERIFY);
        $this->assertEquals(CardStatus::NOT_VERIFY, $card->getStatus());

        $baseInfo = new CardBaseInfo();
        $card->setBaseInfo($baseInfo);
        $this->assertSame($baseInfo, $card->getBaseInfo());
    }

    public function testFluidInterfaces(): void
    {
        $card = new Card();

        // 测试setter方法调用 - 这些方法返回void，不支持链式调用
        $card->setCreatedBy('user1');
        $this->assertEquals('user1', $card->getCreatedBy());

        $card->setUpdatedBy('user2');
        $this->assertEquals('user2', $card->getUpdatedBy());

        $card->setSyncing(true);
        $this->assertTrue($card->isSyncing());
    }

    /**
     * @return iterable<string, array{0: string, 1: mixed}>
     */
    public static function propertiesProvider(): iterable
    {
        return [
            'cardId' => ['cardId', 'test_card_id'],
            'cardType' => ['cardType', CardType::GROUPON],
            'status' => ['status', CardStatus::VERIFY_OK],
            'syncing' => ['syncing', true],
            'createdBy' => ['createdBy', 'test_user'],
            'updatedBy' => ['updatedBy', 'test_user'],
            'createTime' => ['createTime', new \DateTimeImmutable()],
            'updateTime' => ['updateTime', new \DateTimeImmutable()],
        ];
    }

    protected function createEntity(): Card
    {
        $entity = new Card();
        // 为必需的Account关联设置Mock对象
        $account = $this->createMock(Account::class);
        $entity->setAccount($account);

        // 为必需的baseInfo设置一个基本对象
        $baseInfo = new CardBaseInfo();
        $baseInfo->setLogoUrl('https://example.com/logo.png');
        $baseInfo->setBrandName('Test Brand');
        $baseInfo->setCodeType(CodeType::CODE_TYPE_TEXT);
        $baseInfo->setTitle('Test Card');
        $baseInfo->setColor(CardColor::COLOR_010);
        $baseInfo->setNotice('Test Notice');
        $baseInfo->setDescription('Test Description');
        $baseInfo->setQuantity(100);
        $baseInfo->setUseLimit(1);
        $baseInfo->setGetLimit(1);

        $dateInfo = new CardDateInfo();
        $dateInfo->setType(DateType::DATE_TYPE_FIX_TIME_RANGE);
        $dateInfo->setBeginTimestamp(time());
        $dateInfo->setEndTimestamp(time() + 86400);
        $baseInfo->setDateInfo($dateInfo);

        $entity->setBaseInfo($baseInfo);

        return $entity;
    }
}
