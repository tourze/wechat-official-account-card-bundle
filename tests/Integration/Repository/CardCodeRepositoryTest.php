<?php

namespace WechatOfficialAccountCardBundle\Tests\Integration\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Framework\TestCase;
use WechatOfficialAccountCardBundle\Entity\CardCode;
use WechatOfficialAccountCardBundle\Repository\CardCodeRepository;

class CardCodeRepositoryTest extends TestCase
{
    private CardCodeRepository $repository;
    private ManagerRegistry $registry;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->registry = $this->createMock(ManagerRegistry::class);
        $this->repository = new CardCodeRepository($this->registry);
    }
    
    public function testRepositoryExtendingServiceEntityRepository(): void
    {
        $this->assertInstanceOf(ServiceEntityRepository::class, $this->repository);
    }
    
    
    public function testRepositoryInstantiation(): void
    {
        $repository = new CardCodeRepository($this->registry);
        $this->assertInstanceOf(CardCodeRepository::class, $repository);
    }
}