<?php

namespace WechatOfficialAccountCardBundle\Tests\Entity\Embed;

use PHPUnit\Framework\TestCase;
use WechatOfficialAccountCardBundle\Entity\Embed\CardBaseInfo;
use WechatOfficialAccountCardBundle\Entity\Embed\CardDateInfo;
use WechatOfficialAccountCardBundle\Enum\CardColor;
use WechatOfficialAccountCardBundle\Enum\CodeType;
use WechatOfficialAccountCardBundle\Enum\DateType;

class CardBaseInfoTest extends TestCase
{
    private CardBaseInfo $baseInfo;
    private CardDateInfo $dateInfo;

    protected function setUp(): void
    {
        parent::setUp();

        $this->dateInfo = new CardDateInfo();
        $this->dateInfo->setType(DateType::DATE_TYPE_FIX_TIME_RANGE);
        $this->dateInfo->setBeginTimestamp(time());
        $this->dateInfo->setEndTimestamp(time() + 86400 * 30); // 30天后

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
        $this->baseInfo->setDateInfo($this->dateInfo);
    }

    public function testGetterSetterMethods(): void
    {
        // 测试 LogoUrl
        $this->assertEquals('https://example.com/logo.png', $this->baseInfo->getLogoUrl());
        $this->baseInfo->setLogoUrl('https://example.com/new-logo.png');
        $this->assertEquals('https://example.com/new-logo.png', $this->baseInfo->getLogoUrl());

        // 测试 BrandName
        $this->assertEquals('测试品牌', $this->baseInfo->getBrandName());
        $this->baseInfo->setBrandName('新品牌');
        $this->assertEquals('新品牌', $this->baseInfo->getBrandName());

        // 测试 CodeType
        $this->assertEquals(CodeType::CODE_TYPE_QRCODE, $this->baseInfo->getCodeType());
        $this->baseInfo->setCodeType(CodeType::CODE_TYPE_BARCODE);
        $this->assertEquals(CodeType::CODE_TYPE_BARCODE, $this->baseInfo->getCodeType());

        // 测试 Title
        $this->assertEquals('测试卡券', $this->baseInfo->getTitle());
        $this->baseInfo->setTitle('新卡券');
        $this->assertEquals('新卡券', $this->baseInfo->getTitle());

        // 测试 Color
        $this->assertEquals(CardColor::COLOR_010, $this->baseInfo->getColor());
        $this->baseInfo->setColor(CardColor::COLOR_020);
        $this->assertEquals(CardColor::COLOR_020, $this->baseInfo->getColor());

        // 测试 Notice
        $this->assertEquals('使用提醒', $this->baseInfo->getNotice());
        $this->baseInfo->setNotice('新使用提醒');
        $this->assertEquals('新使用提醒', $this->baseInfo->getNotice());

        // 测试 Description
        $this->assertEquals('使用说明详情', $this->baseInfo->getDescription());
        $this->baseInfo->setDescription('新使用说明详情');
        $this->assertEquals('新使用说明详情', $this->baseInfo->getDescription());

        // 测试 Quantity
        $this->assertEquals(1000, $this->baseInfo->getQuantity());
        $this->baseInfo->setQuantity(2000);
        $this->assertEquals(2000, $this->baseInfo->getQuantity());

        // 测试 UseLimit
        $this->assertEquals(1, $this->baseInfo->getUseLimit());
        $this->baseInfo->setUseLimit(2);
        $this->assertEquals(2, $this->baseInfo->getUseLimit());

        // 测试 GetLimit
        $this->assertEquals(1, $this->baseInfo->getGetLimit());
        $this->baseInfo->setGetLimit(2);
        $this->assertEquals(2, $this->baseInfo->getGetLimit());

        // 测试 CanShare
        $this->assertTrue($this->baseInfo->isCanShare());
        $this->baseInfo->setCanShare(false);
        $this->assertFalse($this->baseInfo->isCanShare());

        // 测试 CanGiveFriend
        $this->assertTrue($this->baseInfo->isCanGiveFriend());
        $this->baseInfo->setCanGiveFriend(false);
        $this->assertFalse($this->baseInfo->isCanGiveFriend());

        // 测试 ServicePhone
        $this->assertEquals('13800138000', $this->baseInfo->getServicePhone());
        $this->baseInfo->setServicePhone('13900139000');
        $this->assertEquals('13900139000', $this->baseInfo->getServicePhone());

        // 测试 DateInfo
        $this->assertSame($this->dateInfo, $this->baseInfo->getDateInfo());
        $newDateInfo = new CardDateInfo();
        $this->baseInfo->setDateInfo($newDateInfo);
        $this->assertSame($newDateInfo, $this->baseInfo->getDateInfo());
    }

    public function testNullableFields(): void
    {
        // 测试可空字段
        $baseInfo = new CardBaseInfo();

        $baseInfo->setServicePhone(null);
        $this->assertNull($baseInfo->getServicePhone());
    }

    public function testFluidInterfaces(): void
    {
        $baseInfo = new CardBaseInfo();

        // 测试流畅接口
        $this->assertSame($baseInfo, $baseInfo->setLogoUrl('https://example.com/logo.png'));
        $this->assertSame($baseInfo, $baseInfo->setBrandName('测试品牌'));
        $this->assertSame($baseInfo, $baseInfo->setTitle('测试卡券'));
        $this->assertSame($baseInfo, $baseInfo->setNotice('使用提醒'));
        $this->assertSame($baseInfo, $baseInfo->setDescription('使用说明详情'));
        $this->assertSame($baseInfo, $baseInfo->setCanShare(true));
        $this->assertSame($baseInfo, $baseInfo->setCanGiveFriend(true));
        $this->assertSame($baseInfo, $baseInfo->setServicePhone('13800138000'));
    }

    public function testWithValidationBoundaries(): void
    {
        $baseInfo = new CardBaseInfo();

        // 测试各字段的边界值
        // 字符串长度测试
        $baseInfo->setTitle(str_repeat('a', 27)); // 最大长度27
        $this->assertEquals(str_repeat('a', 27), $baseInfo->getTitle());

        $baseInfo->setBrandName(str_repeat('a', 36)); // 最大长度36
        $this->assertEquals(str_repeat('a', 36), $baseInfo->getBrandName());

        // 数值测试
        $baseInfo->setQuantity(0);
        $this->assertEquals(0, $baseInfo->getQuantity());

        $baseInfo->setQuantity(PHP_INT_MAX);
        $this->assertEquals(PHP_INT_MAX, $baseInfo->getQuantity());

        $baseInfo->setUseLimit(0);
        $this->assertEquals(0, $baseInfo->getUseLimit());

        $baseInfo->setGetLimit(0);
        $this->assertEquals(0, $baseInfo->getGetLimit());
    }
}
