<?php

namespace WechatOfficialAccountCardBundle\Tests\Unit\Enum;

use PHPUnit\Framework\TestCase;
use WechatOfficialAccountCardBundle\Enum\LandingPageBannerType;

class LandingPageBannerTypeTest extends TestCase
{
    public function testLandingPageBannerTypeValues(): void
    {
        $this->assertEquals(0, LandingPageBannerType::URL->value);
        $this->assertEquals(1, LandingPageBannerType::BANNER->value);
        $this->assertEquals(2, LandingPageBannerType::CELL->value);
    }
    
    public function testLandingPageBannerTypeInstances(): void
    {
        $this->assertInstanceOf(LandingPageBannerType::class, LandingPageBannerType::URL);
        $this->assertInstanceOf(LandingPageBannerType::class, LandingPageBannerType::BANNER);
        $this->assertInstanceOf(LandingPageBannerType::class, LandingPageBannerType::CELL);
    }
    
    public function testLandingPageBannerTypeEquality(): void
    {
        $this->assertEquals(LandingPageBannerType::URL, LandingPageBannerType::URL);
        $this->assertEquals(LandingPageBannerType::BANNER, LandingPageBannerType::BANNER);
        
        $this->assertNotEquals(LandingPageBannerType::URL, LandingPageBannerType::BANNER);
        $this->assertNotEquals(LandingPageBannerType::BANNER, LandingPageBannerType::CELL);
    }
    
    public function testLandingPageBannerTypeFromInt(): void
    {
        $this->assertEquals(LandingPageBannerType::URL, LandingPageBannerType::from(0));
        $this->assertEquals(LandingPageBannerType::BANNER, LandingPageBannerType::from(1));
        $this->assertEquals(LandingPageBannerType::CELL, LandingPageBannerType::from(2));
        
        $this->expectException(\ValueError::class);
        LandingPageBannerType::from(999);
    }
    
    public function testLandingPageBannerTypeTryFrom(): void
    {
        $this->assertEquals(LandingPageBannerType::URL, LandingPageBannerType::tryFrom(0));
        $this->assertEquals(LandingPageBannerType::BANNER, LandingPageBannerType::tryFrom(1));
        $this->assertEquals(LandingPageBannerType::CELL, LandingPageBannerType::tryFrom(2));
        
        $this->assertNull(LandingPageBannerType::tryFrom(999));
    }
    
    public function testLandingPageBannerTypeNames(): void
    {
        $this->assertEquals('URL', LandingPageBannerType::URL->name);
        $this->assertEquals('BANNER', LandingPageBannerType::BANNER->name);
        $this->assertEquals('CELL', LandingPageBannerType::CELL->name);
    }
    
    public function testLandingPageBannerTypeCases(): void
    {
        $cases = LandingPageBannerType::cases();
        
        $this->assertCount(3, $cases);
        $this->assertContainsOnlyInstancesOf(LandingPageBannerType::class, $cases);
        
        $this->assertContains(LandingPageBannerType::URL, $cases);
        $this->assertContains(LandingPageBannerType::BANNER, $cases);
        $this->assertContains(LandingPageBannerType::CELL, $cases);
    }
    
    public function testGetLabel(): void
    {
        $this->assertEquals('图文消息场景', LandingPageBannerType::URL->getLabel());
        $this->assertEquals('朋友圈场景', LandingPageBannerType::BANNER->getLabel());
        $this->assertEquals('单张卡券页面场景', LandingPageBannerType::CELL->getLabel());
    }
    
}