<?php

namespace WechatOfficialAccountCardBundle\Tests\Request\Basic;

use PHPUnit\Framework\Attributes\CoversClass;
use HttpClientBundle\Tests\Request\RequestTestCase;
use WechatOfficialAccountBundle\Request\WithAccountRequest;
use WechatOfficialAccountCardBundle\Enum\CardColor;
use WechatOfficialAccountCardBundle\Enum\CardType;
use WechatOfficialAccountCardBundle\Enum\CodeType;
use WechatOfficialAccountCardBundle\Enum\DateType;
use WechatOfficialAccountCardBundle\Request\Basic\CreateRequest;

/**
 * @internal
 */
#[CoversClass(CreateRequest::class)]
final class CreateRequestTest extends RequestTestCase
{
    private CreateRequest $request;

    protected function setUp(): void
    {
        parent::setUp();

        $this->request = new CreateRequest();
    }

    public function testExtendsWithAccountRequest(): void
    {
        $this->assertInstanceOf(WithAccountRequest::class, $this->request);
    }

    public function testGetRequestPath(): void
    {
        $this->assertEquals('card/create', $this->request->getRequestPath());
    }

    public function testGetRequestOptionsWithFixTimeRange(): void
    {
        // setter方法返回void，不支持链式调用，需要单独调用
        $this->request->setCardType(CardType::GENERAL_COUPON);
        $this->request->setLogoUrl('https://example.com/logo.png');
        $this->request->setBrandName('Test Brand');
        $this->request->setCodeType(CodeType::CODE_TYPE_QRCODE);
        $this->request->setTitle('Test Card');
        $this->request->setColor(CardColor::COLOR_010);
        $this->request->setNotice('Test Notice');
        $this->request->setDescription('Test Description');
        $this->request->setQuantity(1000);
        $this->request->setDateType(DateType::DATE_TYPE_FIX_TIME_RANGE);
        $this->request->setBeginTimestamp(1640995200);
        $this->request->setEndTimestamp(1672531199);
        $this->request->setUseLimit(1);
        $this->request->setGetLimit(1);
        $this->request->setCanShare(true);
        $this->request->setCanGiveFriend(false);

        $options = $this->request->getRequestOptions();

        $this->assertIsArray($options);

        $this->assertArrayHasKey('card', $options);
        $card = $options['card'];
        $this->assertIsArray($card);

        $this->assertArrayHasKey('card_type', $card);
        $this->assertEquals(CardType::GENERAL_COUPON->value, $card['card_type']);

        $this->assertArrayHasKey('general_coupon', $card);
        $generalCoupon = $card['general_coupon'];
        $this->assertIsArray($generalCoupon);

        $this->assertArrayHasKey('base_info', $generalCoupon);
        $baseInfo = $generalCoupon['base_info'];
        $this->assertIsArray($baseInfo);

        $this->assertEquals('https://example.com/logo.png', $baseInfo['logo_url']);
        $this->assertEquals('Test Brand', $baseInfo['brand_name']);
        $this->assertEquals(CodeType::CODE_TYPE_QRCODE->value, $baseInfo['code_type']);
        $this->assertEquals('Test Card', $baseInfo['title']);
        $this->assertEquals(CardColor::COLOR_010->value, $baseInfo['color']);
        $this->assertEquals('Test Notice', $baseInfo['notice']);
        $this->assertEquals('Test Description', $baseInfo['description']);
        $this->assertEquals(['quantity' => 1000], $baseInfo['sku']);
        $this->assertEquals(1, $baseInfo['use_limit']);
        $this->assertEquals(1, $baseInfo['get_limit']);
        $this->assertTrue($baseInfo['can_share']);
        $this->assertFalse($baseInfo['can_give_friend']);

        $this->assertArrayHasKey('date_info', $baseInfo);
        $dateInfo = $baseInfo['date_info'];
        $this->assertIsArray($dateInfo);

        $this->assertEquals(DateType::DATE_TYPE_FIX_TIME_RANGE->value, $dateInfo['type']);
        $this->assertEquals(1640995200, $dateInfo['begin_timestamp']);
        $this->assertEquals(1672531199, $dateInfo['end_timestamp']);
        $this->assertArrayNotHasKey('fixed_term', $dateInfo);
        $this->assertArrayNotHasKey('fixed_begin_term', $dateInfo);
    }

