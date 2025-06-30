<?php

namespace WechatOfficialAccountCardBundle\Tests\Unit\Request\Consume;

use PHPUnit\Framework\TestCase;
use WechatOfficialAccountCardBundle\Request\Consume\UpdateCodeRequest;

class UpdateCodeRequestTest extends TestCase
{
    private UpdateCodeRequest $request;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->request = new UpdateCodeRequest();
    }
    
    public function testRequestInstantiation(): void
    {
        $this->assertInstanceOf(UpdateCodeRequest::class, $this->request);
    }
}