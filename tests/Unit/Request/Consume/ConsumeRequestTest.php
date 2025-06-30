<?php

namespace WechatOfficialAccountCardBundle\Tests\Unit\Request\Consume;

use PHPUnit\Framework\TestCase;
use WechatOfficialAccountCardBundle\Request\Consume\ConsumeRequest;

class ConsumeRequestTest extends TestCase
{
    private ConsumeRequest $request;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->request = new ConsumeRequest();
    }
    
    public function testRequestInstantiation(): void
    {
        $this->assertInstanceOf(ConsumeRequest::class, $this->request);
    }
}