<?php

namespace WechatOfficialAccountCardBundle\Tests\Unit\Enum;

use PHPUnit\Framework\TestCase;
use WechatOfficialAccountCardBundle\Enum\EventTypeEnum;

class EventTypeEnumTest extends TestCase
{
    public function testEventTypeEnumValues(): void
    {
        $this->assertEquals('user_get_card', EventTypeEnum::USER_GET_CARD->value);
        $this->assertEquals('user_del_card', EventTypeEnum::USER_DELETE_CARD->value);
        $this->assertEquals('user_consume_card', EventTypeEnum::USER_CONSUME_CARD->value);
    }
    
    public function testEventTypeEnumInstances(): void
    {
        $this->assertInstanceOf(EventTypeEnum::class, EventTypeEnum::USER_GET_CARD);
        $this->assertInstanceOf(EventTypeEnum::class, EventTypeEnum::USER_DELETE_CARD);
        $this->assertInstanceOf(EventTypeEnum::class, EventTypeEnum::USER_CONSUME_CARD);
    }
    
    public function testEventTypeEnumEquality(): void
    {
        $this->assertEquals(EventTypeEnum::USER_GET_CARD, EventTypeEnum::USER_GET_CARD);
        $this->assertEquals(EventTypeEnum::USER_DELETE_CARD, EventTypeEnum::USER_DELETE_CARD);
        
        $this->assertNotEquals(EventTypeEnum::USER_GET_CARD, EventTypeEnum::USER_DELETE_CARD);
        $this->assertNotEquals(EventTypeEnum::USER_DELETE_CARD, EventTypeEnum::USER_CONSUME_CARD);
    }
    
    public function testEventTypeEnumFromString(): void
    {
        $this->assertEquals(EventTypeEnum::USER_GET_CARD, EventTypeEnum::from('user_get_card'));
        $this->assertEquals(EventTypeEnum::USER_DELETE_CARD, EventTypeEnum::from('user_del_card'));
        $this->assertEquals(EventTypeEnum::USER_CONSUME_CARD, EventTypeEnum::from('user_consume_card'));
        
        $this->expectException(\ValueError::class);
        EventTypeEnum::from('invalid_event');
    }
    
    public function testEventTypeEnumTryFrom(): void
    {
        $this->assertEquals(EventTypeEnum::USER_GET_CARD, EventTypeEnum::tryFrom('user_get_card'));
        $this->assertEquals(EventTypeEnum::USER_DELETE_CARD, EventTypeEnum::tryFrom('user_del_card'));
        $this->assertEquals(EventTypeEnum::USER_CONSUME_CARD, EventTypeEnum::tryFrom('user_consume_card'));
        
        $this->assertNull(EventTypeEnum::tryFrom('invalid_event'));
    }
    
    public function testEventTypeEnumNames(): void
    {
        $this->assertEquals('USER_GET_CARD', EventTypeEnum::USER_GET_CARD->name);
        $this->assertEquals('USER_DELETE_CARD', EventTypeEnum::USER_DELETE_CARD->name);
        $this->assertEquals('USER_CONSUME_CARD', EventTypeEnum::USER_CONSUME_CARD->name);
    }
    
    public function testEventTypeEnumCases(): void
    {
        $cases = EventTypeEnum::cases();
        
        $this->assertCount(3, $cases);
        $this->assertContainsOnlyInstancesOf(EventTypeEnum::class, $cases);
        
        $this->assertContains(EventTypeEnum::USER_GET_CARD, $cases);
        $this->assertContains(EventTypeEnum::USER_DELETE_CARD, $cases);
        $this->assertContains(EventTypeEnum::USER_CONSUME_CARD, $cases);
    }
    
    public function testGetLabel(): void
    {
        $this->assertEquals('用户领取卡券', EventTypeEnum::USER_GET_CARD->getLabel());
        $this->assertEquals('用户删除卡券', EventTypeEnum::USER_DELETE_CARD->getLabel());
        $this->assertEquals('用户核销卡券', EventTypeEnum::USER_CONSUME_CARD->getLabel());
    }
    
}