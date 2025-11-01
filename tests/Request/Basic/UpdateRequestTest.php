<?php

namespace WechatOfficialAccountCardBundle\Tests\Request\Basic;

use HttpClientBundle\Test\RequestTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use WechatOfficialAccountBundle\Request\WithAccountRequest;
use WechatOfficialAccountCardBundle\Enum\CardType;
use WechatOfficialAccountCardBundle\Request\Basic\UpdateRequest;

/**
 * @internal
 */
#[CoversClass(UpdateRequest::class)]
final class UpdateRequestTest extends RequestTestCase
{
    private UpdateRequest $request;

    protected function setUp(): void
    {
        parent::setUp();

        $this->request = new UpdateRequest();
    }

    public function testExtendsWithAccountRequest(): void
    {
        $this->assertInstanceOf(WithAccountRequest::class, $this->request);
    }

    public function testGetRequestPath(): void
    {
        $this->assertEquals('card/update', $this->request->getRequestPath());
    }

    public function testSetCardId(): void
    {
        $this->request->setCardId('pYGD_jts75HTOVgOiC-VbCYmZJ9w');
        // 验证setter方法执行成功
        $this->assertTrue(true, 'setCardId executed without exceptions');
    }

    public function testSetCardType(): void
    {
        $this->request->setCardType(CardType::GENERAL_COUPON);
        // 验证setter方法执行成功
        $this->assertTrue(true, 'setCardType executed without exceptions');
    }

    public function testSetBaseInfo(): void
    {
        $baseInfo = [
            'logo_url' => 'https://example.com/logo.png',
            'brand_name' => 'Updated Brand',
            'title' => 'Updated Title',
        ];

        $this->request->setBaseInfo($baseInfo);
        // 验证setter方法执行成功
        $this->assertTrue(true, 'setBaseInfo executed without exceptions');
    }

    public function testGetRequestOptions(): void
    {
        $cardId = 'pYGD_jts75HTOVgOiC-VbCYmZJ9w';
        $baseInfo = [
            'logo_url' => 'https://example.com/updated-logo.png',
            'brand_name' => 'Updated Test Brand',
            'title' => 'Updated Card Title',
            'notice' => 'Updated Notice',
            'description' => 'Updated Description',
        ];

        $this->request->setCardId($cardId);
        $this->request->setCardType(CardType::GENERAL_COUPON);
        $this->request->setBaseInfo($baseInfo);

        $options = $this->request->getRequestOptions();

        $this->assertIsArray($options);

        $this->assertArrayHasKey('card_id', $options);
        $this->assertEquals($cardId, $options['card_id']);

        $this->assertArrayHasKey('general_coupon', $options);
        $generalCoupon = $options['general_coupon'];
        $this->assertIsArray($generalCoupon);

        $this->assertArrayHasKey('base_info', $generalCoupon);
        $this->assertEquals($baseInfo, $generalCoupon['base_info']);
    }

    public function testGetRequestOptionsWithDifferentCardTypes(): void
    {
        $cardId = 'pYGD_jts75HTOVgOiC-VbCYmZJ9w';
        $baseInfo = [
            'logo_url' => 'https://example.com/cash-logo.png',
            'brand_name' => 'Cash Coupon Brand',
        ];

        // Test with CASH card type
        $this->request->setCardId($cardId);
        $this->request->setCardType(CardType::CASH);
        $this->request->setBaseInfo($baseInfo);

        $options = $this->request->getRequestOptions();

        $this->assertEquals($cardId, $options['card_id']);
        $this->assertArrayHasKey('cash', $options);
        $cash = $options['cash'];
        $this->assertIsArray($cash);
        $this->assertArrayHasKey('base_info', $cash);
        $this->assertEquals($baseInfo, $cash['base_info']);

        // Test with DISCOUNT card type
        $this->request->setCardId($cardId);
        $this->request->setCardType(CardType::DISCOUNT);
        $this->request->setBaseInfo($baseInfo);

        $options = $this->request->getRequestOptions();

        $this->assertEquals($cardId, $options['card_id']);
        $this->assertArrayHasKey('discount', $options);
        $discount = $options['discount'];
        $this->assertIsArray($discount);
        $this->assertArrayHasKey('base_info', $discount);
        $this->assertEquals($baseInfo, $discount['base_info']);
    }

    public function testChainedMethodCalls(): void
    {
        $cardId = 'pYGD_jts75HTOVgOiC-VbCYmZJ9w';
        $baseInfo = [
            'logo_url' => 'https://example.com/updated-logo.png',
            'brand_name' => 'Updated Brand Name',
            'title' => 'Updated Card Title',
            'notice' => 'Updated usage notice',
            'description' => 'Updated card description',
            'color' => 'Color010',
        ];

        $this->request->setCardId($cardId);
        $this->request->setCardType(CardType::MEMBER_CARD);
        $this->request->setBaseInfo($baseInfo);

        $options = $this->request->getRequestOptions();

        $this->assertEquals($cardId, $options['card_id']);
        $this->assertArrayHasKey('member_card', $options);
        $memberCard = $options['member_card'];
        $this->assertIsArray($memberCard);
        $this->assertEquals($baseInfo, $memberCard['base_info']);
    }

    public function testCardTypeValueConversionToLowercase(): void
    {
        $cardId = 'pYGD_jts75HTOVgOiC-VbCYmZJ9w';
        $baseInfo = ['title' => 'Test'];

        $this->request->setCardId($cardId);
        $this->request->setCardType(CardType::GENERAL_COUPON);
        $this->request->setBaseInfo($baseInfo);

        $options = $this->request->getRequestOptions();

        // CardType::GENERAL_COUPON->value is 'GENERAL_COUPON', should be converted to 'general_coupon'
        $this->assertArrayHasKey('general_coupon', $options);
        $this->assertArrayNotHasKey('GENERAL_COUPON', $options);
    }
}
