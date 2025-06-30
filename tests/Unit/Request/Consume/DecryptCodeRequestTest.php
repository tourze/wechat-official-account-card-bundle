<?php

namespace WechatOfficialAccountCardBundle\Tests\Unit\Request\Consume;

use PHPUnit\Framework\TestCase;
use WechatOfficialAccountCardBundle\Request\Consume\DecryptCodeRequest;

class DecryptCodeRequestTest extends TestCase
{
    private DecryptCodeRequest $request;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->request = new DecryptCodeRequest();
    }
    
    public function testRequestInstantiation(): void
    {
        $this->assertInstanceOf(DecryptCodeRequest::class, $this->request);
    }
}