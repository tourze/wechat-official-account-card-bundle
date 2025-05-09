<?php

namespace WechatOfficialAccountCardBundle\Tests\Enum;

use PHPUnit\Framework\TestCase;
use WechatOfficialAccountCardBundle\Enum\CardType;

class CardTypeTest extends TestCase
{
    public function testCardTypeValues(): void
    {
        // 测试所有枚举值是否有效
        $this->assertEquals('GROUPON', CardType::GROUPON->value);
        $this->assertEquals('CASH', CardType::CASH->value);
        $this->assertEquals('DISCOUNT', CardType::DISCOUNT->value);
        $this->assertEquals('GIFT', CardType::GIFT->value);
        $this->assertEquals('GENERAL_COUPON', CardType::GENERAL_COUPON->value);
        $this->assertEquals('MEMBER_CARD', CardType::MEMBER_CARD->value);
        $this->assertEquals('SCENIC_TICKET', CardType::SCENIC_TICKET->value);
        $this->assertEquals('MOVIE_TICKET', CardType::MOVIE_TICKET->value);
        $this->assertEquals('BOARDING_PASS', CardType::BOARDING_PASS->value);
        $this->assertEquals('MEETING_TICKET', CardType::MEETING_TICKET->value);
        $this->assertEquals('BUS_TICKET', CardType::BUS_TICKET->value);
    }
    
    public function testCardTypeInstances(): void
    {
        // 测试枚举实例是否符合预期
        $this->assertInstanceOf(CardType::class, CardType::GROUPON);
        $this->assertInstanceOf(CardType::class, CardType::CASH);
        $this->assertInstanceOf(CardType::class, CardType::DISCOUNT);
        $this->assertInstanceOf(CardType::class, CardType::GIFT);
        $this->assertInstanceOf(CardType::class, CardType::GENERAL_COUPON);
        $this->assertInstanceOf(CardType::class, CardType::MEMBER_CARD);
        $this->assertInstanceOf(CardType::class, CardType::SCENIC_TICKET);
        $this->assertInstanceOf(CardType::class, CardType::MOVIE_TICKET);
        $this->assertInstanceOf(CardType::class, CardType::BOARDING_PASS);
        $this->assertInstanceOf(CardType::class, CardType::MEETING_TICKET);
        $this->assertInstanceOf(CardType::class, CardType::BUS_TICKET);
    }
    
    public function testCardTypeEquality(): void
    {
        // 测试相同枚举值的实例是否相等
        $this->assertEquals(CardType::CASH, CardType::CASH);
        $this->assertEquals(CardType::DISCOUNT, CardType::DISCOUNT);
        
        // 测试不同枚举值的实例是否不相等
        $this->assertNotEquals(CardType::CASH, CardType::DISCOUNT);
        $this->assertNotEquals(CardType::GROUPON, CardType::GIFT);
    }
    
    public function testCardTypeFromString(): void
    {
        // 测试从字符串创建枚举实例
        $this->assertEquals(CardType::CASH, CardType::from('CASH'));
        $this->assertEquals(CardType::DISCOUNT, CardType::from('DISCOUNT'));
        $this->assertEquals(CardType::GIFT, CardType::from('GIFT'));
        
        // 测试无效字符串应抛出异常
        $this->expectException(\ValueError::class);
        CardType::from('INVALID_TYPE');
    }
    
    public function testCardTypeTryFrom(): void
    {
        // 测试从字符串尝试创建枚举实例
        $this->assertEquals(CardType::CASH, CardType::tryFrom('CASH'));
        $this->assertEquals(CardType::DISCOUNT, CardType::tryFrom('DISCOUNT'));
        
        // 测试无效字符串应返回null
        $this->assertNull(CardType::tryFrom('INVALID_TYPE'));
    }
    
    public function testCardTypeNames(): void
    {
        // 测试枚举名称
        $this->assertEquals('GROUPON', CardType::GROUPON->name);
        $this->assertEquals('CASH', CardType::CASH->name);
        $this->assertEquals('DISCOUNT', CardType::DISCOUNT->name);
        $this->assertEquals('GIFT', CardType::GIFT->name);
    }
    
    public function testCardTypeCases(): void
    {
        // 测试所有枚举用例
        $cases = CardType::cases();
        
        $this->assertCount(11, $cases);
        $this->assertContainsOnlyInstancesOf(CardType::class, $cases);
        
        // 验证是否包含所有枚举值
        $this->assertContains(CardType::GROUPON, $cases);
        $this->assertContains(CardType::CASH, $cases);
        $this->assertContains(CardType::DISCOUNT, $cases);
        $this->assertContains(CardType::GIFT, $cases);
        $this->assertContains(CardType::GENERAL_COUPON, $cases);
        $this->assertContains(CardType::MEMBER_CARD, $cases);
        $this->assertContains(CardType::SCENIC_TICKET, $cases);
        $this->assertContains(CardType::MOVIE_TICKET, $cases);
        $this->assertContains(CardType::BOARDING_PASS, $cases);
        $this->assertContains(CardType::MEETING_TICKET, $cases);
        $this->assertContains(CardType::BUS_TICKET, $cases);
    }
} 