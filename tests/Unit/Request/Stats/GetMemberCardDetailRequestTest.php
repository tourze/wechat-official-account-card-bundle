<?php

namespace WechatOfficialAccountCardBundle\Tests\Unit\Request\Stats;

use PHPUnit\Framework\TestCase;
use WechatOfficialAccountCardBundle\Request\Stats\GetMemberCardDetailRequest;

class GetMemberCardDetailRequestTest extends TestCase
{
    private GetMemberCardDetailRequest $request;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->request = new GetMemberCardDetailRequest();
    }
    
    public function testRequestInstantiation(): void
    {
        $this->assertInstanceOf(GetMemberCardDetailRequest::class, $this->request);
    }
}