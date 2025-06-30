<?php

namespace WechatOfficialAccountCardBundle\Tests\Unit\Enum;

use PHPUnit\Framework\TestCase;
use WechatOfficialAccountCardBundle\Enum\CardStateEnum;

class CardStateEnumTest extends TestCase
{
    public function testCardStateEnumValues(): void
    {
        $this->assertEquals(0, CardStateEnum::NORMAL->value);
        $this->assertEquals(1, CardStateEnum::GATHER->value);
        $this->assertEquals(-1, CardStateEnum::DELETE->value);
        $this->assertEquals(2, CardStateEnum::VERIFIED->value);
    }
    
    public function testCardStateEnumInstances(): void
    {
        $this->assertInstanceOf(CardStateEnum::class, CardStateEnum::NORMAL);
        $this->assertInstanceOf(CardStateEnum::class, CardStateEnum::GATHER);
        $this->assertInstanceOf(CardStateEnum::class, CardStateEnum::DELETE);
        $this->assertInstanceOf(CardStateEnum::class, CardStateEnum::VERIFIED);
    }
    
    public function testCardStateEnumEquality(): void
    {
        $this->assertEquals(CardStateEnum::NORMAL, CardStateEnum::NORMAL);
        $this->assertEquals(CardStateEnum::GATHER, CardStateEnum::GATHER);
        
        $this->assertNotEquals(CardStateEnum::NORMAL, CardStateEnum::GATHER);
        $this->assertNotEquals(CardStateEnum::DELETE, CardStateEnum::VERIFIED);
    }
    
    public function testCardStateEnumFromInt(): void
    {
        $this->assertEquals(CardStateEnum::NORMAL, CardStateEnum::from(0));
        $this->assertEquals(CardStateEnum::GATHER, CardStateEnum::from(1));
        $this->assertEquals(CardStateEnum::DELETE, CardStateEnum::from(-1));
        $this->assertEquals(CardStateEnum::VERIFIED, CardStateEnum::from(2));
        
        $this->expectException(\ValueError::class);
        CardStateEnum::from(999);
    }
    
    public function testCardStateEnumTryFrom(): void
    {
        $this->assertEquals(CardStateEnum::NORMAL, CardStateEnum::tryFrom(0));
        $this->assertEquals(CardStateEnum::GATHER, CardStateEnum::tryFrom(1));
        $this->assertEquals(CardStateEnum::DELETE, CardStateEnum::tryFrom(-1));
        $this->assertEquals(CardStateEnum::VERIFIED, CardStateEnum::tryFrom(2));
        
        $this->assertNull(CardStateEnum::tryFrom(999));
    }
    
    public function testCardStateEnumNames(): void
    {
        $this->assertEquals('NORMAL', CardStateEnum::NORMAL->name);
        $this->assertEquals('GATHER', CardStateEnum::GATHER->name);
        $this->assertEquals('DELETE', CardStateEnum::DELETE->name);
        $this->assertEquals('VERIFIED', CardStateEnum::VERIFIED->name);
    }
    
    public function testCardStateEnumCases(): void
    {
        $cases = CardStateEnum::cases();
        
        $this->assertCount(4, $cases);
        $this->assertContainsOnlyInstancesOf(CardStateEnum::class, $cases);
        
        $this->assertContains(CardStateEnum::NORMAL, $cases);
        $this->assertContains(CardStateEnum::GATHER, $cases);
        $this->assertContains(CardStateEnum::DELETE, $cases);
        $this->assertContains(CardStateEnum::VERIFIED, $cases);
    }
    
    public function testGetLabel(): void
    {
        $this->assertEquals('待领取', CardStateEnum::NORMAL->getLabel());
        $this->assertEquals('已领取', CardStateEnum::GATHER->getLabel());
        $this->assertEquals('已删除', CardStateEnum::DELETE->getLabel());
        $this->assertEquals('已核销', CardStateEnum::VERIFIED->getLabel());
    }
    
    public function testGetList(): void
    {
        $list = CardStateEnum::getList();
        
        $this->assertCount(4, $list);
        
        $this->assertArrayHasKey(0, $list);
        $this->assertArrayHasKey(1, $list);
        $this->assertArrayHasKey(-1, $list);
        $this->assertArrayHasKey(2, $list);
        
        $this->assertEquals('待领取', $list[0]);
        $this->assertEquals('已领取', $list[1]);
        $this->assertEquals('已删除', $list[-1]);
        $this->assertEquals('已核销', $list[2]);
    }
    
}