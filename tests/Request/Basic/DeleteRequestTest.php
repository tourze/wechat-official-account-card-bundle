<?php

namespace WechatOfficialAccountCardBundle\Tests\Request\Basic;

use HttpClientBundle\Test\RequestTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use WechatOfficialAccountBundle\Entity\Account;
use WechatOfficialAccountBundle\Request\WithAccountRequest;
use WechatOfficialAccountCardBundle\Request\Basic\DeleteRequest;

/**
 * @internal
 */
#[CoversClass(DeleteRequest::class)]
final class DeleteRequestTest extends RequestTestCase
{
    private DeleteRequest $request;

    protected function setUp(): void
    {
        parent::setUp();

        $this->request = new DeleteRequest();
    }

    public function testExtendsWithAccountRequest(): void
    {
        $this->assertInstanceOf(WithAccountRequest::class, $this->request);
    }

    public function testGetRequestPath(): void
    {
        $this->assertEquals('https://api.weixin.qq.com/card/delete', $this->request->getRequestPath());
    }

    public function testSetCardId(): void
    {
        $cardId = 'test_card_12345';
        $this->request->setCardId($cardId);
        $this->assertEquals($cardId, $this->request->getCardId());
    }

    public function testGetRequestOptionsWithCardId(): void
    {
        $cardId = 'test_card_abcdef';
        $this->request->setCardId($cardId);

        $options = $this->request->getRequestOptions();

        $this->assertIsArray($options);

        $this->assertArrayHasKey('json', $options);
        $json = $options['json'];
        $this->assertIsArray($json);
        $this->assertEquals($cardId, $json['card_id']);
    }

    public function testSetAccount(): void
    {
        $account = new Account();
        $this->request->setAccount($account);

        $this->assertSame($account, $this->request->getAccount());
    }

    public function testChainableMethodCall(): void
    {
        $cardId = 'chain_test_card';

        $this->request->setCardId($cardId);

        $this->assertEquals($cardId, $this->request->getCardId());

        $options = $this->request->getRequestOptions();
        $this->assertNotNull($options);
        $this->assertArrayHasKey('json', $options);
        $json = $options['json'];
        $this->assertIsArray($json);
        $this->assertEquals($cardId, $json['card_id']);
    }
}
