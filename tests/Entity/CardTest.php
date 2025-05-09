<?php

namespace WechatOfficialAccountCardBundle\Tests\Entity;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use WechatOfficialAccountBundle\Entity\Account;
use WechatOfficialAccountCardBundle\Entity\Card;
use WechatOfficialAccountCardBundle\Entity\Embed\CardBaseInfo;
use WechatOfficialAccountCardBundle\Entity\Embed\CardDateInfo;
use WechatOfficialAccountCardBundle\Enum\CardColor;
use WechatOfficialAccountCardBundle\Enum\CardStatus;
use WechatOfficialAccountCardBundle\Enum\CardType;
use WechatOfficialAccountCardBundle\Enum\CodeType;
use WechatOfficialAccountCardBundle\Enum\DateType;

class CardTest extends TestCase
{
    private Card $card;
    private Account $account;
    private CardBaseInfo $baseInfo;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->account = $this->createMock(Account::class);
        
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
        
        $this->card = new Card();
        $this->card->setAccount($this->account);
        $this->card->setCardId('cardId123');
        $this->card->setCardType(CardType::CASH);
        $this->card->setStatus(CardStatus::VERIFY_OK);
        $this->card->setBaseInfo($this->baseInfo);
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
        // 测试 ID
        $this->assertNull($this->card->getId());
        
        // 测试 CreatedBy
        $this->card->setCreatedBy('user1');
        $this->assertEquals('user1', $this->card->getCreatedBy());
        
        // 测试 UpdatedBy
        $this->card->setUpdatedBy('user2');
        $this->assertEquals('user2', $this->card->getUpdatedBy());
        
        // 测试 Syncing
        $this->assertFalse($this->card->isSyncing());
        $this->card->setSyncing(true);
        $this->assertTrue($this->card->isSyncing());
        
        // 测试 Account
        $this->assertSame($this->account, $this->card->getAccount());
        
        // 测试 CardId
        $this->assertEquals('cardId123', $this->card->getCardId());
        $this->card->setCardId('newCardId');
        $this->assertEquals('newCardId', $this->card->getCardId());
        
        // 测试 CardType
        $this->assertEquals(CardType::CASH, $this->card->getCardType());
        $this->card->setCardType(CardType::DISCOUNT);
        $this->assertEquals(CardType::DISCOUNT, $this->card->getCardType());
        
        // 测试 Status
        $this->assertEquals(CardStatus::VERIFY_OK, $this->card->getStatus());
        $this->card->setStatus(CardStatus::DISPATCH);
        $this->assertEquals(CardStatus::DISPATCH, $this->card->getStatus());
        
        // 测试 BaseInfo
        $this->assertSame($this->baseInfo, $this->card->getBaseInfo());
        
        // 测试时间
        $now = new DateTimeImmutable();
        $this->card->setCreateTime($now);
        $this->assertEquals($now, $this->card->getCreateTime());
        
        $this->card->setUpdateTime($now);
        $this->assertEquals($now, $this->card->getUpdateTime());
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
        $account = $this->createMock(Account::class);
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
        
        // 测试流畅接口
        $this->assertSame($card, $card->setCreatedBy('user1'));
        $this->assertSame($card, $card->setUpdatedBy('user2'));
        $this->assertSame($card, $card->setSyncing(true));
    }
} 