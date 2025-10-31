<?php

namespace WechatOfficialAccountCardBundle\Tests\Request\Consume;

use PHPUnit\Framework\Attributes\CoversClass;
use HttpClientBundle\Tests\Request\RequestTestCase;
use WechatOfficialAccountBundle\Request\WithAccountRequest;
use WechatOfficialAccountCardBundle\Request\Consume\UpdateCodeRequest;

/**
 * @internal
 */
#[CoversClass(UpdateCodeRequest::class)]
final class UpdateCodeRequestTest extends RequestTestCase
{
    private UpdateCodeRequest $request;

    protected function setUp(): void
    {
        parent::setUp();

        $this->request = new UpdateCodeRequest();
    }

    public function testRequestInstantiation(): void
    {
        $this->assertInstanceOf(UpdateCodeRequest::class, $this->request);
        $this->assertInstanceOf(WithAccountRequest::class, $this->request);
    }

    public function testGetRequestPath(): void
    {
        $expected = 'https://api.weixin.qq.com/card/code/update';
        $this->assertSame($expected, $this->request->getRequestPath());
    }

    public function testCodeSetterAndGetter(): void
    {
        $code = '12345678901234567890';
        $this->request->setCode($code);
        $this->assertSame($code, $this->request->getCode());
    }

    public function testCodeWithSpecialCharacters(): void
    {
        $code = 'ABC-123_DEF.890';
        $this->request->setCode($code);
        $this->assertSame($code, $this->request->getCode());
    }

    public function testCardIdSetterAndGetter(): void
    {
        $cardId = 'pFS7Fjg8kV1IdDz01r4SQwMkuCKc';
        $this->request->setCardId($cardId);
        $this->assertSame($cardId, $this->request->getCardId());
    }

    public function testCardIdWithDifferentFormat(): void
    {
        $cardId = 'card_12345abcdef67890';
        $this->request->setCardId($cardId);
        $this->assertSame($cardId, $this->request->getCardId());
    }

    public function testNewCodeSetterAndGetter(): void
    {
        $newCode = '09876543210987654321';
        $this->request->setNewCode($newCode);
        $this->assertSame($newCode, $this->request->getNewCode());
    }

    public function testNewCodeWithSpecialCharacters(): void
    {
        $newCode = 'XYZ-789_GHI.012';
        $this->request->setNewCode($newCode);
        $this->assertSame($newCode, $this->request->getNewCode());
    }

    public function testGetRequestOptionsWithAllParameters(): void
    {
        $code = '12345678901234567890';
        $cardId = 'pFS7Fjg8kV1IdDz01r4SQwMkuCKc';
        $newCode = '09876543210987654321';

        $this->request->setCode($code);
        $this->request->setCardId($cardId);
        $this->request->setNewCode($newCode);

        $expected = [
            'json' => [
                'code' => $code,
                'card_id' => $cardId,
                'new_code' => $newCode,
            ],
        ];

        $this->assertEquals($expected, $this->request->getRequestOptions());
    }

    public function testGetRequestOptionsWithChineseCharacters(): void
    {
        $code = '测试代码123';
        $cardId = 'pFS7Fjg8kV1IdDz01r4SQwMkuCKc';
        $newCode = '新代码456';

        $this->request->setCode($code);
        $this->request->setCardId($cardId);
        $this->request->setNewCode($newCode);

        $options = $this->request->getRequestOptions();

        $this->assertIsArray($options);

        $this->assertArrayHasKey('json', $options);
        $json = $options['json'];
        $this->assertIsArray($json);

        $this->assertArrayHasKey('code', $json);
        $this->assertArrayHasKey('card_id', $json);
        $this->assertArrayHasKey('new_code', $json);
        $this->assertSame($code, $json['code']);
        $this->assertSame($cardId, $json['card_id']);
        $this->assertSame($newCode, $json['new_code']);
    }

    public function testGetRequestOptionsStructure(): void
    {
        $this->request->setCode('test-code');
        $this->request->setCardId('test-card-id');
        $this->request->setNewCode('new-test-code');

        $options = $this->request->getRequestOptions();

        $this->assertIsArray($options);

        $this->assertArrayHasKey('json', $options);
        $this->assertIsArray($options['json']);
        $this->assertArrayHasKey('code', $options['json']);
        $this->assertArrayHasKey('card_id', $options['json']);
        $this->assertArrayHasKey('new_code', $options['json']);
        $this->assertCount(3, $options['json']);
    }

