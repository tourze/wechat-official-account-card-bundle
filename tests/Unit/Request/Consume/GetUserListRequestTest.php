<?php

namespace WechatOfficialAccountCardBundle\Tests\Unit\Request\Consume;

use PHPUnit\Framework\TestCase;
use WechatOfficialAccountCardBundle\Request\Consume\GetUserListRequest;

class GetUserListRequestTest extends TestCase
{
    private GetUserListRequest $request;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->request = new GetUserListRequest();
    }
    
    public function testRequestInstantiation(): void
    {
        $this->assertInstanceOf(GetUserListRequest::class, $this->request);
    }
}