<?php

namespace WechatOfficialAccountCardBundle\Tests\Request\Distribute;

use PHPUnit\Framework\Attributes\CoversClass;
use HttpClientBundle\Tests\Request\RequestTestCase;
use WechatOfficialAccountBundle\Request\WithAccountRequest;
use WechatOfficialAccountCardBundle\Request\Distribute\GetCodeDepositCountRequest;

/**
 * @internal
 */
#[CoversClass(GetCodeDepositCountRequest::class)]
final class GetCodeDepositCountRequestTest extends RequestTestCase
{
    private GetCodeDepositCountRequest $request;

    protected function setUp(): void
    {
        parent::setUp();

        $this->request = new GetCodeDepositCountRequest();
    }

    public function testExtendsWithAccountRequest(): void
    {
        $this->assertInstanceOf(WithAccountRequest::class, $this->request);
    }

    public function testGetRequestPath(): void
    {
        $this->assertEquals('https://api.weixin.qq.com/card/code/getdepositcount', $this->request->getRequestPath());
    }

    public function testRequestInstantiation(): void
    {
        $this->assertInstanceOf(GetCodeDepositCountRequest::class, $this->request);
    }

    public function testSetCardId(): void
    {
        $cardId = 'test_card_id_12345';
        $this->request->setCardId($cardId);

        $this->assertEquals($cardId, $this->request->getCardId());
    }

    public function testGetRequestOptions(): void
    {
        $cardId = 'test_card_001';
        $this->request->setCardId($cardId);

        $options = $this->request->getRequestOptions();

        $this->assertNotNull($options);
        $this->assertArrayHasKey('json', $options);
        $json = $options['json'];
        $this->assertIsArray($json);
        $this->assertEquals($cardId, $json['card_id']);
    }

    public function testGetRequestOptionsWithDifferentCardIds(): void
    {
        // 测试短ID
        $shortCardId = 'card123';
        $this->request->setCardId($shortCardId);
        $options = $this->request->getRequestOptions();
        $this->assertNotNull($options);
        $this->assertArrayHasKey('json', $options);
        $json = $options['json'];
        $this->assertIsArray($json);
        $this->assertEquals($shortCardId, $json['card_id']);

        // 测试长ID
        $longCardId = 'card_very_long_id_with_underscores_and_numbers_12345';
        $this->request->setCardId($longCardId);
        $options = $this->request->getRequestOptions();
        $this->assertNotNull($options);
        $this->assertArrayHasKey('json', $options);
        $json = $options['json'];
        $this->assertIsArray($json);
        $this->assertEquals($longCardId, $json['card_id']);

        // 测试包含特殊字符的ID
        $specialCardId = 'card-with-dashes_and_underscores.123';
        $this->request->setCardId($specialCardId);
        $options = $this->request->getRequestOptions();
        $this->assertNotNull($options);
        $this->assertArrayHasKey('json', $options);
        $json = $options['json'];
        $this->assertIsArray($json);
        $this->assertEquals($specialCardId, $json['card_id']);
    }

    public function testGetRequestOptionsStructure(): void
    {
        $this->request->setCardId('test_card');

        $options = $this->request->getRequestOptions();

        $this->assertNotNull($options);
        $this->assertCount(1, $options);
        $this->assertArrayHasKey('json', $options);

        $json = $options['json'];
        $this->assertIsArray($json);
        $this->assertArrayHasKey('card_id', $json);
        $this->assertSame('test_card', $json['card_id']);
    }
}
