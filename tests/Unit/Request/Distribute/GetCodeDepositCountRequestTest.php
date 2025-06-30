<?php

namespace WechatOfficialAccountCardBundle\Tests\Unit\Request\Distribute;

use PHPUnit\Framework\TestCase;
use WechatOfficialAccountCardBundle\Request\Distribute\GetCodeDepositCountRequest;

class GetCodeDepositCountRequestTest extends TestCase
{
    private GetCodeDepositCountRequest $request;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->request = new GetCodeDepositCountRequest();
    }
    
    public function testRequestInstantiation(): void
    {
        $this->assertInstanceOf(GetCodeDepositCountRequest::class, $this->request);
    }
}