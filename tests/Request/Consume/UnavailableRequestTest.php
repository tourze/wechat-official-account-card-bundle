<?php

namespace WechatOfficialAccountCardBundle\Tests\Request\Consume;

use PHPUnit\Framework\Attributes\CoversClass;
use HttpClientBundle\Tests\Request\RequestTestCase;
use WechatOfficialAccountBundle\Request\WithAccountRequest;
use WechatOfficialAccountCardBundle\Request\Consume\UnavailableRequest;

/**
 * @internal
 */
#[CoversClass(UnavailableRequest::class)]
final class UnavailableRequestTest extends RequestTestCase
{
    private UnavailableRequest $request;

    protected function setUp(): void
    {
        parent::setUp();

        $this->request = new UnavailableRequest();
    }

    public function testRequestInstantiation(): void
    {
        $this->assertInstanceOf(UnavailableRequest::class, $this->request);
    }

    public function testExtendsWithAccountRequest(): void
    {
        $this->assertInstanceOf(WithAccountRequest::class, $this->request);
    }

    public function testGetRequestPath(): void
    {
        $this->assertSame('https://api.weixin.qq.com/card/code/unavailable', $this->request->getRequestPath());
    }

    public function testCodeSetterAndGetter(): void
    {
        $code = 'test_code_123';

        $this->request->setCode($code);

        $this->assertSame($code, $this->request->getCode());
    }

    public function testCardIdSetterAndGetterWithValue(): void
    {
        $cardId = 'test_card_id_456';

        $this->request->setCardId($cardId);

        $this->assertSame($cardId, $this->request->getCardId());
    }

    public function testCardIdSetterAndGetterWithNull(): void
    {
        $this->request->setCardId(null);

        $this->assertNull($this->request->getCardId());
    }

    public function testCardIdDefaultValue(): void
    {
        $this->assertNull($this->request->getCardId());
    }

    public function testGetRequestOptionsWithCodeOnly(): void
    {
        $code = 'test_code_789';
        $this->request->setCode($code);

        $options = $this->request->getRequestOptions();

        $this->assertIsArray($options);

        $this->assertArrayHasKey('json', $options);
        $jsonData = $options['json'];
        $this->assertIsArray($jsonData);
        $this->assertArrayHasKey('code', $jsonData);
        $this->assertSame($code, $jsonData['code']);
        $this->assertArrayNotHasKey('card_id', $jsonData);
    }

    public function testGetRequestOptionsWithCodeAndCardId(): void
    {
        $code = 'test_code_abc';
        $cardId = 'test_card_id_def';

        $this->request->setCode($code);
        $this->request->setCardId($cardId);

        $options = $this->request->getRequestOptions();

        $this->assertIsArray($options);

        $this->assertArrayHasKey('json', $options);
        $jsonData = $options['json'];
        $this->assertIsArray($jsonData);
        $this->assertArrayHasKey('code', $jsonData);
        $this->assertArrayHasKey('card_id', $jsonData);
        $this->assertSame($code, $jsonData['code']);
        $this->assertSame($cardId, $jsonData['card_id']);
    }

    public function testGetRequestOptionsWithNullCardId(): void
    {
        $code = 'test_code_xyz';

        $this->request->setCode($code);
        $this->request->setCardId(null);

        $options = $this->request->getRequestOptions();

        $this->assertIsArray($options);

        $this->assertArrayHasKey('json', $options);
        $jsonData = $options['json'];
        $this->assertIsArray($jsonData);
        $this->assertArrayHasKey('code', $jsonData);
        $this->assertSame($code, $jsonData['code']);
        $this->assertArrayNotHasKey('card_id', $jsonData);
    }

    public function testSetCodeWithEmptyString(): void
    {
        $emptyCode = '';

        $this->request->setCode($emptyCode);

        $this->assertSame($emptyCode, $this->request->getCode());
    }

    public function testSetCodeWithSpecialCharacters(): void
    {
        $specialCode = 'code_with_特殊字符_123!@#';

        $this->request->setCode($specialCode);

        $this->assertSame($specialCode, $this->request->getCode());
    }

    public function testSetCardIdWithEmptyString(): void
    {
        $emptyCardId = '';

        $this->request->setCardId($emptyCardId);

        $this->assertSame($emptyCardId, $this->request->getCardId());
    }

    public function testMultipleSettersChaining(): void
    {
        $code = 'chain_test_code';
        $cardId = 'chain_test_card_id';

        $this->request->setCode($code);
        $this->request->setCardId($cardId);

        $this->assertSame($code, $this->request->getCode());
        $this->assertSame($cardId, $this->request->getCardId());

        $options = $this->request->getRequestOptions();
        $this->assertNotNull($options);
        $this->assertArrayHasKey('json', $options);
        $jsonData = $options['json'];
        $this->assertIsArray($jsonData);

        $this->assertSame($code, $jsonData['code']);
        $this->assertSame($cardId, $jsonData['card_id']);
    }

    public function testOverwriteCodeValue(): void
    {
        $originalCode = 'original_code';
        $newCode = 'new_code';

        $this->request->setCode($originalCode);
        $this->assertSame($originalCode, $this->request->getCode());

        $this->request->setCode($newCode);
        $this->assertSame($newCode, $this->request->getCode());
    }

    public function testOverwriteCardIdValue(): void
    {
        $originalCardId = 'original_card_id';
        $newCardId = 'new_card_id';

        $this->request->setCardId($originalCardId);
        $this->assertSame($originalCardId, $this->request->getCardId());

        $this->request->setCardId($newCardId);
        $this->assertSame($newCardId, $this->request->getCardId());
    }

    public function testCardIdFromValueToNull(): void
    {
        $code = 'test_code_for_null';
        $cardId = 'test_card_id';

        $this->request->setCode($code);
        $this->request->setCardId($cardId);
        $this->assertSame($cardId, $this->request->getCardId());

        $this->request->setCardId(null);
        $this->assertNull($this->request->getCardId());

        $options = $this->request->getRequestOptions();
        $this->assertNotNull($options);
        $this->assertArrayHasKey('json', $options);
        $jsonData = $options['json'];
        $this->assertIsArray($jsonData);
        $this->assertSame($code, $jsonData['code']);
        $this->assertArrayNotHasKey('card_id', $jsonData);
    }
}
