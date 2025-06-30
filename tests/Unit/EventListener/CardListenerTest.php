<?php

namespace WechatOfficialAccountCardBundle\Tests\Unit\EventListener;

use PHPUnit\Framework\TestCase;
use WechatOfficialAccountBundle\Service\OfficialAccountClient;
use WechatOfficialAccountCardBundle\EventListener\CardListener;

class CardListenerTest extends TestCase
{
    private CardListener $listener;
    private OfficialAccountClient $client;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->client = $this->createMock(OfficialAccountClient::class);
        $this->listener = new CardListener($this->client);
    }
    
    public function testCardListenerInstantiation(): void
    {
        $this->assertInstanceOf(CardListener::class, $this->listener);
    }
}