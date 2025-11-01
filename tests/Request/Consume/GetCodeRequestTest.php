<?php

namespace WechatOfficialAccountCardBundle\Tests\Request\Consume;

use HttpClientBundle\Test\RequestTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use WechatOfficialAccountBundle\Request\WithAccountRequest;
use WechatOfficialAccountCardBundle\Request\Consume\GetCodeRequest;

/**
 * @internal
 */
#[CoversClass(GetCodeRequest::class)]
final class GetCodeRequestTest extends RequestTestCase
{
    private GetCodeRequest $request;

    protected function setUp(): void
    {
        parent::setUp();

        $this->request = new GetCodeRequest();
    }

    public function testInheritance(): void
    {
        $this->assertInstanceOf(WithAccountRequest::class, $this->request);
    }

    public function testGetRequestPath(): void
    {
        $expectedPath = 'https://api.weixin.qq.com/card/code/get';
        $this->assertSame($expectedPath, $this->request->getRequestPath());
    }

    public function testGetRequestMethod(): void
    {
        $this->assertSame('POST', $this->request->getRequestMethod());
    }

    public function testCodeGetterAndSetter(): void
    {
        $code = 'test_code_12345';
        $this->request->setCode($code);

        $this->assertSame($code, $this->request->getCode());
    }

    public function testCardIdGetterAndSetter(): void
    {
        $cardId = 'pFS7Fjg8kV1IdDz01r4SQwMkuCKc';
        $this->request->setCardId($cardId);

        $this->assertSame($cardId, $this->request->getCardId());
    }

    public function testCardIdDefaultsToNull(): void
    {
        $this->assertNull($this->request->getCardId());
    }

    public function testCardIdCanBeSetToNull(): void
    {
        $this->request->setCardId('test');
        $this->request->setCardId(null);

        $this->assertNull($this->request->getCardId());
    }

    public function testCheckConsumeGetterAndSetter(): void
    {
        $this->request->setCheckConsume(true);
        $this->assertTrue($this->request->getCheckConsume());

        $this->request->setCheckConsume(false);
        $this->assertFalse($this->request->getCheckConsume());
    }

    public function testCheckConsumeDefaultsToNull(): void
    {
        $this->assertNull($this->request->getCheckConsume());
    }

    public function testCheckConsumeCanBeSetToNull(): void
    {
        $this->request->setCheckConsume(true);
        $this->request->setCheckConsume(null);

        $this->assertNull($this->request->getCheckConsume());
    }

    public function testGetRequestOptionsWithOnlyCode(): void
    {
        $code = 'test_code_123';
        $this->request->setCode($code);

        $expectedOptions = [
            'json' => [
                'code' => $code,
            ],
        ];

        $this->assertSame($expectedOptions, $this->request->getRequestOptions());
    }

    public function testGetRequestOptionsWithAllFields(): void
    {
        $code = 'test_code_123';
        $cardId = 'pFS7Fjg8kV1IdDz01r4SQwMkuCKc';
        $checkConsume = true;

        $this->request->setCode($code);
        $this->request->setCardId($cardId);
        $this->request->setCheckConsume($checkConsume);

        $expectedOptions = [
            'json' => [
                'code' => $code,
                'card_id' => $cardId,
                'check_consume' => $checkConsume,
            ],
        ];

        $this->assertSame($expectedOptions, $this->request->getRequestOptions());
    }

    public function testGetRequestOptionsWithNullOptionalFields(): void
    {
        $code = 'test_code_123';

        $this->request->setCode($code);
        $this->request->setCardId(null);
        $this->request->setCheckConsume(null);

        $options = $this->request->getRequestOptions();
        $this->assertIsArray($options);

        $this->assertArrayHasKey('json', $options);
        $json = $options['json'];
        $this->assertIsArray($json);
        $this->assertArrayNotHasKey('card_id', $json);
        $this->assertArrayNotHasKey('check_consume', $json);
    }

    public function testGetRequestOptionsWithCheckConsumeFalse(): void
    {
        $code = 'test_code_123';
        $checkConsume = false;

        $this->request->setCode($code);
        $this->request->setCheckConsume($checkConsume);

        $expectedOptions = [
            'json' => [
                'code' => $code,
                'check_consume' => $checkConsume,
            ],
        ];

        $this->assertSame($expectedOptions, $this->request->getRequestOptions());
    }
}
