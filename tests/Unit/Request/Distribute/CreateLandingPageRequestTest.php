<?php

namespace WechatOfficialAccountCardBundle\Tests\Unit\Request\Distribute;

use PHPUnit\Framework\TestCase;
use WechatOfficialAccountCardBundle\Request\Distribute\CreateLandingPageRequest;

class CreateLandingPageRequestTest extends TestCase
{
    private CreateLandingPageRequest $request;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->request = new CreateLandingPageRequest();
    }
    
    public function testRequestInstantiation(): void
    {
        $this->assertInstanceOf(CreateLandingPageRequest::class, $this->request);
    }
}