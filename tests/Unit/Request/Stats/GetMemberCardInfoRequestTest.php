<?php

namespace WechatOfficialAccountCardBundle\Tests\Unit\Request\Stats;

use PHPUnit\Framework\TestCase;
use WechatOfficialAccountCardBundle\Request\Stats\GetMemberCardInfoRequest;

class GetMemberCardInfoRequestTest extends TestCase
{
    private GetMemberCardInfoRequest $request;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->request = new GetMemberCardInfoRequest();
    }
    
    public function testRequestInstantiation(): void
    {
        $this->assertInstanceOf(GetMemberCardInfoRequest::class, $this->request);
    }
}