<?php

namespace WechatOfficialAccountCardBundle\Tests\Request\Consume;

use HttpClientBundle\Test\RequestTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use WechatOfficialAccountBundle\Request\WithAccountRequest;
use WechatOfficialAccountCardBundle\Request\Consume\DecryptCodeRequest;

/**
 * @internal
 */
#[CoversClass(DecryptCodeRequest::class)]
final class DecryptCodeRequestTest extends RequestTestCase
{
    private DecryptCodeRequest $request;

    protected function setUp(): void
    {
        parent::setUp();

        $this->request = new DecryptCodeRequest();
    }

    public function testInheritance(): void
    {
        $this->assertInstanceOf(WithAccountRequest::class, $this->request);
    }

    public function testGetRequestPath(): void
    {
        $expectedPath = 'https://api.weixin.qq.com/card/code/decrypt';
        $this->assertSame($expectedPath, $this->request->getRequestPath());
    }

    public function testEncryptCodeGetterAndSetter(): void
    {
        $encryptCode = 'encrypt_code_12345';
        $this->request->setEncryptCode($encryptCode);

        $this->assertSame($encryptCode, $this->request->getEncryptCode());
    }

    public function testGetRequestOptionsWithEncryptCode(): void
    {
        $encryptCode = 'test_encrypt_code_abcdef';
        $this->request->setEncryptCode($encryptCode);

        $expectedOptions = [
            'json' => [
                'encrypt_code' => $encryptCode,
            ],
        ];

        $this->assertSame($expectedOptions, $this->request->getRequestOptions());
    }

    public function testRequestOptionsStructure(): void
    {
        $this->request->setEncryptCode('sample_code');
        $options = $this->request->getRequestOptions();
        $this->assertIsArray($options);

        $this->assertArrayHasKey('json', $options);
        $json = $options['json'];
        $this->assertIsArray($json);
        $this->assertArrayHasKey('encrypt_code', $json);
        $this->assertSame('sample_code', $json['encrypt_code']);
    }
}
