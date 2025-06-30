<?php

namespace WechatOfficialAccountCardBundle\Tests\Unit\Request\Consume;

use PHPUnit\Framework\TestCase;
use WechatOfficialAccountCardBundle\Request\Consume\GetCodeRequest;

class GetCodeRequestTest extends TestCase
{
    private GetCodeRequest $request;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->request = new GetCodeRequest();
    }
    
    public function testRequestInstantiation(): void
    {
        $this->assertInstanceOf(GetCodeRequest::class, $this->request);
    }
}