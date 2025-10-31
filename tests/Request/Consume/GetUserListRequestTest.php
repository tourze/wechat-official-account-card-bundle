<?php

namespace WechatOfficialAccountCardBundle\Tests\Request\Consume;

use PHPUnit\Framework\Attributes\CoversClass;
use HttpClientBundle\Tests\Request\RequestTestCase;
use WechatOfficialAccountBundle\Entity\Account;
use WechatOfficialAccountBundle\Request\WithAccountRequest;
use WechatOfficialAccountCardBundle\Request\Consume\GetUserListRequest;

/**
 * @internal
 */
#[CoversClass(GetUserListRequest::class)]
final class GetUserListRequestTest extends RequestTestCase
{
    private GetUserListRequest $request;

    protected function setUp(): void
    {
        parent::setUp();

        $this->request = new GetUserListRequest();
    }

    public function testExtendsWithAccountRequest(): void
    {
        $this->assertInstanceOf(WithAccountRequest::class, $this->request);
    }

    public function testGetRequestPath(): void
    {
        $expectedPath = 'https://api.weixin.qq.com/card/user/getcardlist';
        $this->assertSame($expectedPath, $this->request->getRequestPath());
    }

    public function testOpenIdSetterAndGetter(): void
    {
        $openId = 'test_open_id_123456';
        $this->request->setOpenId($openId);
        $this->assertSame($openId, $this->request->getOpenId());
    }

    public function testCardIdDefaultValue(): void
    {
        $this->assertNull($this->request->getCardId());
    }

    public function testCardIdSetterAndGetter(): void
    {
        $cardId = 'card123456789';
        $this->request->setCardId($cardId);
        $this->assertSame($cardId, $this->request->getCardId());
    }

    public function testCardIdCanBeSetToNull(): void
    {
        $this->request->setCardId('card123');
        $this->request->setCardId(null);
        $this->assertNull($this->request->getCardId());
    }

    public function testGetRequestOptionsWithOpenIdOnly(): void
    {
        $openId = 'test_open_id_123456';
        $this->request->setOpenId($openId);

        $expectedOptions = [
            'json' => [
                'openid' => $openId,
            ],
        ];

        $this->assertEquals($expectedOptions, $this->request->getRequestOptions());
    }

    public function testGetRequestOptionsWithOpenIdAndCardId(): void
    {
        $openId = 'test_open_id_123456';
        $cardId = 'card123456789';

        $this->request->setOpenId($openId);
        $this->request->setCardId($cardId);

        $expectedOptions = [
            'json' => [
                'openid' => $openId,
                'card_id' => $cardId,
            ],
        ];

        $this->assertEquals($expectedOptions, $this->request->getRequestOptions());
    }

    public function testGetRequestOptionsExcludesNullCardId(): void
    {
        $openId = 'test_open_id_123456';

        $this->request->setOpenId($openId);
        $this->request->setCardId(null);

        $options = $this->request->getRequestOptions();

        $this->assertNotNull($options);
        $this->assertArrayHasKey('json', $options);
        $json = $options['json'];
        $this->assertIsArray($json);
        $this->assertArrayNotHasKey('card_id', $json);
        $this->assertSame($openId, $json['openid']);
    }

    public function testInheritsFromWithAccountRequest(): void
    {
        $account = $this->createMock(Account::class);

        $this->request->setAccount($account);
        $this->assertSame($account, $this->request->getAccount());
    }

    public function testMethodCallsCombination(): void
    {
        $openId = 'test_open_id_123456';
        $cardId = 'card123456789';
        $account = $this->createMock(Account::class);

        $this->request->setOpenId($openId);
        $this->request->setCardId($cardId);
        $this->request->setAccount($account);

        $this->assertSame($openId, $this->request->getOpenId());
        $this->assertSame($cardId, $this->request->getCardId());
        $this->assertSame($account, $this->request->getAccount());

        $expectedOptions = [
            'json' => [
                'openid' => $openId,
                'card_id' => $cardId,
            ],
        ];

        $this->assertEquals($expectedOptions, $this->request->getRequestOptions());
    }
}
