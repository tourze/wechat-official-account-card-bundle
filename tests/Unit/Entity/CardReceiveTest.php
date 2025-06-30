<?php

namespace WechatOfficialAccountCardBundle\Tests\Unit\Entity;

use PHPUnit\Framework\TestCase;
use WechatOfficialAccountCardBundle\Entity\Card;
use WechatOfficialAccountCardBundle\Entity\CardCode;
use WechatOfficialAccountCardBundle\Entity\CardReceive;

class CardReceiveTest extends TestCase
{
    private CardReceive $cardReceive;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->cardReceive = new CardReceive();
    }
    
    public function testCardReceiveInitialState(): void
    {
        $cardReceive = new CardReceive();
        
        $this->assertEquals(0, $cardReceive->getId());
        $this->assertFalse($cardReceive->isUsed());
        $this->assertFalse($cardReceive->isUnavailable());
        $this->assertFalse($cardReceive->isGiven());
        $this->assertNull($cardReceive->getUsedAt());
        $this->assertNull($cardReceive->getUnavailableAt());
        $this->assertNull($cardReceive->getGivenAt());
        $this->assertNull($cardReceive->getGivenToOpenId());
    }
    
    public function testGetterSetterMethods(): void
    {
        $card = $this->createMock(Card::class);
        $cardCode = $this->createMock(CardCode::class);
        
        $this->cardReceive->setCard($card);
        $this->assertSame($card, $this->cardReceive->getCard());
        
        $this->cardReceive->setCardCode($cardCode);
        $this->assertSame($cardCode, $this->cardReceive->getCardCode());
        
        $this->cardReceive->setOpenId('test_openid');
        $this->assertEquals('test_openid', $this->cardReceive->getOpenId());
        
        $this->cardReceive->setIsUsed(true);
        $this->assertTrue($this->cardReceive->isUsed());
        $this->assertInstanceOf(\DateTimeImmutable::class, $this->cardReceive->getUsedAt());
        
        $this->cardReceive->setIsUnavailable(true);
        $this->assertTrue($this->cardReceive->isUnavailable());
        $this->assertInstanceOf(\DateTimeImmutable::class, $this->cardReceive->getUnavailableAt());
        
        $this->cardReceive->setIsGiven(true);
        $this->assertTrue($this->cardReceive->isGiven());
        $this->assertInstanceOf(\DateTimeImmutable::class, $this->cardReceive->getGivenAt());
        
        $this->cardReceive->setGivenToOpenId('target_openid');
        $this->assertEquals('target_openid', $this->cardReceive->getGivenToOpenId());
    }
    
    public function testToString(): void
    {
        $this->cardReceive->setOpenId('test_openid');
        $this->assertEquals('test_openid', (string) $this->cardReceive);
        
        $newCardReceive = new CardReceive();
        $this->assertEquals('New CardReceive', (string) $newCardReceive);
    }
}