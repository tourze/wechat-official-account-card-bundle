<?php

namespace WechatOfficialAccountCardBundle\Tests\Unit\Request\Distribute;

use PHPUnit\Framework\TestCase;
use WechatOfficialAccountCardBundle\Request\Distribute\ImportCodeRequest;

class ImportCodeRequestTest extends TestCase
{
    private ImportCodeRequest $request;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->request = new ImportCodeRequest();
    }
    
    public function testRequestInstantiation(): void
    {
        $this->assertInstanceOf(ImportCodeRequest::class, $this->request);
    }
}