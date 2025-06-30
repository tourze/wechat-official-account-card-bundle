<?php

namespace WechatOfficialAccountCardBundle\Tests\Unit\Request\Basic;

use PHPUnit\Framework\TestCase;
use WechatOfficialAccountCardBundle\Request\Basic\CreateRequest;

class CreateRequestTest extends TestCase
{
    private CreateRequest $request;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->request = new CreateRequest();
    }
    
    public function testRequestInstantiation(): void
    {
        $this->assertInstanceOf(CreateRequest::class, $this->request);
    }
}