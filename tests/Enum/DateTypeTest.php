<?php

namespace WechatOfficialAccountCardBundle\Tests\Enum;

use PHPUnit\Framework\TestCase;
use WechatOfficialAccountCardBundle\Enum\DateType;

class DateTypeTest extends TestCase
{
    public function testDateTypeValues(): void
    {
        // 测试所有枚举值是否有效
        $this->assertEquals('DATE_TYPE_FIX_TIME_RANGE', DateType::DATE_TYPE_FIX_TIME_RANGE->value);
        $this->assertEquals('DATE_TYPE_FIX_TERM', DateType::DATE_TYPE_FIX_TERM->value);
        $this->assertEquals('DATE_TYPE_PERMANENT', DateType::DATE_TYPE_PERMANENT->value);
    }
    
    public function testDateTypeInstances(): void
    {
        // 测试枚举实例是否符合预期
        $this->assertInstanceOf(DateType::class, DateType::DATE_TYPE_FIX_TIME_RANGE);
        $this->assertInstanceOf(DateType::class, DateType::DATE_TYPE_FIX_TERM);
        $this->assertInstanceOf(DateType::class, DateType::DATE_TYPE_PERMANENT);
    }
    
    public function testDateTypeEquality(): void
    {
        // 测试相同枚举值的实例是否相等
        $this->assertEquals(DateType::DATE_TYPE_FIX_TIME_RANGE, DateType::DATE_TYPE_FIX_TIME_RANGE);
        $this->assertEquals(DateType::DATE_TYPE_FIX_TERM, DateType::DATE_TYPE_FIX_TERM);
        $this->assertEquals(DateType::DATE_TYPE_PERMANENT, DateType::DATE_TYPE_PERMANENT);
        
        // 测试不同枚举值的实例是否不相等
        $this->assertNotEquals(DateType::DATE_TYPE_FIX_TIME_RANGE, DateType::DATE_TYPE_FIX_TERM);
        $this->assertNotEquals(DateType::DATE_TYPE_FIX_TERM, DateType::DATE_TYPE_PERMANENT);
        $this->assertNotEquals(DateType::DATE_TYPE_PERMANENT, DateType::DATE_TYPE_FIX_TIME_RANGE);
    }
    
    public function testDateTypeFromString(): void
    {
        // 测试从字符串创建枚举实例
        $this->assertEquals(DateType::DATE_TYPE_FIX_TIME_RANGE, DateType::from('DATE_TYPE_FIX_TIME_RANGE'));
        $this->assertEquals(DateType::DATE_TYPE_FIX_TERM, DateType::from('DATE_TYPE_FIX_TERM'));
        $this->assertEquals(DateType::DATE_TYPE_PERMANENT, DateType::from('DATE_TYPE_PERMANENT'));
        
        // 测试无效字符串应抛出异常
        $this->expectException(\ValueError::class);
        DateType::from('INVALID_DATE_TYPE');
    }
    
    public function testDateTypeTryFrom(): void
    {
        // 测试从字符串尝试创建枚举实例
        $this->assertEquals(DateType::DATE_TYPE_FIX_TIME_RANGE, DateType::tryFrom('DATE_TYPE_FIX_TIME_RANGE'));
        $this->assertEquals(DateType::DATE_TYPE_FIX_TERM, DateType::tryFrom('DATE_TYPE_FIX_TERM'));
        $this->assertEquals(DateType::DATE_TYPE_PERMANENT, DateType::tryFrom('DATE_TYPE_PERMANENT'));
        
        // 测试无效字符串应返回null
        $this->assertNull(DateType::tryFrom('INVALID_DATE_TYPE'));
    }
    
    public function testDateTypeNames(): void
    {
        // 测试枚举名称
        $this->assertEquals('DATE_TYPE_FIX_TIME_RANGE', DateType::DATE_TYPE_FIX_TIME_RANGE->name);
        $this->assertEquals('DATE_TYPE_FIX_TERM', DateType::DATE_TYPE_FIX_TERM->name);
        $this->assertEquals('DATE_TYPE_PERMANENT', DateType::DATE_TYPE_PERMANENT->name);
    }
    
    public function testDateTypeCases(): void
    {
        // 测试所有枚举用例
        $cases = DateType::cases();
        
        $this->assertCount(3, $cases);
        $this->assertContainsOnlyInstancesOf(DateType::class, $cases);
        
        // 验证是否包含所有枚举值
        $this->assertContains(DateType::DATE_TYPE_FIX_TIME_RANGE, $cases);
        $this->assertContains(DateType::DATE_TYPE_FIX_TERM, $cases);
        $this->assertContains(DateType::DATE_TYPE_PERMANENT, $cases);
    }
    
    public function testDateTypeValidation(): void
    {
        // 测试日期类型的使用场景
        $fixTimeRange = DateType::DATE_TYPE_FIX_TIME_RANGE;
        $fixTerm = DateType::DATE_TYPE_FIX_TERM;
        $permanent = DateType::DATE_TYPE_PERMANENT;
        
        // 日期类型正确转换为字符串
        $this->assertEquals('DATE_TYPE_FIX_TIME_RANGE', $fixTimeRange->value);
        $this->assertEquals('DATE_TYPE_FIX_TERM', $fixTerm->value);
        $this->assertEquals('DATE_TYPE_PERMANENT', $permanent->value);
    }
} 