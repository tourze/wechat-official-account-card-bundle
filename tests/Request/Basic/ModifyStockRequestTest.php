<?php

namespace WechatOfficialAccountCardBundle\Tests\Request\Basic;

use HttpClientBundle\Test\RequestTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use WechatOfficialAccountBundle\Request\WithAccountRequest;
use WechatOfficialAccountCardBundle\Request\Basic\ModifyStockRequest;

/**
 * @internal
 */
#[CoversClass(ModifyStockRequest::class)]
final class ModifyStockRequestTest extends RequestTestCase
{
    private ModifyStockRequest $request;

    protected function setUp(): void
    {
        parent::setUp();

        $this->request = new ModifyStockRequest();
    }

    public function testInheritance(): void
    {
        $this->assertInstanceOf(WithAccountRequest::class, $this->request);
    }

    public function testGetRequestPath(): void
    {
        $this->assertSame('https://api.weixin.qq.com/card/modifystock', $this->request->getRequestPath());
    }

    public function testCardIdGetterAndSetter(): void
    {
        $cardId = 'test_card_id_123';

        $this->request->setCardId($cardId);

        $this->assertSame($cardId, $this->request->getCardId());
    }

    public function testIncreaseStockValueGetterAndSetter(): void
    {
        $this->assertSame(0, $this->request->getIncreaseStockValue());

        $this->request->setIncreaseStockValue(100);
        $this->assertSame(100, $this->request->getIncreaseStockValue());
    }

    public function testIncreaseStockValueNegativeValueHandling(): void
    {
        $this->request->setIncreaseStockValue(-50);

        $this->assertSame(0, $this->request->getIncreaseStockValue());
    }

    public function testReduceStockValueGetterAndSetter(): void
    {
        $this->assertSame(0, $this->request->getReduceStockValue());

        $this->request->setReduceStockValue(50);
        $this->assertSame(50, $this->request->getReduceStockValue());
    }

    public function testReduceStockValueNegativeValueHandling(): void
    {
        $this->request->setReduceStockValue(-100);

        $this->assertSame(0, $this->request->getReduceStockValue());
    }

    public function testGetRequestOptionsWithCardIdOnly(): void
    {
        $cardId = 'card_test_123';
        $this->request->setCardId($cardId);

        $options = $this->request->getRequestOptions();

        $this->assertIsArray($options);

        $this->assertArrayHasKey('json', $options);
        $json = $options['json'];
        $this->assertIsArray($json);
        $this->assertEquals([
            'json' => [
                'card_id' => $cardId,
            ],
        ], $options);
    }

    public function testGetRequestOptionsWithIncreaseStock(): void
    {
        $cardId = 'card_test_123';
        $increaseValue = 100;

        $this->request->setCardId($cardId);
        $this->request->setIncreaseStockValue($increaseValue);

        $options = $this->request->getRequestOptions();

        $this->assertIsArray($options);

        $this->assertArrayHasKey('json', $options);
        $json = $options['json'];
        $this->assertIsArray($json);
        $this->assertEquals([
            'json' => [
                'card_id' => $cardId,
                'increase_stock_value' => $increaseValue,
            ],
        ], $options);
    }

    public function testGetRequestOptionsWithReduceStock(): void
    {
        $cardId = 'card_test_123';
        $reduceValue = 50;

        $this->request->setCardId($cardId);
        $this->request->setReduceStockValue($reduceValue);

        $options = $this->request->getRequestOptions();

        $this->assertIsArray($options);

        $this->assertArrayHasKey('json', $options);
        $json = $options['json'];
        $this->assertIsArray($json);
        $this->assertEquals([
            'json' => [
                'card_id' => $cardId,
                'reduce_stock_value' => $reduceValue,
            ],
        ], $options);
    }

    public function testGetRequestOptionsWithBothIncreaseAndReduceStock(): void
    {
        $cardId = 'card_test_123';
        $increaseValue = 200;
        $reduceValue = 75;

        $this->request->setCardId($cardId);
        $this->request->setIncreaseStockValue($increaseValue);
        $this->request->setReduceStockValue($reduceValue);

        $options = $this->request->getRequestOptions();

        $this->assertIsArray($options);

        $this->assertArrayHasKey('json', $options);
        $json = $options['json'];
        $this->assertIsArray($json);
        $this->assertEquals([
            'json' => [
                'card_id' => $cardId,
                'increase_stock_value' => $increaseValue,
                'reduce_stock_value' => $reduceValue,
            ],
        ], $options);
    }

    public function testGetRequestOptionsWithZeroValuesExcluded(): void
    {
        $cardId = 'card_test_123';

        $this->request->setCardId($cardId);
        $this->request->setIncreaseStockValue(0);
        $this->request->setReduceStockValue(0);

        $options = $this->request->getRequestOptions();

        $this->assertIsArray($options);

        $this->assertArrayHasKey('json', $options);
        $json = $options['json'];
        $this->assertIsArray($json);
        $this->assertEquals([
            'json' => [
                'card_id' => $cardId,
            ],
        ], $options);
    }

    public function testSetterMethodsReturnVoid(): void
    {
        $this->request->setCardId('test');
        $this->request->setIncreaseStockValue(10);
        $this->request->setReduceStockValue(5);

        // Setters should execute without error and modify state
        $this->assertSame('test', $this->request->getCardId());
        $this->assertSame(10, $this->request->getIncreaseStockValue());
        $this->assertSame(5, $this->request->getReduceStockValue());
    }

    public function testBusinessLogicValidation(): void
    {
        $cardId = 'business_card_456';
        $increaseStock = 1000;
        $reduceStock = 200;

        $this->request->setCardId($cardId);
        $this->request->setIncreaseStockValue($increaseStock);
        $this->request->setReduceStockValue($reduceStock);

        $this->assertSame($cardId, $this->request->getCardId());
        $this->assertSame($increaseStock, $this->request->getIncreaseStockValue());
        $this->assertSame($reduceStock, $this->request->getReduceStockValue());
        $this->assertSame('https://api.weixin.qq.com/card/modifystock', $this->request->getRequestPath());

        $options = $this->request->getRequestOptions();
        $this->assertNotNull($options);
        $this->assertArrayHasKey('json', $options);
        $json = $options['json'];
        $this->assertIsArray($json);
        $this->assertArrayHasKey('increase_stock_value', $json);
        $this->assertArrayHasKey('reduce_stock_value', $json);
    }
}
