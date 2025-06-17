<?php

namespace WechatOfficialAccountCardBundle\Tests\Repository;

use PHPUnit\Framework\TestCase;
use WechatOfficialAccountCardBundle\Repository\CardRepository;

class CardRepositoryTest extends TestCase
{
    public function testRepositoryClass(): void
    {
        // 验证仓库类的基本信息
        $reflectionClass = new \ReflectionClass(CardRepository::class);
        
        // 验证仓库类存在
        $this->assertTrue($reflectionClass->isInstantiable());
        
        // 验证仓库类继承自 ServiceEntityRepository
        $this->assertTrue($reflectionClass->isSubclassOf('Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository'));
        
        // 验证构造函数参数
        $constructor = $reflectionClass->getConstructor();
        $parameters = $constructor->getParameters();
        $this->assertCount(1, $parameters);
        $this->assertEquals('registry', $parameters[0]->getName());
        $this->assertEquals('Doctrine\Persistence\ManagerRegistry', (string) $parameters[0]->getType());
    }
    
    public function testEntityType(): void
    {
        // 通过反射获取仓库类的实体类型信息
        $reflectionMethod = new \ReflectionMethod(CardRepository::class, '__construct');
        $body = file_get_contents($reflectionMethod->getFileName());
        
        // 验证 Card 实体是否在构造函数中使用
        $this->assertStringContainsString('parent::__construct($registry, Card::class)', $body);
    }
    
    public function testRepositoryDocBlock(): void
    {
        $reflectionClass = new \ReflectionClass(CardRepository::class);
        $docBlock = $reflectionClass->getDocComment();
        
        // 验证文档块中是否包含预期的方法说明
        $this->assertStringContainsString('@method Card|null find($id', $docBlock);
        $this->assertStringContainsString('@method Card|null findOneBy(array $criteria', $docBlock);
        $this->assertStringContainsString('@method Card[]    findAll()', $docBlock);
        $this->assertStringContainsString('@method Card[]    findBy(array $criteria', $docBlock);
    }
} 