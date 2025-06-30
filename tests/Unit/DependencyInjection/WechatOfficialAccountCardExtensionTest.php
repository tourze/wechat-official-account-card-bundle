<?php

namespace WechatOfficialAccountCardBundle\Tests\Unit\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use WechatOfficialAccountCardBundle\DependencyInjection\WechatOfficialAccountCardExtension;

class WechatOfficialAccountCardExtensionTest extends TestCase
{
    private WechatOfficialAccountCardExtension $extension;
    private ContainerBuilder $container;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->extension = new WechatOfficialAccountCardExtension();
        $this->container = new ContainerBuilder();
    }
    
    public function testExtensionExtendsSymfonyExtension(): void
    {
        $this->assertInstanceOf(Extension::class, $this->extension);
    }
    
    public function testExtensionInstantiation(): void
    {
        $extension = new WechatOfficialAccountCardExtension();
        
        $this->assertInstanceOf(WechatOfficialAccountCardExtension::class, $extension);
    }
    
    public function testLoadMethod(): void
    {
        $configs = [];
        
        $this->extension->load($configs, $this->container);
        
        $this->assertTrue(true);
    }
    
    public function testLoadWithConfiguration(): void
    {
        $configs = [
            'wechat_official_account_card' => []
        ];
        
        $this->extension->load($configs, $this->container);
        
        $this->assertTrue(true);
    }
}