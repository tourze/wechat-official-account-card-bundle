<?php

namespace WechatOfficialAccountCardBundle\Tests\Unit\Request\Basic;

use PHPUnit\Framework\TestCase;
use WechatOfficialAccountCardBundle\Request\Basic\ModifyStockRequest;

class ModifyStockRequestTest extends TestCase
{
    private ModifyStockRequest $request;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->request = new ModifyStockRequest();
    }
    
    public function testRequestInstantiation(): void
    {
        $this->assertInstanceOf(ModifyStockRequest::class, $this->request);
    }
}