    public function testGetRequestOptionsWithFixTerm(): void
    {
        // setter方法返回void，不支持链式调用，需要单独调用
        $this->request->setCardType(CardType::GENERAL_COUPON);
        $this->request->setLogoUrl('https://example.com/logo.png');
        $this->request->setBrandName('Test Brand');
        $this->request->setCodeType(CodeType::CODE_TYPE_QRCODE);
        $this->request->setTitle('Test Card');
        $this->request->setColor(CardColor::COLOR_010);
        $this->request->setNotice('Test Notice');
        $this->request->setDescription('Test Description');
        $this->request->setQuantity(1000);
        $this->request->setDateType(DateType::DATE_TYPE_FIX_TERM);
        $this->request->setFixedTerm(30);
        $this->request->setFixedBeginTerm(1);
        $this->request->setUseLimit(1);
        $this->request->setGetLimit(1);

        $options = $this->request->getRequestOptions();

        $card = $options['card'];
        $this->assertIsArray($card);
        $generalCoupon = $card['general_coupon'];
        $this->assertIsArray($generalCoupon);
        $baseInfo = $generalCoupon['base_info'];
        $this->assertIsArray($baseInfo);

        $this->assertArrayHasKey('date_info', $baseInfo);
        $dateInfo = $baseInfo['date_info'];
        $this->assertIsArray($dateInfo);

        $this->assertEquals(DateType::DATE_TYPE_FIX_TERM->value, $dateInfo['type']);
        $this->assertEquals(30, $dateInfo['fixed_term']);
        $this->assertEquals(1, $dateInfo['fixed_begin_term']);
        $this->assertArrayNotHasKey('begin_timestamp', $dateInfo);
        $this->assertArrayNotHasKey('end_timestamp', $dateInfo);
    }

    public function testGetRequestOptionsWithPermanent(): void
    {
        // setter方法返回void，不支持链式调用，需要单独调用
        $this->request->setCardType(CardType::GENERAL_COUPON);
        $this->request->setLogoUrl('https://example.com/logo.png');
        $this->request->setBrandName('Test Brand');
        $this->request->setCodeType(CodeType::CODE_TYPE_QRCODE);
        $this->request->setTitle('Test Card');
        $this->request->setColor(CardColor::COLOR_010);
        $this->request->setNotice('Test Notice');
        $this->request->setDescription('Test Description');
        $this->request->setQuantity(1000);
        $this->request->setDateType(DateType::DATE_TYPE_PERMANENT);
        $this->request->setUseLimit(1);
        $this->request->setGetLimit(1);

        $options = $this->request->getRequestOptions();

        $card = $options['card'];
        $this->assertIsArray($card);
        $generalCoupon = $card['general_coupon'];
        $this->assertIsArray($generalCoupon);
        $baseInfo = $generalCoupon['base_info'];
        $this->assertIsArray($baseInfo);

        $this->assertArrayHasKey('date_info', $baseInfo);
        $dateInfo = $baseInfo['date_info'];
        $this->assertIsArray($dateInfo);

        $this->assertEquals(DateType::DATE_TYPE_PERMANENT->value, $dateInfo['type']);
        $this->assertArrayNotHasKey('begin_timestamp', $dateInfo);
        $this->assertArrayNotHasKey('end_timestamp', $dateInfo);
        $this->assertArrayNotHasKey('fixed_term', $dateInfo);
        $this->assertArrayNotHasKey('fixed_begin_term', $dateInfo);
    }

    public function testSetterMethodCalls(): void
    {
        // setter方法返回void，不支持链式调用，需要单独调用
        $this->request->setCardType(CardType::GENERAL_COUPON);
        $this->request->setLogoUrl('https://example.com/logo.png');
        $this->request->setBrandName('Test Brand');
        $this->request->setCodeType(CodeType::CODE_TYPE_QRCODE);
        $this->request->setTitle('Test Card');
        $this->request->setColor(CardColor::COLOR_010);
        $this->request->setNotice('Test Notice');
        $this->request->setDescription('Test Description');
        $this->request->setQuantity(1000);
        $this->request->setDateType(DateType::DATE_TYPE_PERMANENT);
        $this->request->setUseLimit(1);
        $this->request->setGetLimit(1);
        $this->request->setCanShare(false);
        $this->request->setCanGiveFriend(true);

        $options = $this->request->getRequestOptions();

        $card = $options['card'];
        $this->assertIsArray($card);
        $generalCoupon = $card['general_coupon'];
        $this->assertIsArray($generalCoupon);
        $baseInfo = $generalCoupon['base_info'];
        $this->assertIsArray($baseInfo);

        $this->assertEquals('https://example.com/logo.png', $baseInfo['logo_url']);
        $this->assertEquals('Test Brand', $baseInfo['brand_name']);
        $this->assertEquals('Test Card', $baseInfo['title']);

        $sku = $baseInfo['sku'];
        $this->assertIsArray($sku);
        $this->assertEquals(1000, $sku['quantity']);

        $dateInfo = $baseInfo['date_info'];
        $this->assertIsArray($dateInfo);
        $this->assertEquals(DateType::DATE_TYPE_PERMANENT->value, $dateInfo['type']);

        $this->assertEquals(1, $baseInfo['use_limit']);
        $this->assertEquals(1, $baseInfo['get_limit']);
        $this->assertFalse($baseInfo['can_share']);
        $this->assertTrue($baseInfo['can_give_friend']);
    }
}
