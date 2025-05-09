<?php

namespace WechatOfficialAccountCardBundle\Tests\Entity\Embed;

use PHPUnit\Framework\TestCase;
use WechatOfficialAccountCardBundle\Entity\Embed\CardDateInfo;
use WechatOfficialAccountCardBundle\Enum\DateType;

class CardDateInfoTest extends TestCase
{
    public function testFixedTimeRange(): void
    {
        $dateInfo = new CardDateInfo();
        $dateInfo->setType(DateType::DATE_TYPE_FIX_TIME_RANGE);
        
        $beginTime = time();
        $endTime = time() + 86400 * 30; // 30天后
        
        $dateInfo->setBeginTimestamp($beginTime);
        $dateInfo->setEndTimestamp($endTime);
        
        $this->assertEquals(DateType::DATE_TYPE_FIX_TIME_RANGE, $dateInfo->getType());
        $this->assertEquals($beginTime, $dateInfo->getBeginTimestamp());
        $this->assertEquals($endTime, $dateInfo->getEndTimestamp());
        $this->assertNull($dateInfo->getFixedTerm());
        $this->assertNull($dateInfo->getFixedBeginTerm());
    }
    
    public function testFixedTerm(): void
    {
        $dateInfo = new CardDateInfo();
        $dateInfo->setType(DateType::DATE_TYPE_FIX_TERM);
        
        $fixedTerm = 30; // 30天内有效
        $fixedBeginTerm = 0; // 领取后立即生效
        
        $dateInfo->setFixedTerm($fixedTerm);
        $dateInfo->setFixedBeginTerm($fixedBeginTerm);
        
        $this->assertEquals(DateType::DATE_TYPE_FIX_TERM, $dateInfo->getType());
        $this->assertEquals($fixedTerm, $dateInfo->getFixedTerm());
        $this->assertEquals($fixedBeginTerm, $dateInfo->getFixedBeginTerm());
        $this->assertNull($dateInfo->getBeginTimestamp());
        $this->assertNull($dateInfo->getEndTimestamp());
    }
    
    public function testFixedTermWithDelay(): void
    {
        $dateInfo = new CardDateInfo();
        $dateInfo->setType(DateType::DATE_TYPE_FIX_TERM);
        
        $fixedTerm = 60; // 60天内有效
        $fixedBeginTerm = 7; // 领取后7天开始生效
        
        $dateInfo->setFixedTerm($fixedTerm);
        $dateInfo->setFixedBeginTerm($fixedBeginTerm);
        
        $this->assertEquals(DateType::DATE_TYPE_FIX_TERM, $dateInfo->getType());
        $this->assertEquals($fixedTerm, $dateInfo->getFixedTerm());
        $this->assertEquals($fixedBeginTerm, $dateInfo->getFixedBeginTerm());
    }
    
    public function testPermanent(): void
    {
        $dateInfo = new CardDateInfo();
        $dateInfo->setType(DateType::DATE_TYPE_PERMANENT);
        
        $this->assertEquals(DateType::DATE_TYPE_PERMANENT, $dateInfo->getType());
        $this->assertNull($dateInfo->getBeginTimestamp());
        $this->assertNull($dateInfo->getEndTimestamp());
        $this->assertNull($dateInfo->getFixedTerm());
        $this->assertNull($dateInfo->getFixedBeginTerm());
    }
    
    public function testGetterSetterMethods(): void
    {
        $dateInfo = new CardDateInfo();
        
        // 测试类型
        $dateInfo->setType(DateType::DATE_TYPE_FIX_TIME_RANGE);
        $this->assertEquals(DateType::DATE_TYPE_FIX_TIME_RANGE, $dateInfo->getType());
        
        $dateInfo->setType(DateType::DATE_TYPE_FIX_TERM);
        $this->assertEquals(DateType::DATE_TYPE_FIX_TERM, $dateInfo->getType());
        
        $dateInfo->setType(DateType::DATE_TYPE_PERMANENT);
        $this->assertEquals(DateType::DATE_TYPE_PERMANENT, $dateInfo->getType());
        
        // 测试时间戳
        $beginTime = time();
        $endTime = time() + 86400 * 30;
        
        $dateInfo->setBeginTimestamp($beginTime);
        $this->assertEquals($beginTime, $dateInfo->getBeginTimestamp());
        
        $dateInfo->setEndTimestamp($endTime);
        $this->assertEquals($endTime, $dateInfo->getEndTimestamp());
        
        // 测试固定时长
        $dateInfo->setFixedTerm(30);
        $this->assertEquals(30, $dateInfo->getFixedTerm());
        
        $dateInfo->setFixedBeginTerm(7);
        $this->assertEquals(7, $dateInfo->getFixedBeginTerm());
    }
    
    public function testNullableFields(): void
    {
        $dateInfo = new CardDateInfo();
        
        // 测试所有可空字段
        $dateInfo->setBeginTimestamp(null);
        $this->assertNull($dateInfo->getBeginTimestamp());
        
        $dateInfo->setEndTimestamp(null);
        $this->assertNull($dateInfo->getEndTimestamp());
        
        $dateInfo->setFixedTerm(null);
        $this->assertNull($dateInfo->getFixedTerm());
        
        $dateInfo->setFixedBeginTerm(null);
        $this->assertNull($dateInfo->getFixedBeginTerm());
    }
    
    public function testFluidInterfaces(): void
    {
        $dateInfo = new CardDateInfo();
        
        // 测试流畅接口
        $this->assertSame($dateInfo, $dateInfo->setType(DateType::DATE_TYPE_FIX_TIME_RANGE));
        $this->assertSame($dateInfo, $dateInfo->setBeginTimestamp(time()));
        $this->assertSame($dateInfo, $dateInfo->setEndTimestamp(time() + 86400));
        $this->assertSame($dateInfo, $dateInfo->setFixedTerm(30));
        $this->assertSame($dateInfo, $dateInfo->setFixedBeginTerm(0));
    }
    
    public function testDefaultValues(): void
    {
        $dateInfo = new CardDateInfo();
        
        // 测试默认值
        $this->assertEquals(DateType::DATE_TYPE_FIX_TIME_RANGE, $dateInfo->getType());
        $this->assertNull($dateInfo->getBeginTimestamp());
        $this->assertNull($dateInfo->getEndTimestamp());
        $this->assertNull($dateInfo->getFixedTerm());
        $this->assertNull($dateInfo->getFixedBeginTerm());
    }
} 