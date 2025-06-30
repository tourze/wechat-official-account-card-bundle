<?php

namespace WechatOfficialAccountCardBundle\Tests\Unit\Entity;

use PHPUnit\Framework\TestCase;
use WechatOfficialAccountCardBundle\Entity\Card;
use WechatOfficialAccountCardBundle\Entity\CardCode;

class CardCodeTest extends TestCase
{
    private CardCode $cardCode;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->cardCode = new CardCode();
    }
    
    public function testCardCodeInitialState(): void
    {
        $cardCode = new CardCode();
        
        $this->assertEquals(0, $cardCode->getId());
        $this->assertFalse($cardCode->isUsed());
        $this->assertFalse($cardCode->isUnavailable());
        $this->assertNull($cardCode->getUsedAt());
        $this->assertNull($cardCode->getUnavailableAt());
    }
    
    public function testGetterSetterMethods(): void
    {
        $card = $this->createMock(Card::class);
        
        $this->cardCode->setCard($card);
        $this->assertSame($card, $this->cardCode->getCard());
        
        $this->cardCode->setCode('TEST_CODE_123');
        $this->assertEquals('TEST_CODE_123', $this->cardCode->getCode());
        
        $this->cardCode->setIsUsed(true);
        $this->assertTrue($this->cardCode->isUsed());
        $this->assertInstanceOf(\DateTimeImmutable::class, $this->cardCode->getUsedAt());
        
        $this->cardCode->setIsUnavailable(true);
        $this->assertTrue($this->cardCode->isUnavailable());
        $this->assertInstanceOf(\DateTimeImmutable::class, $this->cardCode->getUnavailableAt());
        
        $now = new \DateTimeImmutable();
        $this->cardCode->setUsedAt($now);
        $this->assertEquals($now, $this->cardCode->getUsedAt());
        
        $this->cardCode->setUnavailableAt($now);
        $this->assertEquals($now, $this->cardCode->getUnavailableAt());
    }
    
    public function testToString(): void
    {
        $this->cardCode->setCode('TEST_CODE');
        $this->assertEquals('TEST_CODE', (string) $this->cardCode);
        
        $newCardCode = new CardCode();
        $this->assertEquals('New CardCode', (string) $newCardCode);
    }
    
    public function testBooleanFields(): void
    {
        $this->cardCode->setIsUsed(false);
        $this->assertFalse($this->cardCode->isUsed());
        
        $this->cardCode->setIsUnavailable(false);
        $this->assertFalse($this->cardCode->isUnavailable());
    }
}