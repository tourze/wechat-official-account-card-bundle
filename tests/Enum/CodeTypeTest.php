<?php

namespace WechatOfficialAccountCardBundle\Tests\Enum;

use PHPUnit\Framework\TestCase;
use WechatOfficialAccountCardBundle\Enum\CodeType;

class CodeTypeTest extends TestCase
{
    public function testCodeTypeValues(): void
    {
        // 测试所有枚举值是否有效
        $this->assertEquals('CODE_TYPE_TEXT', CodeType::CODE_TYPE_TEXT->value);
        $this->assertEquals('CODE_TYPE_BARCODE', CodeType::CODE_TYPE_BARCODE->value);
        $this->assertEquals('CODE_TYPE_QRCODE', CodeType::CODE_TYPE_QRCODE->value);
        $this->assertEquals('CODE_TYPE_ONLY_QRCODE', CodeType::CODE_TYPE_ONLY_QRCODE->value);
        $this->assertEquals('CODE_TYPE_ONLY_BARCODE', CodeType::CODE_TYPE_ONLY_BARCODE->value);
        $this->assertEquals('CODE_TYPE_NONE', CodeType::CODE_TYPE_NONE->value);
    }
    
    public function testCodeTypeInstances(): void
    {
        // 测试枚举实例是否符合预期
        $this->assertInstanceOf(CodeType::class, CodeType::CODE_TYPE_TEXT);
        $this->assertInstanceOf(CodeType::class, CodeType::CODE_TYPE_BARCODE);
        $this->assertInstanceOf(CodeType::class, CodeType::CODE_TYPE_QRCODE);
        $this->assertInstanceOf(CodeType::class, CodeType::CODE_TYPE_ONLY_QRCODE);
        $this->assertInstanceOf(CodeType::class, CodeType::CODE_TYPE_ONLY_BARCODE);
        $this->assertInstanceOf(CodeType::class, CodeType::CODE_TYPE_NONE);
    }
    
    public function testCodeTypeEquality(): void
    {
        // 测试相同枚举值的实例是否相等
        $this->assertEquals(CodeType::CODE_TYPE_TEXT, CodeType::CODE_TYPE_TEXT);
        $this->assertEquals(CodeType::CODE_TYPE_QRCODE, CodeType::CODE_TYPE_QRCODE);
        
        // 测试不同枚举值的实例是否不相等
        $this->assertNotEquals(CodeType::CODE_TYPE_TEXT, CodeType::CODE_TYPE_BARCODE);
        $this->assertNotEquals(CodeType::CODE_TYPE_QRCODE, CodeType::CODE_TYPE_ONLY_QRCODE);
    }
    
    public function testCodeTypeFromString(): void
    {
        // 测试从字符串创建枚举实例
        $this->assertEquals(CodeType::CODE_TYPE_TEXT, CodeType::from('CODE_TYPE_TEXT'));
        $this->assertEquals(CodeType::CODE_TYPE_BARCODE, CodeType::from('CODE_TYPE_BARCODE'));
        $this->assertEquals(CodeType::CODE_TYPE_QRCODE, CodeType::from('CODE_TYPE_QRCODE'));
        $this->assertEquals(CodeType::CODE_TYPE_ONLY_QRCODE, CodeType::from('CODE_TYPE_ONLY_QRCODE'));
        $this->assertEquals(CodeType::CODE_TYPE_ONLY_BARCODE, CodeType::from('CODE_TYPE_ONLY_BARCODE'));
        $this->assertEquals(CodeType::CODE_TYPE_NONE, CodeType::from('CODE_TYPE_NONE'));
        
        // 测试无效字符串应抛出异常
        $this->expectException(\ValueError::class);
        CodeType::from('INVALID_CODE_TYPE');
    }
    
    public function testCodeTypeTryFrom(): void
    {
        // 测试从字符串尝试创建枚举实例
        $this->assertEquals(CodeType::CODE_TYPE_TEXT, CodeType::tryFrom('CODE_TYPE_TEXT'));
        $this->assertEquals(CodeType::CODE_TYPE_QRCODE, CodeType::tryFrom('CODE_TYPE_QRCODE'));
        
        // 测试无效字符串应返回null
        $this->assertNull(CodeType::tryFrom('INVALID_CODE_TYPE'));
    }
    
    public function testCodeTypeNames(): void
    {
        // 测试枚举名称
        $this->assertEquals('CODE_TYPE_TEXT', CodeType::CODE_TYPE_TEXT->name);
        $this->assertEquals('CODE_TYPE_BARCODE', CodeType::CODE_TYPE_BARCODE->name);
        $this->assertEquals('CODE_TYPE_QRCODE', CodeType::CODE_TYPE_QRCODE->name);
        $this->assertEquals('CODE_TYPE_ONLY_QRCODE', CodeType::CODE_TYPE_ONLY_QRCODE->name);
        $this->assertEquals('CODE_TYPE_ONLY_BARCODE', CodeType::CODE_TYPE_ONLY_BARCODE->name);
        $this->assertEquals('CODE_TYPE_NONE', CodeType::CODE_TYPE_NONE->name);
    }
    
    public function testCodeTypeCases(): void
    {
        // 测试所有枚举用例
        $cases = CodeType::cases();
        
        $this->assertCount(6, $cases);
        $this->assertContainsOnlyInstancesOf(CodeType::class, $cases);
        
        // 验证是否包含所有枚举值
        $this->assertContains(CodeType::CODE_TYPE_TEXT, $cases);
        $this->assertContains(CodeType::CODE_TYPE_BARCODE, $cases);
        $this->assertContains(CodeType::CODE_TYPE_QRCODE, $cases);
        $this->assertContains(CodeType::CODE_TYPE_ONLY_QRCODE, $cases);
        $this->assertContains(CodeType::CODE_TYPE_ONLY_BARCODE, $cases);
        $this->assertContains(CodeType::CODE_TYPE_NONE, $cases);
    }
} 