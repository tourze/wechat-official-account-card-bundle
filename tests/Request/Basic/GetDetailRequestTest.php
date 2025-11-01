<?php

namespace WechatOfficialAccountCardBundle\Tests\Request\Basic;

use HttpClientBundle\Test\RequestTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use WechatOfficialAccountBundle\Entity\Account;
use WechatOfficialAccountBundle\Request\WithAccountRequest;
use WechatOfficialAccountCardBundle\Request\Basic\GetDetailRequest;

/**
 * @internal
 */
#[CoversClass(GetDetailRequest::class)]
final class GetDetailRequestTest extends RequestTestCase
{
    private GetDetailRequest $request;

    protected function setUp(): void
    {
        parent::setUp();

        $this->request = new GetDetailRequest();
    }

    public function testInheritance(): void
    {
        $this->assertInstanceOf(WithAccountRequest::class, $this->request);
    }

    public function testGetRequestPath(): void
    {
        $this->assertSame('https://api.weixin.qq.com/card/get', $this->request->getRequestPath());
    }

    public function testCardIdSetterAndGetter(): void
    {
        $cardId = 'pCJ1lQv7tC7YrWLlmnLCCUKHOaKA';

        $this->request->setCardId($cardId);

        $this->assertSame($cardId, $this->request->getCardId());
    }

    public function testCardIdGetterWithDifferentValues(): void
    {
        $testCases = [
            'pCJ1lQv7tC7YrWLlmnLCCUKHOaKA',
            'pCJ1lQv7tC7YrWLlmnLCCUKHOaKB',
            'test_card_id_123',
            '',
        ];

        foreach ($testCases as $cardId) {
            $this->request->setCardId($cardId);
            $this->assertSame($cardId, $this->request->getCardId());
        }
    }

    public function testGetRequestOptionsWithCardId(): void
    {
        $cardId = 'pCJ1lQv7tC7YrWLlmnLCCUKHOaKA';
        $this->request->setCardId($cardId);

        $options = $this->request->getRequestOptions();

        $this->assertIsArray($options);

        $this->assertArrayHasKey('json', $options);
        $json = $options['json'];
        $this->assertIsArray($json);
        $this->assertSame($cardId, $json['card_id']);
    }

    public function testGetRequestOptionsStructure(): void
    {
        $cardId = 'test_card_id_for_structure';
        $this->request->setCardId($cardId);

        $options = $this->request->getRequestOptions();

        $expectedStructure = [
            'json' => [
                'card_id' => $cardId,
            ],
        ];

        $this->assertEquals($expectedStructure, $options);
    }

    public function testGetRequestOptionsWithEmptyCardId(): void
    {
        $this->request->setCardId('');

        $options = $this->request->getRequestOptions();

        $this->assertIsArray($options);

        $this->assertArrayHasKey('json', $options);
        $json = $options['json'];
        $this->assertIsArray($json);
        $this->assertSame('', $json['card_id']);
    }

    public function testAccountInheritedFunctionality(): void
    {
        $account = new Account();
        $account->setAppId('test_app_id');
        $account->setName('Test Account');

        $this->request->setAccount($account);

        $retrievedAccount = $this->request->getAccount();
        $this->assertSame($account, $retrievedAccount);
        $this->assertInstanceOf(Account::class, $retrievedAccount);

        /** @var Account $retrievedAccount */
        $this->assertSame('test_app_id', $retrievedAccount->getAppId());
        $this->assertSame('Test Account', $retrievedAccount->getName());
    }

    public function testCompleteWorkflow(): void
    {
        $cardId = 'pCJ1lQv7tC7YrWLlmnLCCUKHOaKA';
        $account = new Account();
        $account->setAppId('wx1234567890abcdef');
        $account->setAppSecret('1234567890abcdef1234567890abcdef');
        $account->setName('Test WeChat Account');

        $this->request->setCardId($cardId);
        $this->request->setAccount($account);

        $this->assertSame('https://api.weixin.qq.com/card/get', $this->request->getRequestPath());
        $this->assertSame($cardId, $this->request->getCardId());
        $this->assertSame($account, $this->request->getAccount());

        $options = $this->request->getRequestOptions();

        $this->assertIsArray($options);

        $this->assertArrayHasKey('json', $options);
        $json = $options['json'];
        $this->assertIsArray($json);
        $this->assertSame($cardId, $json['card_id']);
    }

    public function testStringConversion(): void
    {
        $cardId = 'pCJ1lQv7tC7YrWLlmnLCCUKHOaKA';
        $this->request->setCardId($cardId);

        $stringRepresentation = (string) $this->request;

        $this->assertIsString($stringRepresentation);
        $this->assertJson($stringRepresentation);

        $decoded = json_decode($stringRepresentation, true);
        $this->assertIsArray($decoded);
        $this->assertArrayHasKey('_className', $decoded);
        $this->assertArrayHasKey('path', $decoded);
        $this->assertArrayHasKey('payload', $decoded);
        $this->assertSame(GetDetailRequest::class, $decoded['_className']);
        $this->assertSame('https://api.weixin.qq.com/card/get', $decoded['path']);
    }

    public function testLogDataGeneration(): void
    {
        $cardId = 'pCJ1lQv7tC7YrWLlmnLCCUKHOaKA';
        $this->request->setCardId($cardId);

        $logData = $this->request->generateLogData();

        $this->assertNotNull($logData);
        $this->assertArrayHasKey('_className', $logData);
        $this->assertArrayHasKey('path', $logData);
        $this->assertArrayHasKey('method', $logData);
        $this->assertArrayHasKey('payload', $logData);

        $this->assertSame(GetDetailRequest::class, $logData['_className']);
        $this->assertSame('https://api.weixin.qq.com/card/get', $logData['path']);
        $this->assertNull($logData['method']);
        $this->assertSame(['json' => ['card_id' => $cardId]], $logData['payload']);
    }
}
