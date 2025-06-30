<?php

namespace WechatOfficialAccountCardBundle\Tests\Unit\Request\Consume;

use PHPUnit\Framework\TestCase;
use WechatOfficialAccountCardBundle\Request\Consume\UnavailableRequest;

class UnavailableRequestTest extends TestCase
{
    private UnavailableRequest $request;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->request = new UnavailableRequest();
    }
    
    public function testRequestInstantiation(): void
    {
        $this->assertInstanceOf(UnavailableRequest::class, $this->request);
    }
}