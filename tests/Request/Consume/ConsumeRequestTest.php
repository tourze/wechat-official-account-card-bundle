<?php

namespace WechatOfficialAccountCardBundle\Tests\Request\Consume;

use HttpClientBundle\Test\RequestTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use WechatOfficialAccountBundle\Entity\Account;
use WechatOfficialAccountCardBundle\Request\Consume\ConsumeRequest;

/**
 * @internal
 */
#[CoversClass(ConsumeRequest::class)]
final class ConsumeRequestTest extends RequestTestCase
{
    private ConsumeRequest $request;

    protected function setUp(): void
    {
        parent::setUp();

        $this->request = new ConsumeRequest();
    }

    public function testGetRequestPath(): void
    {
        $expectedPath = 'https://api.weixin.qq.com/card/code/consume';
        $this->assertSame($expectedPath, $this->request->getRequestPath());
    }

    public function testCodeSetterAndGetter(): void
    {
        $code = '123456789';
        $this->request->setCode($code);
        $this->assertSame($code, $this->request->getCode());
    }

    public function testCardIdDefaultValue(): void
    {
        $this->assertNull($this->request->getCardId());
    }

    public function testCardIdSetterAndGetter(): void
    {
        $cardId = 'card123456';
        $this->request->setCardId($cardId);
        $this->assertSame($cardId, $this->request->getCardId());
    }

    public function testCardIdCanBeSetToNull(): void
    {
        $this->request->setCardId('card123');
        $this->request->setCardId(null);
        $this->assertNull($this->request->getCardId());
    }

    public function testGetRequestOptionsWithCodeOnly(): void
    {
        $code = '123456789';
        $this->request->setCode($code);

        $expectedOptions = [
            'json' => [
                'code' => $code,
            ],
        ];

        $this->assertEquals($expectedOptions, $this->request->getRequestOptions());
    }

    public function testGetRequestOptionsWithCodeAndCardId(): void
    {
        $code = '123456789';
        $cardId = 'card123456';

        $this->request->setCode($code);
        $this->request->setCardId($cardId);

        $expectedOptions = [
            'json' => [
                'code' => $code,
                'card_id' => $cardId,
            ],
        ];

        $this->assertEquals($expectedOptions, $this->request->getRequestOptions());
    }

    public function testGetRequestOptionsExcludesNullCardId(): void
    {
        $code = '123456789';

        $this->request->setCode($code);
        $this->request->setCardId(null);

        $options = $this->request->getRequestOptions();

        $this->assertIsArray($options);
        $this->assertArrayHasKey('json', $options);
        $json = $options['json'];
        $this->assertIsArray($json);
        $this->assertArrayNotHasKey('card_id', $json);
        $this->assertArrayHasKey('code', $json);
        $this->assertSame($code, $json['code']);
    }

    public function testInheritsFromWithAccountRequest(): void
    {
        $account = $this->createMock(Account::class);

        $this->request->setAccount($account);
        $this->assertSame($account, $this->request->getAccount());
    }
}
