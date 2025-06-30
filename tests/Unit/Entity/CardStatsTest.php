<?php

namespace WechatOfficialAccountCardBundle\Tests\Unit\Entity;

use PHPUnit\Framework\TestCase;
use WechatOfficialAccountCardBundle\Entity\Card;
use WechatOfficialAccountCardBundle\Entity\CardStats;

class CardStatsTest extends TestCase
{
    private CardStats $cardStats;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->cardStats = new CardStats();
    }
    
    public function testCardStatsInitialState(): void
    {
        $cardStats = new CardStats();
        
        $this->assertEquals(0, $cardStats->getId());
        $this->assertEquals(0, $cardStats->getReceiveCount());
        $this->assertEquals(0, $cardStats->getUseCount());
        $this->assertEquals(0, $cardStats->getGiveCount());
        $this->assertEquals(0, $cardStats->getViewCount());
        $this->assertEquals(0, $cardStats->getNewFollowCount());
        $this->assertEquals(0, $cardStats->getUnfollowCount());
        $this->assertEquals(0, $cardStats->getGiveReceiveCount());
    }
    
    public function testGetterSetterMethods(): void
    {
        $card = $this->createMock(Card::class);
        
        $this->cardStats->setCard($card);
        $this->assertSame($card, $this->cardStats->getCard());
        
        $statsDate = new \DateTimeImmutable('2023-01-01');
        $this->cardStats->setStatsDate($statsDate);
        $this->assertEquals($statsDate, $this->cardStats->getStatsDate());
        
        $this->cardStats->setReceiveCount(100);
        $this->assertEquals(100, $this->cardStats->getReceiveCount());
        
        $this->cardStats->setUseCount(80);
        $this->assertEquals(80, $this->cardStats->getUseCount());
        
        $this->cardStats->setGiveCount(20);
        $this->assertEquals(20, $this->cardStats->getGiveCount());
        
        $this->cardStats->setViewCount(200);
        $this->assertEquals(200, $this->cardStats->getViewCount());
        
        $this->cardStats->setNewFollowCount(50);
        $this->assertEquals(50, $this->cardStats->getNewFollowCount());
        
        $this->cardStats->setUnfollowCount(5);
        $this->assertEquals(5, $this->cardStats->getUnfollowCount());
        
        $this->cardStats->setGiveReceiveCount(15);
        $this->assertEquals(15, $this->cardStats->getGiveReceiveCount());
    }
}