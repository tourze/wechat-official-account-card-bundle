<?php

namespace WechatOfficialAccountCardBundle\Tests\Unit\Enum;

use PHPUnit\Framework\TestCase;
use WechatOfficialAccountCardBundle\Enum\CardCondSource;

class CardCondSourceTest extends TestCase
{
    public function testCardCondSourceValues(): void
    {
        $this->assertEquals(0, CardCondSource::PLATFORM->value);
        $this->assertEquals(1, CardCondSource::API->value);
    }
    
    public function testCardCondSourceInstances(): void
    {
        $this->assertInstanceOf(CardCondSource::class, CardCondSource::PLATFORM);
        $this->assertInstanceOf(CardCondSource::class, CardCondSource::API);
    }
    
    public function testCardCondSourceEquality(): void
    {
        $this->assertEquals(CardCondSource::PLATFORM, CardCondSource::PLATFORM);
        $this->assertEquals(CardCondSource::API, CardCondSource::API);
        
        $this->assertNotEquals(CardCondSource::PLATFORM, CardCondSource::API);
    }
    
    public function testCardCondSourceFromInt(): void
    {
        $this->assertEquals(CardCondSource::PLATFORM, CardCondSource::from(0));
        $this->assertEquals(CardCondSource::API, CardCondSource::from(1));
        
        $this->expectException(\ValueError::class);
        CardCondSource::from(999);
    }
    
    public function testCardCondSourceTryFrom(): void
    {
        $this->assertEquals(CardCondSource::PLATFORM, CardCondSource::tryFrom(0));
        $this->assertEquals(CardCondSource::API, CardCondSource::tryFrom(1));
        
        $this->assertNull(CardCondSource::tryFrom(999));
    }
    
    public function testCardCondSourceNames(): void
    {
        $this->assertEquals('PLATFORM', CardCondSource::PLATFORM->name);
        $this->assertEquals('API', CardCondSource::API->name);
    }
    
    public function testCardCondSourceCases(): void
    {
        $cases = CardCondSource::cases();
        
        $this->assertCount(2, $cases);
        $this->assertContainsOnlyInstancesOf(CardCondSource::class, $cases);
        
        $this->assertContains(CardCondSource::PLATFORM, $cases);
        $this->assertContains(CardCondSource::API, $cases);
    }
    
    public function testGetLabel(): void
    {
        $this->assertEquals('公众平台创建', CardCondSource::PLATFORM->getLabel());
        $this->assertEquals('API创建', CardCondSource::API->getLabel());
    }
    
}