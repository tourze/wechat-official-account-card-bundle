<?php

namespace WechatOfficialAccountCardBundle\Tests\Unit\Request\Basic;

use PHPUnit\Framework\TestCase;
use WechatOfficialAccountCardBundle\Request\Basic\DeleteRequest;

class DeleteRequestTest extends TestCase
{
    private DeleteRequest $request;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->request = new DeleteRequest();
    }
    
    public function testRequestInstantiation(): void
    {
        $this->assertInstanceOf(DeleteRequest::class, $this->request);
    }
}