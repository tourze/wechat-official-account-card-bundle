<?php

namespace WechatOfficialAccountCardBundle\Tests\Unit\Request\Basic;

use PHPUnit\Framework\TestCase;
use WechatOfficialAccountCardBundle\Request\Basic\UpdateRequest;

class UpdateRequestTest extends TestCase
{
    private UpdateRequest $request;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->request = new UpdateRequest();
    }
    
    public function testRequestInstantiation(): void
    {
        $this->assertInstanceOf(UpdateRequest::class, $this->request);
    }
}