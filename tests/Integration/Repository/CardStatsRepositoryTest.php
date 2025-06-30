<?php

namespace WechatOfficialAccountCardBundle\Tests\Integration\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Framework\TestCase;
use WechatOfficialAccountCardBundle\Entity\CardStats;
use WechatOfficialAccountCardBundle\Repository\CardStatsRepository;

class CardStatsRepositoryTest extends TestCase
{
    private CardStatsRepository $repository;
    private ManagerRegistry $registry;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->registry = $this->createMock(ManagerRegistry::class);
        $this->repository = new CardStatsRepository($this->registry);
    }
    
    public function testRepositoryExtendingServiceEntityRepository(): void
    {
        $this->assertInstanceOf(ServiceEntityRepository::class, $this->repository);
    }
    
    
    public function testRepositoryInstantiation(): void
    {
        $repository = new CardStatsRepository($this->registry);
        $this->assertInstanceOf(CardStatsRepository::class, $repository);
    }
}