<?php

namespace WechatOfficialAccountCardBundle\Tests\Enum;

use PHPUnit\Framework\TestCase;
use WechatOfficialAccountCardBundle\Enum\CardColor;

class CardColorTest extends TestCase
{
    public function testCardColorValues(): void
    {
        // 测试所有枚举值是否有效
        $this->assertEquals('#63b359', CardColor::COLOR_010->value); // 淡绿色
        $this->assertEquals('#2c9f67', CardColor::COLOR_020->value); // 深绿色
        $this->assertEquals('#509fc9', CardColor::COLOR_030->value); // 浅蓝色
        $this->assertEquals('#5885cf', CardColor::COLOR_040->value); // 蓝色
        $this->assertEquals('#9062c0', CardColor::COLOR_050->value); // 紫色
        $this->assertEquals('#d09a45', CardColor::COLOR_060->value); // 棕色
        $this->assertEquals('#e4b138', CardColor::COLOR_070->value); // 黄色
        $this->assertEquals('#ee903c', CardColor::COLOR_080->value); // 橙色
        $this->assertEquals('#f08500', CardColor::COLOR_081->value); // 橙色
        $this->assertEquals('#a9d92d', CardColor::COLOR_082->value); // 绿色
        $this->assertEquals('#dd6549', CardColor::COLOR_090->value); // 红色
        $this->assertEquals('#cc463d', CardColor::COLOR_100->value); // 深红色
        $this->assertEquals('#cf3e36', CardColor::COLOR_101->value); // 深红色
        $this->assertEquals('#5E6671', CardColor::COLOR_102->value); // 灰色
    }
    
    public function testCardColorInstances(): void
    {
        // 测试枚举实例是否符合预期
        $this->assertInstanceOf(CardColor::class, CardColor::COLOR_010);
        $this->assertInstanceOf(CardColor::class, CardColor::COLOR_020);
        $this->assertInstanceOf(CardColor::class, CardColor::COLOR_030);
        $this->assertInstanceOf(CardColor::class, CardColor::COLOR_040);
        $this->assertInstanceOf(CardColor::class, CardColor::COLOR_050);
        $this->assertInstanceOf(CardColor::class, CardColor::COLOR_060);
        $this->assertInstanceOf(CardColor::class, CardColor::COLOR_070);
        $this->assertInstanceOf(CardColor::class, CardColor::COLOR_080);
        $this->assertInstanceOf(CardColor::class, CardColor::COLOR_081);
        $this->assertInstanceOf(CardColor::class, CardColor::COLOR_082);
        $this->assertInstanceOf(CardColor::class, CardColor::COLOR_090);
        $this->assertInstanceOf(CardColor::class, CardColor::COLOR_100);
        $this->assertInstanceOf(CardColor::class, CardColor::COLOR_101);
        $this->assertInstanceOf(CardColor::class, CardColor::COLOR_102);
    }
    
    public function testCardColorEquality(): void
    {
        // 测试相同枚举值的实例是否相等
        $this->assertEquals(CardColor::COLOR_010, CardColor::COLOR_010);
        $this->assertEquals(CardColor::COLOR_050, CardColor::COLOR_050);
        
        // 测试不同枚举值的实例是否不相等
        $this->assertNotEquals(CardColor::COLOR_010, CardColor::COLOR_020);
        $this->assertNotEquals(CardColor::COLOR_080, CardColor::COLOR_081);
    }
    
    public function testCardColorFromString(): void
    {
        // 测试从字符串创建枚举实例
        $this->assertEquals(CardColor::COLOR_010, CardColor::from('#63b359'));
        $this->assertEquals(CardColor::COLOR_050, CardColor::from('#9062c0'));
        $this->assertEquals(CardColor::COLOR_102, CardColor::from('#5E6671'));
        
        // 测试无效字符串应抛出异常
        $this->expectException(\ValueError::class);
        CardColor::from('#000000');
    }
    
    public function testCardColorTryFrom(): void
    {
        // 测试从字符串尝试创建枚举实例
        $this->assertEquals(CardColor::COLOR_010, CardColor::tryFrom('#63b359'));
        $this->assertEquals(CardColor::COLOR_050, CardColor::tryFrom('#9062c0'));
        
        // 测试无效字符串应返回null
        $this->assertNull(CardColor::tryFrom('#000000'));
    }
    
    public function testCardColorNames(): void
    {
        // 测试枚举名称
        $this->assertEquals('COLOR_010', CardColor::COLOR_010->name);
        $this->assertEquals('COLOR_020', CardColor::COLOR_020->name);
        $this->assertEquals('COLOR_050', CardColor::COLOR_050->name);
        $this->assertEquals('COLOR_102', CardColor::COLOR_102->name);
    }
    
    public function testCardColorCases(): void
    {
        // 测试所有枚举用例
        $cases = CardColor::cases();
        
        $this->assertCount(14, $cases);
        $this->assertContainsOnlyInstancesOf(CardColor::class, $cases);
        
        // 验证是否包含所有枚举值
        $this->assertContains(CardColor::COLOR_010, $cases);
        $this->assertContains(CardColor::COLOR_020, $cases);
        $this->assertContains(CardColor::COLOR_030, $cases);
        $this->assertContains(CardColor::COLOR_040, $cases);
        $this->assertContains(CardColor::COLOR_050, $cases);
        $this->assertContains(CardColor::COLOR_060, $cases);
        $this->assertContains(CardColor::COLOR_070, $cases);
        $this->assertContains(CardColor::COLOR_080, $cases);
        $this->assertContains(CardColor::COLOR_081, $cases);
        $this->assertContains(CardColor::COLOR_082, $cases);
        $this->assertContains(CardColor::COLOR_090, $cases);
        $this->assertContains(CardColor::COLOR_100, $cases);
        $this->assertContains(CardColor::COLOR_101, $cases);
        $this->assertContains(CardColor::COLOR_102, $cases);
    }
    
    public function testCardColorValidation(): void
    {
        // 测试颜色的格式是否符合预期的16进制颜色格式（#rrggbb）
        foreach (CardColor::cases() as $color) {
            $this->assertMatchesRegularExpression('/#[0-9a-fA-F]{6}/', $color->value);
        }
    }
} 