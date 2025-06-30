<?php

namespace WechatOfficialAccountCardBundle\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use WechatOfficialAccountCardBundle\WechatOfficialAccountCardBundle;

class WechatOfficialAccountCardBundleTest extends TestCase
{
    private WechatOfficialAccountCardBundle $bundle;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->bundle = new WechatOfficialAccountCardBundle();
    }
    
    public function testBundleExtendsSymfonyBundle(): void
    {
        $this->assertInstanceOf(Bundle::class, $this->bundle);
    }
    
    public function testBundleInstantiation(): void
    {
        $bundle = new WechatOfficialAccountCardBundle();
        
        $this->assertInstanceOf(WechatOfficialAccountCardBundle::class, $bundle);
    }
    
    public function testGetName(): void
    {
        $this->assertEquals('WechatOfficialAccountCardBundle', $this->bundle->getName());
    }
    
    public function testGetPath(): void
    {
        $path = $this->bundle->getPath();
        
        $this->assertStringContainsString('wechat-official-account-card-bundle', $path);
    }
}