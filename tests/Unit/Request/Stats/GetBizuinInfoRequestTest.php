<?php

namespace WechatOfficialAccountCardBundle\Tests\Unit\Request\Stats;

use PHPUnit\Framework\TestCase;
use WechatOfficialAccountCardBundle\Request\Stats\GetBizuinInfoRequest;

class GetBizuinInfoRequestTest extends TestCase
{
    private GetBizuinInfoRequest $request;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->request = new GetBizuinInfoRequest();
    }
    
    public function testRequestInstantiation(): void
    {
        $this->assertInstanceOf(GetBizuinInfoRequest::class, $this->request);
    }
}