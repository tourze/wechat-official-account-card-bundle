<?php

namespace WechatOfficialAccountCardBundle\Tests\Integration\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Framework\TestCase;
use WechatOfficialAccountCardBundle\Entity\CardReceive;
use WechatOfficialAccountCardBundle\Repository\CardReceiveRepository;

class CardReceiveRepositoryTest extends TestCase
{
    private CardReceiveRepository $repository;
    private ManagerRegistry $registry;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->registry = $this->createMock(ManagerRegistry::class);
        $this->repository = new CardReceiveRepository($this->registry);
    }
    
    public function testRepositoryExtendingServiceEntityRepository(): void
    {
        $this->assertInstanceOf(ServiceEntityRepository::class, $this->repository);
    }
    
    
    public function testRepositoryInstantiation(): void
    {
        $repository = new CardReceiveRepository($this->registry);
        $this->assertInstanceOf(CardReceiveRepository::class, $repository);
    }
}