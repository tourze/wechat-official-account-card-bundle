<?php

namespace WechatOfficialAccountCardBundle\Tests\Enum;

use PHPUnit\Framework\TestCase;
use WechatOfficialAccountCardBundle\Enum\CardStatus;

class CardStatusTest extends TestCase
{
    public function testCardStatusValues(): void
    {
        // 测试所有枚举值是否有效
        $this->assertEquals('CARD_STATUS_NOT_VERIFY', CardStatus::NOT_VERIFY->value);
        $this->assertEquals('CARD_STATUS_VERIFY_FAIL', CardStatus::VERIFY_FAIL->value);
        $this->assertEquals('CARD_STATUS_VERIFY_OK', CardStatus::VERIFY_OK->value);
        $this->assertEquals('CARD_STATUS_DELETE', CardStatus::DELETE->value);
        $this->assertEquals('CARD_STATUS_DISPATCH', CardStatus::DISPATCH->value);
    }
    
    public function testCardStatusInstances(): void
    {
        // 测试枚举实例是否符合预期
        $this->assertInstanceOf(CardStatus::class, CardStatus::NOT_VERIFY);
        $this->assertInstanceOf(CardStatus::class, CardStatus::VERIFY_FAIL);
        $this->assertInstanceOf(CardStatus::class, CardStatus::VERIFY_OK);
        $this->assertInstanceOf(CardStatus::class, CardStatus::DELETE);
        $this->assertInstanceOf(CardStatus::class, CardStatus::DISPATCH);
    }
    
    public function testCardStatusEquality(): void
    {
        // 测试相同枚举值的实例是否相等
        $this->assertEquals(CardStatus::NOT_VERIFY, CardStatus::NOT_VERIFY);
        $this->assertEquals(CardStatus::VERIFY_OK, CardStatus::VERIFY_OK);
        
        // 测试不同枚举值的实例是否不相等
        $this->assertNotEquals(CardStatus::NOT_VERIFY, CardStatus::VERIFY_OK);
        $this->assertNotEquals(CardStatus::VERIFY_FAIL, CardStatus::DISPATCH);
    }
    
    public function testCardStatusFromString(): void
    {
        // 测试从字符串创建枚举实例
        $this->assertEquals(CardStatus::NOT_VERIFY, CardStatus::from('CARD_STATUS_NOT_VERIFY'));
        $this->assertEquals(CardStatus::VERIFY_FAIL, CardStatus::from('CARD_STATUS_VERIFY_FAIL'));
        $this->assertEquals(CardStatus::VERIFY_OK, CardStatus::from('CARD_STATUS_VERIFY_OK'));
        $this->assertEquals(CardStatus::DELETE, CardStatus::from('CARD_STATUS_DELETE'));
        $this->assertEquals(CardStatus::DISPATCH, CardStatus::from('CARD_STATUS_DISPATCH'));
        
        // 测试无效字符串应抛出异常
        $this->expectException(\ValueError::class);
        CardStatus::from('INVALID_STATUS');
    }
    
    public function testCardStatusTryFrom(): void
    {
        // 测试从字符串尝试创建枚举实例
        $this->assertEquals(CardStatus::NOT_VERIFY, CardStatus::tryFrom('CARD_STATUS_NOT_VERIFY'));
        $this->assertEquals(CardStatus::VERIFY_OK, CardStatus::tryFrom('CARD_STATUS_VERIFY_OK'));
        
        // 测试无效字符串应返回null
        $this->assertNull(CardStatus::tryFrom('INVALID_STATUS'));
    }
    
    public function testCardStatusNames(): void
    {
        // 测试枚举名称
        $this->assertEquals('NOT_VERIFY', CardStatus::NOT_VERIFY->name);
        $this->assertEquals('VERIFY_FAIL', CardStatus::VERIFY_FAIL->name);
        $this->assertEquals('VERIFY_OK', CardStatus::VERIFY_OK->name);
        $this->assertEquals('DELETE', CardStatus::DELETE->name);
        $this->assertEquals('DISPATCH', CardStatus::DISPATCH->name);
    }
    
    public function testCardStatusCases(): void
    {
        // 测试所有枚举用例
        $cases = CardStatus::cases();
        
        $this->assertCount(5, $cases);
        $this->assertContainsOnlyInstancesOf(CardStatus::class, $cases);
        
        // 验证是否包含所有枚举值
        $this->assertContains(CardStatus::NOT_VERIFY, $cases);
        $this->assertContains(CardStatus::VERIFY_FAIL, $cases);
        $this->assertContains(CardStatus::VERIFY_OK, $cases);
        $this->assertContains(CardStatus::DELETE, $cases);
        $this->assertContains(CardStatus::DISPATCH, $cases);
    }
} 