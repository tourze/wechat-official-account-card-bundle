<?php

namespace WechatOfficialAccountCardBundle\Tests\Unit\Request\Basic;

use PHPUnit\Framework\TestCase;
use WechatOfficialAccountCardBundle\Request\Basic\GetDetailRequest;

class GetDetailRequestTest extends TestCase
{
    private GetDetailRequest $request;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->request = new GetDetailRequest();
    }
    
    public function testRequestInstantiation(): void
    {
        $this->assertInstanceOf(GetDetailRequest::class, $this->request);
    }
}