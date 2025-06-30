<?php

namespace WechatOfficialAccountCardBundle\Tests\Unit\Request\Stats;

use PHPUnit\Framework\TestCase;
use WechatOfficialAccountCardBundle\Request\Stats\GetCardInfoRequest;

class GetCardInfoRequestTest extends TestCase
{
    private GetCardInfoRequest $request;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->request = new GetCardInfoRequest();
    }
    
    public function testRequestInstantiation(): void
    {
        $this->assertInstanceOf(GetCardInfoRequest::class, $this->request);
    }
}