    public function testParameterOverwrite(): void
    {
        // 设置初始值
        $this->request->setCode('initial-code');
        $this->request->setCardId('initial-card-id');
        $this->request->setNewCode('initial-new-code');

        // 覆盖值
        $newCode = 'updated-code';
        $newCardId = 'updated-card-id';
        $newNewCode = 'updated-new-code';

        $this->request->setCode($newCode);
        $this->request->setCardId($newCardId);
        $this->request->setNewCode($newNewCode);

        // 验证覆盖后的值
        $this->assertSame($newCode, $this->request->getCode());
        $this->assertSame($newCardId, $this->request->getCardId());
        $this->assertSame($newNewCode, $this->request->getNewCode());
    }

    public function testEmptyStringsHandling(): void
    {
        $this->request->setCode('');
        $this->request->setCardId('');
        $this->request->setNewCode('');

        $this->assertSame('', $this->request->getCode());
        $this->assertSame('', $this->request->getCardId());
        $this->assertSame('', $this->request->getNewCode());

        $options = $this->request->getRequestOptions();
        $this->assertIsArray($options);
        $this->assertArrayHasKey('json', $options);
        $json = $options['json'];
        $this->assertIsArray($json);

        $this->assertArrayHasKey('code', $json);
        $this->assertArrayHasKey('card_id', $json);
        $this->assertArrayHasKey('new_code', $json);
        $this->assertSame('', $json['code']);
        $this->assertSame('', $json['card_id']);
        $this->assertSame('', $json['new_code']);
    }

    public function testLongStringsHandling(): void
    {
        $longCode = str_repeat('A', 100);
        $longCardId = str_repeat('B', 100);
        $longNewCode = str_repeat('C', 100);

        $this->request->setCode($longCode);
        $this->request->setCardId($longCardId);
        $this->request->setNewCode($longNewCode);

        $this->assertSame($longCode, $this->request->getCode());
        $this->assertSame($longCardId, $this->request->getCardId());
        $this->assertSame($longNewCode, $this->request->getNewCode());
    }

    public function testRequestOptionsConsistency(): void
    {
        $code = 'consistency-test-code';
        $cardId = 'consistency-test-card-id';
        $newCode = 'consistency-test-new-code';

        $this->request->setCode($code);
        $this->request->setCardId($cardId);
        $this->request->setNewCode($newCode);

        $options1 = $this->request->getRequestOptions();
        $options2 = $this->request->getRequestOptions();

        $this->assertEquals($options1, $options2);
        $this->assertIsArray($options1);
        $this->assertIsArray($options2);
        $this->assertArrayHasKey('json', $options1);
        $this->assertArrayHasKey('json', $options2);

        $json1 = $options1['json'];
        $json2 = $options2['json'];
        $this->assertIsArray($json1);
        $this->assertIsArray($json2);

        $this->assertArrayHasKey('code', $json1);
        $this->assertArrayHasKey('card_id', $json1);
        $this->assertArrayHasKey('new_code', $json1);
        $this->assertSame($json1['code'], $json2['code']);
        $this->assertSame($json1['card_id'], $json2['card_id']);
        $this->assertSame($json1['new_code'], $json2['new_code']);
    }

    public function testGettersReturnAssignedValues(): void
    {
        $code = 'test-code';
        $cardId = 'test-card-id';
        $newCode = 'test-new-code';

        $this->request->setCode($code);
        $this->request->setCardId($cardId);
        $this->request->setNewCode($newCode);

        // 验证getter返回与setter设置的值一致
        $this->assertSame($code, $this->request->getCode());
        $this->assertSame($cardId, $this->request->getCardId());
        $this->assertSame($newCode, $this->request->getNewCode());

        // 验证请求选项包含必需的json键
        $options = $this->request->getRequestOptions();
        $this->assertIsArray($options);
        $this->assertArrayHasKey('json', $options);
    }

    public function testNullValueHandling(): void
    {
        // 测试未设置值时的默认行为
        $this->assertNull($this->request->getCode());
        $this->assertNull($this->request->getCardId());
        $this->assertNull($this->request->getNewCode());

        $options = $this->request->getRequestOptions();
        $this->assertIsArray($options);
        $this->assertArrayHasKey('json', $options);
        $json = $options['json'];
        $this->assertIsArray($json);
        $this->assertArrayHasKey('code', $json);
        $this->assertArrayHasKey('card_id', $json);
        $this->assertArrayHasKey('new_code', $json);
        $this->assertSame('', $json['code']);
        $this->assertSame('', $json['card_id']);
        $this->assertSame('', $json['new_code']);
    }
}
