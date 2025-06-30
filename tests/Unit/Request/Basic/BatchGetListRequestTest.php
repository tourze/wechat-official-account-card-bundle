<?php

namespace WechatOfficialAccountCardBundle\Tests\Unit\Request\Basic;

use PHPUnit\Framework\TestCase;
use WechatOfficialAccountBundle\Request\WithAccountRequest;
use WechatOfficialAccountCardBundle\Enum\CardStatus;
use WechatOfficialAccountCardBundle\Request\Basic\BatchGetListRequest;

class BatchGetListRequestTest extends TestCase
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
        
        $this->assertArrayHasKey('offset', $options);
        $this->assertArrayHasKey('count', $options);
        $this->assertEquals(0, $options['offset']);
        $this->assertEquals(50, $options['count']);
        $this->assertArrayNotHasKey('status_list', $options);
    }
    
    public function testSetOffset(): void
    {
        $result = $this->request->setOffset(10);
        
        $this->assertSame($this->request, $result);
        
        $options = $this->request->getRequestOptions();
        $this->assertEquals(10, $options['offset']);
    }
    
    public function testSetCount(): void
    {
        $result = $this->request->setCount(100);
        
        $this->assertSame($this->request, $result);
        
        $options = $this->request->getRequestOptions();
        $this->assertEquals(100, $options['count']);
    }
    
    public function testSetStatusListWithNull(): void
    {
        $result = $this->request->setStatusList(null);
        
        $this->assertSame($this->request, $result);
        
        $options = $this->request->getRequestOptions();
        $this->assertArrayNotHasKey('status_list', $options);
    }
    
    public function testSetStatusListWithArray(): void
    {
        $statusList = [CardStatus::VERIFY_OK, CardStatus::DISPATCH];
        
        $result = $this->request->setStatusList($statusList);
        
        $this->assertSame($this->request, $result);
        
        $options = $this->request->getRequestOptions();
        $this->assertArrayHasKey('status_list', $options);
        $this->assertEquals(['CARD_STATUS_VERIFY_OK', 'CARD_STATUS_DISPATCH'], $options['status_list']);
    }
    
    public function testChainedMethodCalls(): void
    {
        $result = $this->request
            ->setOffset(20)
            ->setCount(25)
            ->setStatusList([CardStatus::VERIFY_OK]);
        
        $this->assertSame($this->request, $result);
        
        $options = $this->request->getRequestOptions();
        $this->assertEquals(20, $options['offset']);
        $this->assertEquals(25, $options['count']);
        $this->assertEquals(['CARD_STATUS_VERIFY_OK'], $options['status_list']);
    }
}