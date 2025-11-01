<?php

namespace WechatOfficialAccountCardBundle\Tests\Request\Basic;

use HttpClientBundle\Test\RequestTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use WechatOfficialAccountBundle\Request\WithAccountRequest;
use WechatOfficialAccountCardBundle\Enum\CardStatus;
use WechatOfficialAccountCardBundle\Request\Basic\BatchGetListRequest;

/**
 * @internal
 */
#[CoversClass(BatchGetListRequest::class)]
final class BatchGetListRequestTest extends RequestTestCase
{
    private BatchGetListRequest $request;

    protected function setUp(): void
    {
        parent::setUp();

        $this->request = new BatchGetListRequest();
    }

    public function testExtendsWithAccountRequest(): void
    {
        $this->assertInstanceOf(WithAccountRequest::class, $this->request);
    }

    public function testGetRequestPath(): void
    {
        $this->assertEquals('card/batchget', $this->request->getRequestPath());
    }

    public function testDefaultRequestOptions(): void
    {
        $options = $this->request->getRequestOptions();

        $this->assertIsArray($options);

        $this->assertArrayHasKey('offset', $options);
        $this->assertArrayHasKey('count', $options);
        $this->assertEquals(0, $options['offset']);
        $this->assertEquals(50, $options['count']);
        $this->assertArrayNotHasKey('status_list', $options);
    }

    public function testSetOffset(): void
    {
        $this->request->setOffset(10);

        $options = $this->request->getRequestOptions();
        $this->assertEquals(10, $options['offset']);
    }

    public function testSetCount(): void
    {
        $this->request->setCount(100);

        $options = $this->request->getRequestOptions();
        $this->assertEquals(100, $options['count']);
    }

    public function testSetStatusListWithNull(): void
    {
        $this->request->setStatusList(null);

        $options = $this->request->getRequestOptions();
        $this->assertArrayNotHasKey('status_list', $options);
    }

    public function testSetStatusListWithArray(): void
    {
        $statusList = [CardStatus::VERIFY_OK, CardStatus::DISPATCH];

        $this->request->setStatusList($statusList);

        $options = $this->request->getRequestOptions();

        $this->assertIsArray($options);

        $this->assertArrayHasKey('status_list', $options);
        $this->assertEquals(['CARD_STATUS_VERIFY_OK', 'CARD_STATUS_DISPATCH'], $options['status_list']);
    }

    public function testChainedMethodCalls(): void
    {
        $this->request->setOffset(20);
        $this->request->setCount(25);
        $this->request->setStatusList([CardStatus::VERIFY_OK]);

        $options = $this->request->getRequestOptions();
        $this->assertEquals(20, $options['offset']);
        $this->assertEquals(25, $options['count']);
        $this->assertEquals(['CARD_STATUS_VERIFY_OK'], $options['status_list']);
    }
}
